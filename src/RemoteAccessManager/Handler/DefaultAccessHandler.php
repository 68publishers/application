<?php

declare(strict_types=1);

namespace SixtyEightPublishers\Application\RemoteAccessManager\Handler;

use Nette\Application\BadRequestException;

class DefaultAccessHandler implements IAccessHandler
{
	public function allow()
	{
	}

	public function deny()
	{
		throw new BadRequestException('Access denied!');
	}
}
