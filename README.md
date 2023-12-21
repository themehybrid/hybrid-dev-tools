# Hybrid Dev Tools

Development helper tools.

* PHPCS
* Rector (PHP transpiler)
* PHP Scoper
* Pint
* Psalm
* Parallel lint
* Composer normalize

[PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) coding standards ruleset for the ThemeHybrid projects.

## Installation

### Standalone

```
git clone https://github.com/themehybrid/hybrid-dev-tools
cd hybrid-dev-tools
composer install
```

### Dependency

```
composer config repositories.hybrid-dev-tools vcs https://github.com/themehybrid/hybrid-dev-tools
composer require themehybrid/hybrid-dev-tools:dev-main --dev
```

Create or modify `phpcs.xml` in project root:

```xml
<?xml version="1.0"?>
<ruleset>
    <file>src</file>
    <rule ref="ThemeHybrid"/>
</ruleset>
```

### Phar tools

Then add the script in the composer.json under *"scripts"* with the event names you want to trigger.
For example:

```
...
"scripts": {
    "post-install-cmd": "Hybrid\DevTools\Composer\Actions::downloadTools",
    "post-update-cmd": "Hybrid\DevTools\Composer\Actions::downloadTools"
  },
...
```

See [why?](https://getcomposer.org/doc/articles/scripts.md#what-is-a-script-)
Look [here](https://getcomposer.org/doc/articles/scripts.md#event-names) for more informations about composer events.

## Sample usage

The composer.json scheme has a part "extra" which is used for the script.
Its described [here](https://getcomposer.org/doc/04-schema.md#extra).

In this part you can add your needed phar tools under the key "hybrid-dev-tools".

```
...
"extra": {
    ...
    "hybrid-dev-tools": {  
        "skip-configs": true,
        "overwrite-configs": true,
        "configs-path": "./bin-dev/tools/configs",
        "skip-tools": true,
        "bin-path": "./bin-dev/tools",
        "tools": {
            "composer-normalize.phar": {
                "url": "https://github.com/ergebnis/composer-normalize/releases/download/2.41.1/composer-normalize.phar",
                "ver": "2.41.1"
            }
        }
    }
    ...
  }
...
```

### Run

```
$ composer exec -v phpcs -- -s [<file>] ...
$ composer exec -v phpcbf -- -s [<file>] ...

OR

$ composer exec -v phpcs -- -s [<file>] --standard=ThemeHybrid ...
$ composer exec -v phpcbf -- -s [<file>] --standard=ThemeHybrid ...
```
