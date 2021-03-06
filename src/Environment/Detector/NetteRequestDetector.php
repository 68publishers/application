<?php

declare(strict_types=1);

namespace SixtyEightPublishers\Application\Environment\Detector;

use Nette\Http\IRequest;
use Nette\Utils\Strings;
use SixtyEightPublishers\Application\Environment\IEnvironmentDetector;
use SixtyEightPublishers\Application\Environment\Profile;
use SixtyEightPublishers\Application\Environment\ProfileContainer;

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
		foreach ($profileContainer as $profile) {
			if ($profile->isEnabled()) {
				foreach ($profile->getDomains() as $domain) {
					if ((Strings::contains($domain, '*') && $this->match($domain, $url)) || ($domain === $url)) {
						return $profile;
					}
				}
			}
		}

		return NULL;
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
		foreach (explode('*', $domain) as $e) {
			if (empty($e)) {
				continue;
			}

			if (strpos($url, $e) === FALSE) {
				return FALSE;
			}

			$url = str_replace($e, '@', $url);
		}

		$params = [];
		foreach (explode('@', $url) as $t) {
			if (!empty($t)) {
				$params[] = $t;
			}
		}

		return ($count === count($params));
	}
}
