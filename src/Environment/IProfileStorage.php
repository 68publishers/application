<?php

declare(strict_types=1);

namespace SixtyEightPublishers\Application\Environment;

interface IProfileStorage
{
	/**
	 * @param \SixtyEightPublishers\Application\Environment\IProfile $profile
	 *
	 * @return mixed|void
	 */
	public function setProfile(IProfile $profile);

	/**
	 * @return \SixtyEightPublishers\Application\Environment\ActiveProfile
	 */
	public function getProfile() : ActiveProfile;

	/**
	 * Saves actual ActiveProfile's state
	 * @return mixed|void
	 */
	public function persist();
}
