<?php

declare(strict_types=1);

namespace SixtyEightPublishers\Application\Environment;

interface IEnvironmentDetector
{
	/**
	 * If method does not return instance of \SixtyEightPublishers\Application\Environment\Profile, the default profile will be set automatically.
	 * @param \SixtyEightPublishers\Application\Environment\ProfileContainer $profileContainer
	 *
	 * @return mixed|void|Profile
	 */
	public function detect(ProfileContainer $profileContainer);
}
