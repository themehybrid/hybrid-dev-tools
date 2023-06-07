# Hybrid Dev Tools
Development helper tools.

* PHPCS
* Rector (PHP transpiler)

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

### Run

```
$ composer exec -v phpcs -- -s [<file>] ...
$ composer exec -v phpcbf -- -s [<file>] ...

OR

$ composer exec -v phpcs -- -s [<file>] --standard=ThemeHybrid ...
$ composer exec -v phpcbf -- -s [<file>] --standard=ThemeHybrid ...
```
