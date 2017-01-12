<?php

declare(strict_types=1);

namespace SixtyEightPublishers\Application;

interface IProfileStorage
{
	/**
	 * @param \SixtyEightPublishers\Application\IProfile $profile
	 *
	 * @return mixed|void
	 */
	public function setProfile(IProfile $profile);

	/**
	 * @return \SixtyEightPublishers\Application\ActiveProfile
	 */
	public function getProfile() : ActiveProfile;

	/**
	 * Saves actual ActiveProfile's state
	 * @return mixed|void
	 */
	public function persist();
}
