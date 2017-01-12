<?php

declare(strict_types = 1);

namespace SixtyEightPublishers\Tests\Application;

use Tester\Assert;
use Tester\TestCase;
use SixtyEightPublishers\Application as SEApplication;

require __DIR__ . '/../bootstrap.php';

class ActiveProfile extends TestCase
{
	/** @var SEApplication\ActiveProfile */
	private $activeProfile;

	public function setUp()
	{
		$profile = new SEApplication\Profile('czech', ['CZ', 'SK'], ['cs_CZ', 'sk_SK'], ['CZK', 'EUR'], ['profile.local']);
		$this->activeProfile = new SEApplication\ActiveProfile($profile, new TestProfileStorage());
	}

	public function testBase()
	{
		Assert::same('czech', $this->activeProfile->getName());
		Assert::same(['CZ', 'SK'], $this->activeProfile->getCountries());
		Assert::same(['cs_CZ', 'sk_SK'], $this->activeProfile->getLanguages());
		Assert::same(['CZK', 'EUR'], $this->activeProfile->getCurrencies());
		Assert::same(['profile.local'], $this->activeProfile->getDomains());

		Assert::same('CZ', $this->activeProfile->getCountry());
		Assert::same('cs_CZ', $this->activeProfile->getLanguage());
		Assert::same('CZK', $this->activeProfile->getCurrency());
	}

	public function testChanges()
	{
		$this->activeProfile->changeCountry('SK');
		$this->activeProfile->changeLanguage('sk_SK');
		$this->activeProfile->changeCurrency('EUR');

		Assert::same('SK', $this->activeProfile->getCountry());
		Assert::same('sk_SK', $this->activeProfile->getLanguage());
		Assert::same('EUR', $this->activeProfile->getCurrency());
	}

	/**
	 * @param string $method
	 * @param string $value
	 *
	 * @dataProvider getInvalidChangeData
	 * @throws \SixtyEightPublishers\Application\ProfileConfigurationException
	 */
	public function testInvalidChange($method, $value)
	{
		$this->activeProfile->$method($value);
	}

	public function getInvalidChangeData()
	{
		return [
			['changeCountry', 'PL'],
			['changeLanguage', 'pl_PL'],
			['changeCurrency', 'PLN'],
		];
	}
}

class TestProfileStorage implements SEApplication\IProfileStorage
{
	public function setProfile(SEApplication\IProfile $profile)
	{
	}

	public function getProfile() : SEApplication\ActiveProfile
	{
	}

	public function persist()
	{
	}
}

(new ActiveProfile())->run();
