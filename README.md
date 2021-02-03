[![License](https://img.shields.io/github/license/imponeer/smarty-includeq.svg)](LICENSE)
[![GitHub release](https://img.shields.io/github/release/imponeer/smarty-includeq.svg)](https://github.com/imponeer/smarty-includeq/releases) [![Maintainability](https://api.codeclimate.com/v1/badges/05e38f936681d6b4c462/maintainability)](https://codeclimate.com/github/imponeer/smarty-includeq/maintainability) [![PHP](https://img.shields.io/packagist/php-v/imponeer/smarty-includeq.svg)](http://php.net) 
[![Packagist](https://img.shields.io/packagist/dm/imponeer/smarty-includeq.svg)](https://packagist.org/packages/imponeer/smarty-includeq)

# Smarty IncludeQ

Rewritten (due that original use GPLv2+ license) [Smarty](https://smarty.net) '[include](https://www.smarty.net/docsv2/en/language.function.include.tpl)' variant that was invented for use in [XOOPS](https://xoops.org), but nowadays used in some other PHP based CMS'es (like [ImpressCMS](https://impresscms.org)!).

See, [original version of this smarty plugin in Xoops](https://github.com/XOOPS/XoopsCore25/blob/v2.5.8/htdocs/class/smarty/xoops_plugins/compiler.includeq.php) to see more accurate description why this plugin exists.

## Installation

To install and use this package, we recommend to use [Composer](https://getcomposer.org):

```bash
composer require imponeer/smarty-includeq
```

Otherwise, you need to include manually files from `src/` directory. 

## Registering in Smarty

If you want to use these extensions from this package in your project you need register them with [`registerPlugin` function](https://www.smarty.net/docs/en/api.register.plugin.tpl) from [Smarty](https://www.smarty.net). For example:
```php
$smarty = new \Smarty();
$includeqPlugin = new \Imponeer\Smarty\Extensions\IncludeQ\IncludeQCompiler();
$smarty->registerPlugin('compiler', $includeqPlugin->getName(), [$includeqPlugin, 'execute']);
```

## Using from templates

Example how to use it:
```smarty
  {includeq file="file.tpl"}
```
## How to contribute?

If you want to add some functionality or fix bugs, you can fork, change and create pull request. If you not sure how this works, try [interactive GitHub tutorial](https://try.github.io).

If you found any bug or have some questions, use [issues tab](https://github.com/imponeer/smarty-includeq/issues) and write there your questions.