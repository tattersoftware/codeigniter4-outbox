# Tatter\Outbox
Email toolkit for CodeIgniter 4

[![](https://github.com/tattersoftware/codeigniter4-outbox/workflows/PHPUnit/badge.svg)](https://github.com/tattersoftware/codeigniter4-outbox/actions?query=workflow%3A%22PHPUnit)
[![](https://github.com/tattersoftware/codeigniter4-outbox/workflows/PHPStan/badge.svg)](https://github.com/tattersoftware/codeigniter4-outbox/actions?query=workflow%3A%22PHPStan)

## Quick Start

1. Install with Composer: `> composer require tatter/outbox`
2. Migrate the database: `> php spark migrate -all`

## Description

**Outbox** supplies a handful of useful tools to supplement the framework's native `Email`
class.

## Configuration (optional)

The library's default behavior can be altered by extending its config file. Copy
**examples/Outbox.php** to **app/Config/** and follow the instructions
in the comments. If no config file is found in **app/Config** then the library will use its own.

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
Simply supply your display data to `inline()`, along with the optional template and CSS view
paths:
```
use Tatter\Outbox\Outbox;

...

$data = [
	'title'       => 'First Email Ever',
	'preview'     => 'Please do not think this is spam!',
	'main'        => 'Hey there! Good of you to sign up.<br /> We would like to offer you...',
	'contact'     => 'support@example.com',
	'unsubscribe' => '<a href="https://example.com/unsubscribe">Unsubscribe</a>',	
];
$message = Outbox::inline($data, 'EmailTemplates/MarketCampaign');
$this->email
	->setMessage($message)
	->setMailType('html')
	->send();
```

### Tokenizing

Sometimes instead of sending mail internally your application will work with an external
email service API. **Outbox** provides a way of tokenizing your templates so they will work
with popular merge-style services. Simply set your configuration details and then supply the
list of variables to tokenize:

	$template = Outbox::tokenize(['title', 'main', 'unsubscribe']);

**Outbox** will return your template with the tokenize values ready for submission.

## Templating

**Outbox** comes with a CodeIgniter-ready version of the
[Responsive HTML Email Template](https://github.com/leemunroe/responsive-html-email-template).
This provides a solid basis for your emails so you can be sure they will display nicely on
any device.

You may also write your own templates to use with the rest of the features. **Outbox** provides
migrations and an Entity, a Model, Views, and a Controller for managing email templates in your
database. To enable the Controller you will need to add the following routes to **app/Config/Routes.php**:
```
// Routes to Email Templates
$routes->group('emails', ['namespace' => '\Tatter\Outbox\Controllers'], function ($routes)
{
	$routes->get('templates/new/(:segment)', 'Templates::new/$1');
	$routes->get('templates/send/(:segment)', 'Templates::send/$1');
	$routes->post('templates/send/(:segment)', 'Templates::send_commit/$1');
	$routes->presenter('templates', ['controller' => 'Templates']);
});
```

Of course you may provide your own interfaces as well, and should probably secure access to
these routes with a Filter either way.

Templates use predefined "tokens" that will be passed through COdeIgniter's Parser to add
your data. The `Template` Entity can do this for you with the `render($data = [])` method
to get back a ready-to-go HTML email string:
```
$template = model(TemplateModel::class)->where('name', 'Newsletter')->first();
$email    = service('Email');

$email->setBody($template->render(['title' => 'Pumpkins are here!']));
$email->send();
```

If you want to take advantage of `Outbox`'s style inlining you can get a fully prepared
version of the `Email` class with rendered and inlined content from the library:
```
$email = Outbox::fromTemplate($template);
$email->setTo('jill@example.com')->send();
```

### Cascading Templates

Each `Template` may also be entered with a "Parent Template". Parent templates need to have
a `{body}` token which will receive the parsed content from its child. Additional tokens
in the parent template can be entered by defining them in the child.

Cascading templates makes it easy to have a few "layouts" with many different variable
messages for each layout. For example, your app may send both newsletters and receipts
with their own layout (Parent Template) and then a myriad of different custom content
for different types of users.
