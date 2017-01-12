<?php

declare(strict_types=1);

namespace SixtyEightPublishers\Application\Storage;

use Nette\Http\Session;
use Nette\SmartObject;
use SixtyEightPublishers\Application\ActiveProfile;
use SixtyEightPublishers\Application\IProfile;
use SixtyEightPublishers\Application\IProfileStorage;
use SixtyEightPublishers\Application\ProfileConfigurationException;

class SessionProfileStorage implements IProfileStorage
{
	use SmartObject;

	const SESSION_SECTION = 'SixtyEightPublishers.Application';

	/** @var null|ActiveProfile */
	private $profile;

	/** @var \Nette\Http\SessionSection  */
	private $session;

	/** @var null|callable */
	public $onPersist;

	/** @var null|callable */
	public $onProfileSet;

	/**
	 * @param \Nette\Http\Session $session
	 */
	public function __construct(Session $session)
	{
		$this->session = $session->getSection(self::SESSION_SECTION);
	}


	/***************** interface \SixtyEightPublishers\Application\IProfileStorage *****************/

	/**
	 * {@inheritdoc}
	 */
	public function setProfile(IProfile $profile)
	{
		$this->profile = new ActiveProfile($profile, $this);

		if ($this->session['profileName'] === $this->profile->getName()) {
			try {
				$this->profile->changeCountry($this->session['profileCountry'], FALSE);
			} catch (ProfileConfigurationException $e) {
			}
			try {
				$this->profile->changeLanguage($this->session['profileLanguage'], FALSE);
			} catch (ProfileConfigurationException $e) {
			}
			try {
				$this->profile->changeCurrency($this->session['profileCurrency'], FALSE);
			} catch (ProfileConfigurationException $e) {
			}
		} else {
			$this->session['profileName'] = $this->profile->getName();
		}

		$this->onProfileSet($this->profile);
	}

	/**
	 * @return \SixtyEightPublishers\Application\ActiveProfile
	 */
	public function getProfile() : ActiveProfile
	{
		return $this->profile;
	}

	/**
	 * {@inheritdoc}
	 */
	public function persist()
	{
		$this->onPersist($this->profile);
		$this->session['profileName'] = $this->profile->getName();
		$this->session['profileCountry'] = $this->profile->getCountry();
		$this->session['profileLanguage'] = $this->profile->getLanguage();
		$this->session['profileCurrency'] = $this->profile->getCurrency();
	}
}
