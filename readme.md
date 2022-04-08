# AssertHtml - PHPUnit Assertions for HTML Content

[![phpunit](https://github.com/ssddanbrown/htmlassert/actions/workflows/phpunit.yml/badge.svg)](https://github.com/ssddanbrown/htmlassert/actions/workflows/phpunit.yml)
[![Latest Stable Version](https://poser.pugx.org/ssddanbrown/asserthtml/v)](https://packagist.org/packages/ssddanbrown/asserthtml)
[![Total Downloads](https://poser.pugx.org/ssddanbrown/asserthtml/downloads)](https://packagist.org/packages/ssddanbrown/asserthtml)

## Introduction

This package provides a class with a range of PHPUnit assertions for testing HTML content.
This is heavily inspired, and uses code from, Laravel's [browser-kit-testing](https://github.com/laravel/browser-kit-testing) package.

## Installation

You can install the package via composer:

```bash
composer require ssddanbrown/asserthtml
```

## Usage

```php
use Ssddanbrown\AssertHtml\HtmlTest;

// Create an HtmlTest instance with html content
$html = new HtmlTest('<p>Hello</p>');

// Make assertions against the instance
$html->assertElementContains('p', 'Hello');
```

## Available Methods

#### Assertion Methods

```php
$html->assertElementExists(string $selector, array $attributes = []): self
$html->assertElementNotExists(string $selector, array $attributes = []): self
$html->assertElementCount(string $selector, int $count): self
$html->assertElementContains(string $selector, string $text): self
$html->assertElementNotContains(string $selector, string $text): self
$html->assertLinkExists(string $url, string $text = null): self
$html->assertLinkNotExists(string $url, string $text = null): self
$html->assertFieldHasValue(string $fieldNameOrId, string $expected): self
$html->assertFieldNotHasValue(string $fieldNameOrId, string $value): self
$html->assertFieldHasSelected(string $fieldNameOrId, string $value): self
$html->assertFieldNotHasSelected(string $fieldNameOrId, string $value): self
$html->assertCheckboxChecked(string $inputNameOrId): self
$html->assertCheckboxNotChecked(string $inputNameOrId): self
```

#### Helper Methods

```php
$html->getOuterHtml(?string $selector = null): string
$html->getInnerHtml(string $selector): string
```

## Laravel Usage

A simple trait is included for easier usage within Laravel project. Use the trait in your base TestCase class, or within specific test class files, and access by passing any `TestResponse`'s to `$this->withHtml($response);`

#### Adding the trait

```php
<?php
...
use Ssddanbrown\AssertHtml\TestsHtml;
...
abstract class TestCase extends BaseTestCase
{
    ...
    use TestsHtml;
    ...
}
```

#### Making assertions

```php
<?php

class HtmlTest extends TestCase
{
    public function test_login_has_header()
    {
        $response = $this->get('/login');
        $this->withHtml($response)->assertElementContains('h1#title', 'Login to my app!');
    }
}
```

## Development

[Psalm](https://psalm.dev/) is included for static analysis. It can be run like so:

```bash
./vendor/bin/psalm
```

[PHPUnit](https://phpunit.de/) is used for testing. It can be run like so:

```bash
./vendor/bin/phpunit
```

## Low Maintenance Project

This is a low maintenance project. The scope of features and support are purposefully kept narrow to my requirements to ensure longer term maintenance is viable. I'm not looking to grow this into a bigger project at all.

Issues and PRs raised for bugs are perfectly fine assuming they don't significantly increase the scope of the project. Please don't open PRs for significant new features.

## License

This project is licensed under the MIT License. See the [license file](https://github.com/ssddanbrown/asserthtml/blob/main/license.md) for more info.

Much of the logic used in this project has been taken from the [laravel/browser-kit-testing](https://github.com/laravel/browser-kit-testing) project so is subject to [their license](https://github.com/laravel/browser-kit-testing/blob/6.x/LICENSE.md) also.