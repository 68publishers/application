<?php

declare(strict_types = 1);

namespace SixtyEightPublishers\Tests\Application;

use Tester\Assert;
use Tester\TestCase;
use SixtyEightPublishers\Application as SEApplication;

require __DIR__ . '/../bootstrap.php';

/**
 * @testCase
 */
class Profile extends TestCase
{
	public function testProfile()
	{
		$profile = new SEApplication\Profile('north_america', ['US'], ['en_US'], ['USD'], ['profile.local']);
		$profile->setEnabled(TRUE);

		Assert::same('north_america', $profile->getName());
		Assert::same(['US'], $profile->getCountries());
		Assert::same(['en_US'], $profile->getLanguages());
		Assert::same(['USD'], $profile->getCurrencies());
		Assert::same(['profile.local'], $profile->getDomains());
		Assert::true($profile->isEnabled(), 'Profile is enabled');

		$profile->setEnabled(FALSE);
		Assert::false($profile->isEnabled(), 'Profile is disabled');
	}
}

(new Profile())->run();
