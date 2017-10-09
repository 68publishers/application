<?php

declare(strict_types=1);

namespace SixtyEightPublishers\Application\Environment;

use Nette\SmartObject;

/**
 * @property-read NULL|string    $country
 * @property-read NULL|string    $language
 * @property-read NULL|string    $currency
 * @property-read NULL|string    $defaultCountry
 * @property-read NULL|string    $defaultLanguage
 * @property-read NULL|string    $defaultCurrency
 *
 * @method void onChangeLanguage(string $language)
 */
class ActiveProfile implements IProfile
{
	use SmartObject;

	/** @var \SixtyEightPublishers\Application\Environment\IProfile  */
	private $profile;

	/** @var \SixtyEightPublishers\Application\Environment\IProfileStorage  */
	private $profileStorage;

	/** @var NULL|string */
	private $country;

	/** @var NULL|string */
	private $language;

	/** @var NULL|string */
	private $currency;

	/** @var NULL|string */
	private $defaultCountry;

	/** @var NULL|string */
	private $defaultLanguage;

	/** @var NULL|string */
	private $defaultCurrency;

	/** @var NULL|callable[] */
	public $onChangeLanguage;

	/**
	 * @param \SixtyEightPublishers\Application\Environment\IProfile            $profile
	 * @param \SixtyEightPublishers\Application\Environment\IProfileStorage     $profileStorage
	 */
	public function __construct(IProfile $profile, IProfileStorage $profileStorage)
	{
		$this->profile = $profile;
		$this->profileStorage = $profileStorage;
		$this->defaultCountry = $profile->getCountries()[0] ?? NULL;
		$this->defaultLanguage = $profile->getLanguages()[0] ?? NULL;
		$this->defaultCurrency = $profile->getCurrencies()[0] ?? NULL;
	}

	/**
	 * @param bool $useDefault
	 *
	 * @return NULL|string
	 */
	public function getCountry(bool $useDefault = TRUE) : ?string
	{
		return $useDefault ? ($this->country ?? $this->defaultCountry) : $this->country;
	}

	/**
	 * @param bool $useDefault
	 *
	 * @return NULL|string
	 */
	public function getLanguage(bool $useDefault = TRUE) : ?string
	{
		return $useDefault ? ($this->language ?? $this->defaultLanguage) : $this->language;
	}

	/**
	 * @param bool $useDefault
	 *
	 * @return NULL|string
	 */
	public function getCurrency(bool $useDefault = TRUE) : ?string
	{
		return $useDefault ? ($this->currency ?? $this->defaultCurrency) : $this->currency;
	}

	/**
	 * @return NULL|string
	 */
	public function getDefaultCountry() : ?string
	{
		return $this->defaultCountry;
	}

	/**
	 * @return NULL|string
	 */
	public function getDefaultLanguage() : ?string
	{
		return $this->defaultLanguage;
	}

	/**
	 * @return NULL|string
	 */
	public function getDefaultCurrency() : ?string
	{
		return $this->defaultCurrency;
	}

	/**
	 * @param string        $country
	 * @param bool          $persist
	 *
	 * @return $this
	 */
	public function changeCountry($country, $persist = TRUE)
	{
		if (!in_array($country, $this->getCountries())) {
			throw new ProfileConfigurationException("Country with code \"{$country}\" is not defined in active profile.");
		}

		$this->country = $country;

		if ($persist) {
			$this->profileStorage->persist();
		}

		return $this;
	}

	/**
	 * @param string        $language
	 * @param bool          $persist
	 *
	 * @return $this
	 */
	public function changeLanguage($language, $persist = TRUE)
	{
		if (!in_array($language, $this->getLanguages())) {
			if (is_string($language)) {
				foreach ($this->getLanguages() as $available) {
					if (substr($available, 0, 2) === substr($language, 0, 2)) {
						return $this->changeLanguage($available, $persist);
					}
				}
			}

			throw new ProfileConfigurationException("Language with code \"{$language}\" is not defined in active profile.");
		}

		$this->language = $language;

		if ($persist) {
			$this->profileStorage->persist();
		}
		$this->onChangeLanguage($language);

		return $this;
	}

	/**
	 * @param string        $currency
	 * @param bool          $persist
	 *
	 * @return $this
	 */
	public function changeCurrency($currency, $persist = TRUE)
	{
		if (!in_array($currency, $this->getCurrencies())) {
			throw new ProfileConfigurationException("Currency with code \"{$currency}\" is not defined in active profile.");
		}

		$this->currency = $currency;

		if ($persist) {
			$this->profileStorage->persist();
		}

		return $this;
	}

	/***************** interface \SixtyEightPublishers\Application\Environment\IProfile *****************/

	/**
	 * {@inheritdoc}
	 */
	public function getName() : string
	{
		return $this->profile->getName();
	}

	/**
	 * {@inheritdoc}
	 */
	public function getCountries() : array
	{
		return $this->profile->getCountries();
	}

	/**
	 * {@inheritdoc}
	 */
	public function getLanguages() : array
	{
		return $this->profile->getLanguages();
	}

	/**
	 * {@inheritdoc}
	 */
	public function getCurrencies() : array
	{
		return $this->profile->getCurrencies();
	}

	/**
	 * {@inheritdoc}
	 */
	public function getDomains() : array
	{
		return $this->profile->getDomains();
	}
}
