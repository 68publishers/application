<?php

declare(strict_types = 1);

namespace SixtyEightPublishers\Application;

/**
 * @internal
 */
class Profile implements IProfile
{
	/** @var string  */
	private $name;

	/** @var array  */
	private $countries;

	/** @var array  */
	private $languages;

	/** @var array  */
	private $currencies;

	/** @var array  */
	private $domains;

	/** @var bool  */
	private $enabled = TRUE;

	/**
	 * @param string        $name
	 * @param array         $countries
	 * @param array         $languages
	 * @param array         $currencies
	 * @param array         $domains
	 */
	public function __construct($name, array $countries, array $languages, array $currencies, array $domains)
	{
		$this->name = $name;
		$this->countries = $countries;
		$this->languages = $languages;
		$this->currencies = $currencies;
		$this->domains = $domains;
	}

	/**
	 * @param bool $enabled
	 *
	 * @return void
	 */
	public function setEnabled($enabled = true)
	{
		$this->enabled = $enabled;
	}

	/**
	 * @return bool
	 */
	public function isEnabled()
	{
		return $this->enabled;
	}


	/***************** interface \SixtyEightPublishers\Application\IProfile *****************/


	/**
	 * @return string
	 */
	public function getName() : string
	{
		return $this->name;
	}

	/**
	 * @return array
	 */
	public function getCountries() : array
	{
		return $this->countries;
	}

	/**
	 * @return array
	 */
	public function getLanguages() : array
	{
		return $this->languages;
	}

	/**
	 * @return array
	 */
	public function getCurrencies() : array
	{
		return $this->currencies;
	}

	/**
	 * @return array
	 */
	public function getDomains() : array
	{
		return $this->domains;
	}
}
