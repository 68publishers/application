<?php

namespace SixtyEightPublishers\Application\DI;

use Nette\DI\CompilerExtension;
use SixtyEightPublishers\Application\ConfigurationException;
use SixtyEightPublishers\Application\Environment;


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

	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$config = $this->getConfig($this->defaults);

		$environment = $builder->addDefinition($this->prefix('environment'))
			->setClass(Environment::class);

		if (empty($config['profile']))
			throw new ConfigurationException("You must define some profile combination in your configuration.");

		$requiredProfileParams = ['country', 'language', 'currency'];
		foreach ($config['profile'] as $profileName => $profile)
		{
			if (!empty(array_diff($requiredProfileParams, array_keys($profile))))
				throw new ConfigurationException("Problem with \"{$profileName}\" profile configuration. There are missing some of the required parameters (country, language, currency).");

			$environment->addSetup('addProfile', [
				$profileName,
				(array) $profile['country'],
				(array) $profile['language'],
				(array) $profile['currency'],
				array_key_exists('domain', $profile) ? (array) $profile['domain'] : []
			]);
		}
	}


}