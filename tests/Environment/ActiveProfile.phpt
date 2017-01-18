<?php

declare(strict_types=1);

namespace SixtyEightPublishers\Tests\Application\Environment;

use Tester\Assert;
use Tester\TestCase;
use SixtyEightPublishers\Application\Environment as SEEnvironment;

require __DIR__ . '/../bootstrap.php';

class ActiveProfile extends TestCase
{
	/** @var SEEnvironment\ActiveProfile */
	private $activeProfile;

	public function setUp()
	{
		$profile = new SEEnvironment\Profile('czech', ['CZ', 'SK'], ['cs_CZ', 'sk_SK'], ['CZK', 'EUR'], ['profile.local']);
		$this->activeProfile = new SEEnvironment\ActiveProfile($profile, new TestProfileStorage());
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
	 * @throws \SixtyEightPublishers\Application\Environment\ProfileConfigurationException
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

class TestProfileStorage implements SEEnvironment\IProfileStorage
{
	public function setProfile(SEEnvironment\IProfile $profile)
	{
	}

	public function getProfile() : SEEnvironment\ActiveProfile
	{
	}

	public function persist()
	{
	}
}

(new ActiveProfile())->run();
