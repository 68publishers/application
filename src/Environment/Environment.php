<?php

namespace SixtyEightPublishers\Application;


class Environment
{
	/** @var null|Profile */
	private $profile;

	/**
	 * @param \SixtyEightPublishers\Application\ProfileContainer            $profileContainer
	 * @param \SixtyEightPublishers\Application\IEnvironmentDetector        $detector
	 */
	public function __construct(ProfileContainer $profileContainer, IEnvironmentDetector $detector)
	{
		$profile = $detector->detect($profileContainer);
		$this->profile = $profile instanceof Profile ? $profile : $profileContainer->getDefaultProfile();
	}

	/**
	 * @return \SixtyEightPublishers\Application\Profile
	 */
	public function getProfile()
	{
		return $this->profile;
	}

}
