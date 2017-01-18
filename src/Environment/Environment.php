<?php

declare(strict_types=1);

namespace SixtyEightPublishers\Application\Environment;

class Environment
{
	/** @var \SixtyEightPublishers\Application\Environment\IProfileStorage  */
	private $profileStorage;

	/**
	 * @param \SixtyEightPublishers\Application\Environment\ProfileContainer            $profileContainer
	 * @param \SixtyEightPublishers\Application\Environment\IEnvironmentDetector        $detector
	 * @param \SixtyEightPublishers\Application\Environment\IProfileStorage             $profileStorage
	 */
	public function __construct(ProfileContainer $profileContainer, IEnvironmentDetector $detector, IProfileStorage $profileStorage)
	{
		$profile = $detector->detect($profileContainer);
		$this->profileStorage = $profileStorage;
		$this->profileStorage->setProfile($profile instanceof Profile ? $profile : $profileContainer->getDefaultProfile());
	}

	/**
	 * @return \SixtyEightPublishers\Application\Environment\ActiveProfile
	 */
	public function getProfile()
	{
		return $this->profileStorage->getProfile();
	}
}
