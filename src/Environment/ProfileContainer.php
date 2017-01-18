<?php

declare(strict_types=1);

namespace SixtyEightPublishers\Application\Environment;

/**
 * @internal
 */
class ProfileContainer implements \IteratorAggregate
{
	const DEFAULT_PROFILE_NAME = 'default';

	/** @var null|Profile */
	private $defaultProfile;

	/** @var Profile[] */
	private $profiles = [];

	/**
	 * @param string        $name
	 * @param array         $countries
	 * @param array         $languages
	 * @param array         $currencies
	 * @param array         $domains
	 * @param bool          $isEnabled
	 *
	 * @return void
	 */
	public function addProfile($name, array $countries, array $languages, array $currencies, array $domains, $isEnabled = true)
	{
		$profile = new Profile($name, $countries, $languages, $currencies, $domains);

		if (!$isEnabled) {
			$profile->setEnabled(FALSE);
		}

		if ($name === self::DEFAULT_PROFILE_NAME || !$this->defaultProfile) {
			$this->defaultProfile = $profile;
		}

		$this->profiles[$name] = $profile;
	}

	/**
	 * @return null|\SixtyEightPublishers\Application\Environment\Profile
	 */
	public function getDefaultProfile()
	{
		return $this->defaultProfile;
	}

	/**
	 * @return \SixtyEightPublishers\Application\Environment\Profile[]
	 */
	public function getProfiles()
	{
		return $this->profiles;
	}

	/**
	 * @param string $code
	 *
	 * @return \SixtyEightPublishers\Application\Environment\Profile
	 */
	public function getProfile($code)
	{
		if (!array_key_exists($code, $this->profiles)) {
			throw new NonExistentProfileException("Profile with name \"{$code}\" doesn't exists.");
		}

		return $this->profiles[$code];
	}

	/********************* interface \IteratorAggregate *********************/

	/**
	 * @return \ArrayIterator
	 */
	public function getIterator()
	{
		return new \ArrayIterator($this->profiles);
	}
}
