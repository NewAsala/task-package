# Contact Us From package

[![watchers](https://img.shields.io/github/watchers/NewAsala/task-package?style=flat-square)](https://github.com/NewAsala/task-package/watchers)

[![GitHub stars](https://img.shields.io/github/stars/NewAsala/task-package)](https://github.com/NewAsala/task-package/stargazers)

## this will linked between two projects

## Steps to Installing Bridage

Bridage is a PHP package that linked easy with TMS_Modules.

- The first thing, you must add TMS_Modules to your project
- Put the Module config in app/config:

```bash
composer require nwidart/laravel-modules
```
    then you must publish his provider:

```bash
php artisan vendor:publish --provider="Nwidart\Modules\LaravelModulesServiceProvider"
```
to be able use this Module in package you must add to `composer.json` :

```php
"autoload": {
        "psr-4": {
            "Modules\\": "Modules/",
        }
    }
```
and save this edit in this command:

```bash
composer dump-autoload
```

- Make-sure the php version in your project is conform to this package, `like table migration` : 

```bash
$table->id(); Or $table->bigIncrements('id'); Or anything like this
```

- You must publish javaScript folder from package in this command after that select `public` tag:

```bash
php artisan vendor:publish
```

- you must add mail config to `.env` file somthing like this:
```bash
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=yourUsername
MAIL_PASSWORD=yourPassword
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your email
```
- add to the kernel:
```bash
    protected $commands = [
        \\Akrad\\Console\\Commands\\ObserversCommand::class,
    ];
```

## Installing Bridage

The Package command is:

```bash
composer require akrad/bridage
```