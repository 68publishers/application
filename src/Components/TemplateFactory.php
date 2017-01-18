<?php

declare(strict_types=1);

namespace SixtyEightPublishers\Application\Components;

use Nette\Application\UI\Control;

class TemplateFactory extends \Nette\Bridges\ApplicationLatte\TemplateFactory
{
	/** @var TemplateManager */
	private $templateManager;

	/**
	 * @param \SixtyEightPublishers\Application\Components\TemplateManager $templateManager
	 *
	 * @return void
	 */
	public function setTemplateManager(TemplateManager $templateManager)
	{
		$this->templateManager = $templateManager;
	}

	/**
	 * @param \Nette\Application\UI\Control|null $control
	 *
	 * @throws \SixtyEightPublishers\Application\Components\ConfigurationException
	 * @return \SixtyEightPublishers\Application\Components\Template
	 */
	public function createTemplate(Control $control = NULL)
	{
		if (!$this->templateManager) {
			$class = self::class;
			throw new ConfigurationException("You must set template manager object via {$class}::setTemplateManager() method.");
		}
		$template = parent::createTemplate($control);

		if ($template instanceof Template) {
			$template->setTemplateManager($this->templateManager);
		}

		return $template;
	}
}
