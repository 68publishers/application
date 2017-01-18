<?php

declare(strict_types=1);

namespace SixtyEightPublishers\Application\Environment;

use Nette\SmartObject;

/**
 * @property-read string    $country
 * @property-read string    $language
 * @property-read string    $currency
 */
class ActiveProfile implements IProfile
{
	use SmartObject;

	/** @var \SixtyEightPublishers\Application\Environment\IProfile  */
	private $profile;

	/** @var \SixtyEightPublishers\Application\Environment\IProfileStorage  */
	private $profileStorage;

	/** @var null|string */
	private $country;

	/** @var null|string */
	private $language;

	/** @var null|string */
	private $currency;

	/**
	 * @param \SixtyEightPublishers\Application\Environment\IProfile            $profile
	 * @param \SixtyEightPublishers\Application\Environment\IProfileStorage     $profileStorage
	 */
	public function __construct(IProfile $profile, IProfileStorage $profileStorage)
	{
		$this->profile = $profile;
		$this->profileStorage = $profileStorage;
		$this->country = $profile->getCountries()[0] ?? NULL;
		$this->language = $profile->getLanguages()[0] ?? NULL;
		$this->currency = $profile->getCurrencies()[0] ?? NULL;
	}

	/**
	 * @return string
	 */
	public function getCountry()
	{
		return $this->country;
	}

	/**
	 * @return string
	 */
	public function getLanguage()
	{
		return $this->language;
	}

	/**
	 * @return string
	 */
	public function getCurrency()
	{
		return $this->currency;
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
			throw new ProfileConfigurationException("Language with code \"{$language}\" is not defined in active profile.");
		}

		$this->language = $language;

		if ($persist) {
			$this->profileStorage->persist();
		}

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
