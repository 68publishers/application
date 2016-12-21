<?php

namespace SixtyEightPublishers\Application\DI;

use Nette\DI\CompilerExtension;
use SixtyEightPublishers\Application\ConfigurationException;
use SixtyEightPublishers\Application\Environment;
use SixtyEightPublishers\Application\ProfileContainer;
use SixtyEightPublishers\Application\IEnvironmentDetector;
use SixtyEightPublishers\Application\Detector\NetteRequestDetector;


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

		$profileContainer = $builder->addDefinition($this->prefix('profileContainer'))
			->setClass(ProfileContainer::class);

		if (empty($config['profile']))
			throw new ConfigurationException("You must define some profile combination in your configuration.");

		$requiredProfileParams = ['country', 'language', 'currency'];
		foreach ($config['profile'] as $profileName => $profile)
		{
			if (!empty(array_diff($requiredProfileParams, array_keys($profile))))
				throw new ConfigurationException("Problem with \"{$profileName}\" profile configuration. There are missing some of the required parameters (country, language, currency).");

			$profileContainer->addSetup('addProfile', [
				$profileName,
				(array) $profile['country'],
				(array) $profile['language'],
				(array) $profile['currency'],
				array_key_exists('domain', $profile) ? (array) $profile['domain'] : []
			]);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function beforeCompile()
	{
		$builder = $this->getContainerBuilder();
		$detectors = $builder->findByType(IEnvironmentDetector::class);

		if (count($detectors) > 1)
			foreach ($detectors as $name => $definition)
				if ($definition->getClass() === NetteRequestDetector::class)
					$builder->removeDefinition($name);
	}

}
