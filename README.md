# Application

[![Build Status][ico-travis]][link-travis]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Total Downloads][ico-downloads]][link-downloads]
[![Latest Version on Packagist][ico-version]][link-packagist]

This package helps you to deal with regions with different languages, currencies and countries. It could be helpful even if you have single region project.

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
            language: [sk_SK, en_GB, de_DE, pl_PL]
            currency: [EUR, PLZ, GBP]
            country: [SK, GB, DE, PL]
        north_america:
            language: en_US
            currency: USD
            country: US
            domain: [www.example.com, example.com]
            # disable: yes
        default: # If the default profile doesn't exists, the first profile is taken as default
            language: cs_CZ
            currency: CZK
            country: CZ
```

### Bar panel

Enables and disables Tracy debugger bar panel for better debugging

```yml
environment:
    debugger: yes
```

![](https://68publishers.github.io/repo/environment/tracy-panel.png)

### Integration with Kdyby\Translation

This feature provides automatic evaluation of the locale parameter for `Kdyby\Translation` based on profile settings in the extension. 
Default profile's language can be used if setting `translations.useDefault` is set to `TRUE`.
If is this setting set to `FALSE` default language will not be used and other resolvers will be invoked.
Also if you change language via method `\SixtyEightPublishers\Application\Environment\ActiveProfile::changeLanguage()`, locale in Translator is changed too.

```yml
environment:
    translations:
        enable: yes
        useDefault: no
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
