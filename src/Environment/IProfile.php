<?php

namespace SixtyEightPublishers\Application;

interface IProfile
{
	/**
	 * @return string
	 */
	public function getName() : string;

	/**
	 * @return array
	 */
	public function getCountries() : array;

	/**
	 * @return array
	 */
	public function getLanguages() : array;

	/**
	 * @return array
	 */
	public function getCurrencies() : array;

	/**
	 * @return array
	 */
	public function getDomains() : array;
}
