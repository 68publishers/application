<?php

namespace SixtyEightPublishers\Application;


/**
 * @internal
 */
class Profile
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
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return array
	 */
	public function getCountries()
	{
		return $this->countries;
	}

	/**
	 * @return array
	 */
	public function getLanguages()
	{
		return $this->languages;
	}

	/**
	 * @return array
	 */
	public function getCurrencies()
	{
		return $this->currencies;
	}

	/**
	 * @return array
	 */
	public function getDomains()
	{
		return $this->domains;
	}

	/**
	 * @return bool
	 */
	public function isEnabled()
	{
		return $this->enabled;
	}

}