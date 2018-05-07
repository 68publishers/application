<?php

namespace SixtyEightPublishers\Application\Environment\Translation;

use Kdyby\Translation\IUserLocaleResolver;
use Kdyby\Translation\Translator;
use SixtyEightPublishers\Application\Environment\IProfileStorage;

class ProfileStorageResolver implements IUserLocaleResolver
{
	/** @var \SixtyEightPublishers\Application\Environment\IProfileStorage  */
	private $storage;

	/** @var bool  */
	private $useDefault = FALSE;

	/** @var bool */
	private $lock = FALSE;

	/**
	 * @param \SixtyEightPublishers\Application\Environment\IProfileStorage $storage
	 * @param bool                                                          $useDefault
	 */
	public function __construct(IProfileStorage $storage, bool $useDefault = FALSE)
	{
		$this->storage = $storage;
		$this->useDefault = $useDefault;
	}

	/**
	 * @param \Kdyby\Translation\Translator $translator
	 *
	 * @return string
	 */
	public function resolve(Translator $translator)
	{
		$locale = $this->storage->getProfile()->getLanguage($this->useDefault);
		if (is_null($locale) && !$this->lock) {
			$this->lock = TRUE;
			if (NULL !== ($newLocale = $translator->getLocale())) {
				$this->storage->getProfile()->changeLanguage($newLocale, FALSE);
			}
			$this->lock = FALSE;
		}

		return $locale;
	}
}
