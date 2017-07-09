<?php

declare(strict_types=1);

namespace SixtyEightPublishers\Application\RemoteAccessManager\DI;

use Nette\DI\CompilerExtension;
use SixtyEightPublishers\Application\RemoteAccessManager\Handler\IAccessHandler;
use SixtyEightPublishers\Application\RemoteAccessManager\RemoteAccessManager;
use SixtyEightPublishers\Application\Environment\ConfigurationException;
use SixtyEightPublishers\Application\RemoteAccessManager\Handler\DefaultAccessHandler;

class RemoteAccessManagerExtension extends CompilerExtension
{
	/**
	 * @var array
	 */
	private $defaults = [
		'disable' => FALSE,
		'allowAll' => RemoteAccessManager::ALLOWED_ALL,
		'secretKey' => RemoteAccessManager::COOKIE_SECRET,
		'whitelist' => NULL,
		'blacklist' => NULL,
	];

	/**
	 * {@inheritdoc}
	 */
	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$config = $this->getConfig($this->defaults);

		if (!is_bool($config['allowAll'])) {
			throw new ConfigurationException("Config key 'allowAll' must be a bool value.");
		}

		if ($config['disable'] === TRUE) {
			return;
		}

		$builder->addDefinition($this->prefix('handler'))
			->setClass(DefaultAccessHandler::class);

		$builder->addDefinition($this->prefix('remoteAccess'))
			->setClass(RemoteAccessManager::class, [
				'whitelist' => $config['whitelist'],
				'blacklist' => $config['blacklist'],
				'key' => $config['secretKey'],
				'mode' => $config['allowAll'],
				'consoleMode' => $builder->parameters['consoleMode'],
			])
			->addTag('run', TRUE)
			->addSetup('process');
	}

	/**
	 * {@inheritdoc}
	 */
	public function beforeCompile()
	{
		$builder = $this->getContainerBuilder();
		$handlers = $builder->findByType(IAccessHandler::class);

		if (count($handlers) > 1) {
			foreach ($handlers as $name => $definition) {
				if ($definition->getClass() === DefaultAccessHandler::class) {
					$builder->removeDefinition($name);
				}
			}
		}
	}
}
