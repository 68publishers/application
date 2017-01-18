<?php

declare(strict_types=1);

namespace SixtyEightPublishers\Application\Components;

use Nette\Application\IPresenter;
use Nette\Application\UI\Control;

class Template extends \Nette\Bridges\ApplicationLatte\Template
{
	/** @var  NULL|TemplateManager */
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
	 * @param null|string $file
	 *
	 * @return NULL|string
	 */
	private function getOverloadingFile($file = NULL)
	{
		if (array_key_exists('control', $this->getParameters()) && $this->control instanceof Control && !$this->control instanceof IPresenter) {
			if ($this->templateManager instanceof TemplateManager) {
				/** @var Control $control */
				$control = $this->control;
				$file = $this->templateManager->getTemplate($control->getPresenter()->getName(), $control->getUniqueId(), $file ?: $this->getFile());
			}
		}

		return $file;
	}

	/**
	 * {@inheritdoc}
	 */
	public function render($file = NULL, array $params = [])
	{
		$file = $this->getOverloadingFile($file);

		parent::render($file, $params);
	}

	/**
	 * {@inheritdoc}
	 */
	public function __toString()
	{
		$this->setFile($this->getOverloadingFile());

		return parent::__toString();
	}
}
