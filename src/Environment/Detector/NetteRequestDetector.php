<?php

namespace SixtyEightPublishers\Application\Detector;

use Nette\Http\IRequest;
use Nette\Utils\Strings;
use SixtyEightPublishers\Application\IEnvironmentDetector;
use SixtyEightPublishers\Application\Profile;
use SixtyEightPublishers\Application\ProfileContainer;


class NetteRequestDetector implements IEnvironmentDetector
{
	/** @var \Nette\Http\IRequest  */
	private $request;

	/**
	 * @param \Nette\Http\IRequest $request
	 */
	public function __construct(IRequest $request)
	{
		$this->request = $request;
	}

	/**
	 * {@inheritdoc}
	 */
	public function detect(ProfileContainer $profileContainer)
	{
		$url = $this->request->getUrl()->getHost();

		/** @var Profile $profile */
		foreach ($profileContainer as $profile)
			foreach ($profile->getDomains() as $domain)
				if ((Strings::contains($domain, '*') && $this->match($domain, $url)) || ($domain === $url))
					return $profile;

		return null;
	}

	/**
	 * @param string    $domain
	 * @param string    $url
	 *
	 * @return bool
	 */
	private function match($domain, $url)
	{
		$count = substr_count($domain, '*');
		foreach (explode('*', $domain) as $e)
		{
			if (empty($e))
				continue;

			if (strpos($url, $e) === false)
				return false;
			$url = str_replace($e, '@', $url);
		}

		$params = [];
		foreach (explode('@', $url) as $t)
		{
			if (!empty($t))
				$params[] = $t;
		}

		return ($count === count($params));
	}

}
