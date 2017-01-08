<?php

namespace SixtyEightPublishers\Application;

class Environment
{
	/** @var \SixtyEightPublishers\Application\IProfileStorage  */
	private $profileStorage;

	/**
	 * @param \SixtyEightPublishers\Application\ProfileContainer            $profileContainer
	 * @param \SixtyEightPublishers\Application\IEnvironmentDetector        $detector
	 * @param \SixtyEightPublishers\Application\IProfileStorage             $profileStorage
	 */
	public function __construct(ProfileContainer $profileContainer, IEnvironmentDetector $detector, IProfileStorage $profileStorage)
	{
		$profile = $detector->detect($profileContainer);
		$this->profileStorage = $profileStorage;
		$this->profileStorage->setProfile($profile instanceof Profile ? $profile : $profileContainer->getDefaultProfile());
	}

	/**
	 * @return \SixtyEightPublishers\Application\ActiveProfile
	 */
	public function getProfile()
	{
		return $this->profileStorage->getProfile();
	}
}
