<h1 align="center">Contact plugin for <a href="https://flextype.org/">Flextype</a></h1>

<p align="center">
<a href="https://github.com/flextype-plugins/contact/releases"><img alt="Version" src="https://img.shields.io/github/release/flextype-plugins/contact.svg?label=version&color=black"></a> <a href="https://github.com/flextype-plugins/contact"><img src="https://img.shields.io/badge/license-MIT-blue.svg?color=black" alt="License"></a> <a href="https://github.com/flextype-plugins/contact"><img src="https://img.shields.io/github/downloads/flextype-plugins/contact/total.svg?color=black" alt="Total downloads"></a> <a href="https://github.com/flextype/flextype"><img src="https://img.shields.io/badge/Flextype-0.9.16-green.svg" alt="Flextype"></a> <a href=""><img src="https://img.shields.io/discord/423097982498635778.svg?logo=discord&color=black&label=Discord%20Chat" alt="Discord"></a>
</p>

## Dependencies

The following dependencies need to be downloaded and installed for Contact Plugin.

| Item | Version | Download |
|---|---|---|
| [flextype](https://github.com/flextype/flextype) | 0.9.16 | [download](https://github.com/flextype/flextype/releases) |
| [site](https://github.com/flextype-plugins/site) | >=1.0.0 | [download](https://github.com/flextype-plugins/site/releases) |
| [twig](https://github.com/flextype-plugins/twig) | >=2.0.0 | [download](https://github.com/flextype-plugins/twig/releases) |
| [phpmailer](https://github.com/flextype-plugins/phpmailer) | >=1.0.0 | [download](https://github.com/flextype-plugins/phpmailer/releases) |

## Installation

1. Download & Install all required dependencies.
2. Create new folder `/project/plugins/contact`
3. Download PHP Mailer Plugin and unzip plugin content to the folder `/project/plugins/contact`
4. Copy `_project` folder content into your `project` folder.

## Documentation

### Settings

| Key | Value | Description |
|---|---|---|
| enabled | true | true or false to disable the plugin |
| priority | 80 | contact plugin priority |
| default_subject | Default subject | Default subject for email |
| message_success | Email sended. | Message to show after email send. |
| from.name | Flextype | From name |
| from.email | sergey.romanenko@flextype.org | From email |
| to.name | Flextype | Receiver name. |
| to.email | sergey.romanenko@flextype.org | Receiver email |

### Create your own contact form

here is an example of simple contact form:

```yaml
title: Contact
default_field: title
icon:
  name: envelope
  set: 'fontawesome|solid'
size: 6/12
hide: true
form:
  action: 'contact.contactProcess'
  buttons:
    submit:
      type: submit
      title: 'Send'
  fields:
    mailbox:
      type: hidden
      default: test
    subject:
      title: 'Subject'
      type: text
      size: 6/12
      validation:
        required: true
    email:
      title: 'Email'
      type: email
      size: 6/12
      validation:
        required: true
    message:
      title: 'Message'
      type: textarea
      size: 12
      validation:
        required: true
```

Read documentation about creating forms here:  
https://github.com/flextype-plugins/form

### Usage in the PHP

```php
// Show success message
if (isset(flextype('flash')->getMessages()['success']) and count(flextype('flash')->getMessages()['success']) > 0)
    foreach (flextype('flash')->getMessages()['success'] as $message) {
        echo $message;
    }
}

// Render contact form
echo flextype('form')
        ->render(flextype('serializers')
                    ->yaml()
                    ->decode(filesystem()
                                ->file(PATH['project'] . '/fieldsets/contact.yaml')
                                ->get()), []);
```

### Usage in the TWIG templates

```twig
{# Show success message #}
{% for message in flextype.flash.getMessages()['success'] %}
    {{ message }}
{% endfor %}

{# Render contact form #}
{{ flextype.form.render(flextype.serializers.yaml.decode(filesystem().file(PATH_PROJECT ~ '/fieldsets/contact.yaml').get()), {})|raw }}
```

## LICENSE
[The MIT License (MIT)](https://github.com/flextype-plugins/contact/blob/master/LICENSE.txt)
Copyright (c) 2021 [Sergey Romanenko](https://github.com/Awilum)
