<?php

namespace SixtyEightPublishers\Application\Environment\Translation;

use Kdyby\Translation\ITranslator;
use SixtyEightPublishers\Application\Environment\Environment;

/**
 * @internal
 */
class ChangeTranslatorLocaleHandler
{
	/** @var \Kdyby\Translation\ITranslator  */
	private $translator;

	/**
	 * @param \SixtyEightPublishers\Application\Environment\Environment     $environment
	 * @param \Kdyby\Translation\ITranslator                                $translator
	 */
	public function __construct(Environment $environment, ITranslator $translator)
	{
		$environment->getProfile()->onChangeLanguage[] = [$this, 'onChangeLanguage'];
		$this->translator = $translator;
	}

	/**
	 * @param string $language
	 */
	public function onChangeLanguage($language)
	{
		if (method_exists($this->translator, 'setLocale')) {
			$this->translator->setLocale($language);
		}
	}
}
