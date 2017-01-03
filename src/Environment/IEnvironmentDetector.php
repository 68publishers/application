<?php

namespace SixtyEightPublishers\Application;

interface IEnvironmentDetector
{
	/**
	 * If method does not return instance of \SixtyEightPublishers\Application\Profile, the default profile will be set automatically.
	 * @param \SixtyEightPublishers\Application\ProfileContainer $profileContainer
	 *
	 * @return mixed|void|Profile
	 */
	public function detect(ProfileContainer $profileContainer);
}
