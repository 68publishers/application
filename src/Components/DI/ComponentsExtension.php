<?php

declare(strict_types=1);

namespace SixtyEightPublishers\Application\Components\DI;

use Nette\DI\CompilerExtension;
use Nette\DI\Helpers;
use SixtyEightPublishers\Application\Components\Template;
use SixtyEightPublishers\Application\Components\TemplateManager;
use SixtyEightPublishers\Application\Components\TemplateFactory;

class ComponentsExtension extends CompilerExtension
{
	/**
	 * @var array
	 */
	private $defaults = [
		'enabled' => TRUE,
		'debugger' => FALSE,
		'templatesDir' => '%appDir%/templates'
	];

	/**
	 * {@inheritdoc}
	 */
	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('templateManager'))
			->setClass(TemplateManager::class);
	}

	/**
	 * {@inheritdoc}
	 */
	public function beforeCompile()
	{
		$builder = $this->getContainerBuilder();
		$config = $this->getConfig($this->defaults);

		if (($templateFactory = $builder->getDefinition('latte.templateFactory')) && class_exists('Nette\Bridges\ApplicationLatte\TemplateFactory')
			&& class_exists('Nette\Bridges\ApplicationLatte\Template')) {
			$templateFactory->setFactory(TemplateFactory::class);
			$templateFactory->setArguments(['templateClass' => Template::class]);
			$templateFactory->addSetup('setTemplateManager', ['@' . $this->prefix('templateManager')]);
		}

		if ($config['enabled']) {
			$templatesDir = Helpers::expand($config['templatesDir'], $builder->parameters);
			$builder->getDefinition($this->prefix('templateManager'))
				->setArguments([
					'map' => TemplateManager::createMap($templatesDir),
					'templatesDir' => $templatesDir,
				]);
		}
	}
}
