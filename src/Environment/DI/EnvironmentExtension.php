<?php

declare(strict_types=1);

namespace SixtyEightPublishers\Application\Environment\DI;

use Nette\DI\CompilerExtension;
use Nette\PhpGenerator\ClassType;
use SixtyEightPublishers\Application\Environment\ConfigurationException;
use SixtyEightPublishers\Application\Environment\Environment;
use SixtyEightPublishers\Application\Environment\ProfileContainer;
use SixtyEightPublishers\Application\Environment\IEnvironmentDetector;
use SixtyEightPublishers\Application\Environment\Detector\NetteRequestDetector;
use SixtyEightPublishers\Application\Environment\Diagnostics\Panel;
use SixtyEightPublishers\Application\Environment\IProfileStorage;
use SixtyEightPublishers\Application\Environment\Storage\SessionProfileStorage;

class EnvironmentExtension extends CompilerExtension
{
	/**
	 * @var array
	 */
	private $defaults = [
		'debugger' => FALSE,
		'localeDomain' => FALSE,
		'mode' => 'tolerant',
		'profile' => [],
	];

	/**
	 * {@inheritdoc}
	 */
	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$config = $this->getConfig($this->defaults);

		$builder->addDefinition($this->prefix('environment'))
			->setClass(Environment::class);

		$builder->addDefinition($this->prefix('detector'))
			->setClass(NetteRequestDetector::class);

		$builder->addDefinition($this->prefix('profileStorage'))
			->setClass(SessionProfileStorage::class);

		$profileContainer = $builder->addDefinition($this->prefix('profileContainer'))
			->setClass(ProfileContainer::class);

		if (empty($config['profile'])) {
			throw new ConfigurationException("You must define some profile combination in your configuration.");
		}

		$requiredProfileParams = ['country', 'language', 'currency'];
		foreach ($config['profile'] as $profileName => $profile) {
			if (!empty(array_diff($requiredProfileParams, array_keys($profile)))) {
				throw new ConfigurationException("Problem with \"{$profileName}\" profile configuration. There are missing some of the required parameters (country, language, currency).");
			}

			$profileContainer->addSetup('addProfile', [
				$profileName,
				(array) $profile['country'],
				(array) $profile['language'],
				(array) $profile['currency'],
				array_key_exists('domain', $profile) ? (array) $profile['domain'] : [],
				!(array_key_exists('disable', $profile) && $profile['disable'] === TRUE),
			]);
		}

		if ($this->useDebugger()) {
			$builder->addDefinition($this->prefix('panel'))
				->setClass(Panel::class)
				->setInject(FALSE)
				->setAutowired(FALSE);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function beforeCompile()
	{
		$builder = $this->getContainerBuilder();
		$detectors = $builder->findByType(IEnvironmentDetector::class);
		$storage = $builder->findByType(IProfileStorage::class);

		if (count($detectors) > 1) {
			foreach ($detectors as $name => $definition) {
				if ($definition->getType() === NetteRequestDetector::class) {
					$builder->removeDefinition($name);
				}
			}
		}

		if (count($storage) > 1) {
			foreach ($storage as $name => $definition) {
				if ($definition->getType() === SessionProfileStorage::class) {
					$builder->removeDefinition($name);
				}
			}
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function afterCompile(ClassType $class)
	{
		$initialize = $class->methods['initialize'];

		if ($this->useDebugger()) {
			$bar = $this->getContainerBuilder()->getByType('Tracy\Bar');
			$initialize->addBody('$this->getService(?)->addPanel($this->getService(?));', [
				$bar,
				$this->prefix('panel'),
			]);
		}
	}

	/**
	 * @return bool
	 */
	private function useDebugger()
	{
		$config = $this->getConfig($this->defaults);
		$bar = $this->getContainerBuilder()->getByType('Tracy\Bar');

		return ($config['debugger'] && $bar && interface_exists('Tracy\IBarPanel'));
	}
}
