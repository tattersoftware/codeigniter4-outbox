# Tatter\Outbox
Email toolkit for CodeIgniter 4

[![](https://github.com/tattersoftware/codeigniter4-outbox/workflows/PHP%20Unit%20Tests/badge.svg)](https://github.com/tattersoftware/codeigniter4-outbox/actions?query=workflow%3A%22PHP+Unit+Tests)

## Quick Start

1. Install with Composer: `> composer require --dev tatter/outbox`
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

### Templating

**Outbox** comes with a CodeIgniter-ready version of the
[Responsive HTML Email Template](https://github.com/leemunroe/responsive-html-email-template).
This provides a solid basis for your emails so you can be sure they will display nicely on
any device. You may also write your own templates to use with the rest of the features.

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
