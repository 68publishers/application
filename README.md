# Application

[![Build Status][ico-travis]][link-travis]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Total Downloads][ico-downloads]][link-downloads]
[![Latest Version on Packagist][ico-version]][link-packagist]

This is where your description will be. Limit it to 2 paragraphs tops.

## Installation

The best way to install 68publishers/application is using Composer:

```bash
composer require 68publishers/application
```

and now you can enable the Environment extension using your neon config

```yml
extensions:
	environment: SixtyEightPublishers\Application\DI\EnvironmentExtension
```

## Usage

```php
environment:
	profile:
		europe:
			language: [cs_CZ, sk_SK, en_GB, de_DE, pl_PL]
			currency: [CZK, EUR, PLZ, GBP]
			country: [CZ, SK, GB, DE, PL]
		north_america:
			language: en_US
			currency: USD
			country: US
			domain: [www.example.com, example.com]
			# disable: yes
```

### Bar panel

Enables and disables Tracy debugger bar panel

```yml
environment:
	debugger: yes
```

## Rules for contributing

- **1 PR per feature**
- PR with tests are more likely to be merged
- **tests and coding standard must pass**

```bash
vendor/bin/tester ./tests -s
vendor/bin/php-cs-fixer fix --config=.php_cs.dist -v --dry-run
```

[ico-version]: https://img.shields.io/packagist/v/68publishers/application.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/68publishers/application/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/68publishers/application.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/68publishers/application.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/68publishers/application.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/68publishers/application
[link-travis]: https://travis-ci.org/68publishers/application
[link-scrutinizer]: https://scrutinizer-ci.com/g/68publishers/application/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/68publishers/application
[link-downloads]: https://packagist.org/packages/68publishers/application
