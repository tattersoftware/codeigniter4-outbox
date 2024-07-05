# Tatter\Outbox
Email toolkit for CodeIgniter 4

[![](https://github.com/tattersoftware/codeigniter4-outbox/workflows/PHPUnit/badge.svg)](https://github.com/tattersoftware/codeigniter4-outbox/actions/workflows/phpunit.yml)
[![](https://github.com/tattersoftware/codeigniter4-outbox/workflows/PHPStan/badge.svg)](https://github.com/tattersoftware/codeigniter4-outbox/actions/workflows/phpstan.yml)
[![](https://github.com/tattersoftware/codeigniter4-outbox/workflows/Deptrac/badge.svg)](https://github.com/tattersoftware/codeigniter4-outbox/actions/workflows/deptrac.yml)
[![Coverage Status](https://coveralls.io/repos/github/tattersoftware/codeigniter4-outbox/badge.svg?branch=develop)](https://coveralls.io/github/tattersoftware/codeigniter4-outbox?branch=develop)

## Quick Start

1. Install with Composer: `> composer require tatter/outbox`
2. Prepare the database: `> php spark migrate -all && php spark db:seed "Tatter\Outbox\Database\Seeds\TemplateSeeder"`
3. Send beautiful, dynamic email:
```php
model(TemplateModel::class)->findByName('Default')
	->email([
		'item' => 'Fancy Purse',
		'cost' => '10 dollars',
		'url'  => site_url('items/show/' . $itemId),
	])
	->setTo($user->email)
	->send();
```

## Features

**Outbox** supplies useful tools to supplement the framework's native `Email` class:
logging, style inlining, and templating.

## Installation

Install easily via Composer to take advantage of CodeIgniter 4's autoloading capabilities
and always be up-to-date:
```shell
composer require tatter/outbox
```

Or, install manually by downloading the source files and adding the directory to
**app/Config/Autoload.php**.

## Configuration (optional)

The library's default behavior can be altered by extending its config file. You can create config file using this command
```shell
php spark outbox:publish
```
Or, manually by copy **examples/Outbox.php** to **app/Config/** and follow the instructions
in the comments. If no config file is found in **app/Config** then the library will use its own.

If you plan to use the Template Routes (see below) you might also want to configure
[Tatter\Layouts](https://github.com/tattersoftware/codeigniter4-layouts) to ensure the
views are displayed properly for your app.

## Usage

### Logging

By default **Outbox** will log any successfully sent emails in the database. This provides
a handy paper-trail for applications that send a variety of status and communication
messages to users. Use the `Tatter\Outbox\Models\EmailModel` and its corresponding entity
to view email logs.

### Inlining

Sending HTML email can be tricky, as support for HTML and CSS vary across displays and devices.
**Outbox** includes `CssToInlineStyles`, a module by *tijsverkoyen* to take any CSS and
inject it inline into an email template for maximum compatibility. This allows you to reuse
site stylesheets or write your own from scratch and use them across any of your templates.
Use the default styles from
[Responsive HTML Email Template](https://github.com/leemunroe/responsive-html-email-template),
supply your own as string parameters, or create a View file and add it to the configuration.

## Templating

**Outbox** comes with a default template, a modified-for-CodeIgniter version of the
[Responsive HTML Email Template](https://github.com/leemunroe/responsive-html-email-template).
This provides a solid basis for your emails so you can be sure they will display nicely on
any device. Run the Template Seeder to begin using this as the default:
```shell
	php spark db:seed "Tatter\Outbox\Database\Seeds\TemplateSeeder"
```

You may also write your own templates and seed them or use the provided MVC bundle for
managing email templates in your database. To enable the Controller you will need to
toggle `$routeTemplates` in the configuration, or add the following routes to **app/Config/Routes.php**:

```php
// Routes to Email Templates
$routes->group('emails', ['namespace' => '\Tatter\Outbox\Controllers'], function ($routes)
{
	$routes->get('templates/new/(:segment)', 'Templates::new/$1');
	$routes->get('templates/send/(:segment)', 'Templates::send/$1');
	$routes->post('templates/send/(:segment)', 'Templates::send_commit/$1');
	$routes->presenter('templates', ['controller' => 'Templates']);
});
```

Be sure to secure appropriate access to these routes (e.g. with a Filter).

### Tokens

Templates use View Parser "tokens" that will be passed through to add your data.
The `Template` Entity can do this for you by passing in your data parameters:

```php
$template = model(TemplateModel::class)->findByName('Item Purchase');

$subject = $template->renderSubject(['item' => 'Fancy Purse']);
$body    = $template->renderBody(['cost' => '10 dollars']);
```

`renderBody()` will take care of inlining any CSS you have provided and including your
template in its parent (if defined).

If you do not need any other configuration you can get a fully prepared
version of the `Email` class with rendered and inlined content from the library:
```php
$email = $template->email($data);
$email->setTo('jill@example.com')->send();
```

### Cascading Templates

Each `Template` may also be created with a "Parent Template". Parent templates need to have
a `{body}` token which will receive the parsed content from its child. Additional tokens
in the parent template can be entered by defining them in the child.

Cascading templates makes it easy to have a few "layouts" with many different variable
messages for each layout. For example, your app may send both newsletters and receipts
with their own layout (Parent Template) and then a myriad of different customizable
messages for different occasions.
