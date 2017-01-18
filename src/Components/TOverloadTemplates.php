<?php

declare(strict_types=1);

namespace SixtyEightPublishers\Application\Components;

use Nette\Application\UI\Control;
use Nette\ComponentModel\IComponent;
use Nette\MemberAccessException;
use Nette\Reflection\Method;
use Nette\UnexpectedValueException;

trait TOverloadTemplates
{
	/** @var NULL|TemplateManager */
	protected $templateManager;

	/**
	 * @param \SixtyEightPublishers\Application\Components\TemplateManager $templateManager
	 *
	 * @return void
	 */
	public function injectTemplateManager(TemplateManager $templateManager)
	{
		if (!$this instanceof Control) {
			$class = Control::class;
			throw new MemberAccessException("Trait " . __TRAIT__ . " can be used only in objects that are descendants of {$class}.");
		}

		$this->templateManager = $templateManager;
	}


	/**
	 * @param string $name
	 *
	 * @return NULL|IComponent
	 */
	protected function createComponent($name)
	{
		$ucName = ucfirst($name);
		$method = 'createComponent' . $ucName;
		if ($ucName !== $name && method_exists($this, $method) && (new \ReflectionMethod($this, $method))->getName() === $method) {
			$component = $this->$method($name);
			if (!$component instanceof IComponent && !isset($this->components[$name])) {
				$class = get_class($this);
				throw new UnexpectedValueException("Method $class::$method() did not return or create the desired component.");
			}
			
			$annotations = (new Method($this, $method))->getAnnotations();
			if (array_key_exists('template', $annotations)) {
				foreach ($annotations['template'] as $value) {
					$this->templateManager->monitor($this->getName(), (is_string($value)) ? "{$name}-{$value}" : $name);
				}
			}

			return $component;
		}
	}
}
