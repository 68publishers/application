<?php

declare(strict_types=1);

namespace SixtyEightPublishers\Application\RemoteAccessManager\Handler;

interface IAccessHandler
{
	public function allow();

	public function deny();
}
