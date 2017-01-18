<?php

declare(strict_types=1);

namespace SixtyEightPublishers\Application\Environment;

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
