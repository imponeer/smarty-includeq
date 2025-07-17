[![License](https://img.shields.io/github/license/imponeer/smarty-includeq.svg)](LICENSE) [![GitHub release](https://img.shields.io/github/release/imponeer/smarty-includeq.svg)](https://github.com/imponeer/smarty-includeq/releases) [![PHP](https://img.shields.io/packagist/php-v/imponeer/smarty-includeq.svg)](http://php.net) [![Packagist](https://img.shields.io/packagist/dm/imponeer/smarty-includeq.svg)](https://packagist.org/packages/imponeer/smarty-includeq) [![Smarty version requirement](https://img.shields.io/packagist/dependency-v/imponeer/smarty-includeq/smarty%2Fsmarty)](https://smarty-php.github.io)

# Smarty IncludeQ

This library provides a rewritten version of the Smarty `{include}` tag variant originally developed for [XOOPS](https://xoops.org) CMS. The `{includeq}` tag offers enhanced template inclusion capabilities and is now used across various PHP-based content management systems, including [ImpressCMS](https://impresscms.org).

This implementation was created as a clean-room rewrite to avoid GPL licensing constraints while maintaining full compatibility with the original functionality. The plugin extends Smarty's template inclusion system with additional features specifically designed for CMS environments.

For reference, see the [original XOOPS implementation](https://github.com/XOOPS/XoopsCore25/blob/v2.5.8/htdocs/class/smarty/xoops_plugins/compiler.includeq.php) to understand the historical context and requirements this plugin addresses.

## Installation

To install and use this package, we recommend to use [Composer](https://getcomposer.org):

```bash
composer require imponeer/smarty-includeq
```

Otherwise, you need to include manually files from `src/` directory.

## Setup

### Modern Smarty Extension (Recommended)

For Smarty 5.x, use the modern extension system by adding the extension to your Smarty instance:

```php
// Create a Smarty instance
$smarty = new \Smarty\Smarty();

// Register the IncludeQ extension
$smarty->addExtension(new \Imponeer\Smarty\Extensions\IncludeQ\IncludeQExtension());
```

### Legacy Plugin Registration

For compatibility with older Smarty versions or legacy code, you can register the compiler directly:

```php
$smarty = new \Smarty();
$includeqPlugin = new \Imponeer\Smarty\Extensions\IncludeQ\IncludeQCompiler();
$smarty->registerPlugin('compiler', 'includeq', [$includeqPlugin, 'compile']);
```

### Using with Dependency Injection Containers

#### Symfony Container

To integrate with Symfony, you can leverage autowiring:

```yaml
# config/services.yaml
services:
    _defaults:
        autowire: true
        autoconfigure: true

    # Configure Smarty with the extension
    \Smarty\Smarty:
        calls:
            - [addExtension, ['@Imponeer\Smarty\Extensions\IncludeQ\IncludeQExtension']]
```

#### PHP-DI Container

With PHP-DI container:

```php
use function DI\create;
use function DI\get;

return [
    \Smarty\Smarty::class => create()
        ->method('addExtension', get(\Imponeer\Smarty\Extensions\IncludeQ\IncludeQExtension::class))
];
```

#### League Container

If you're using League Container, you can register the extension like this:

```php
// Create the container
$container = new \League\Container\Container();

// Register Smarty with the IncludeQ extension
$container->add(\Smarty\Smarty::class, function() {
    $smarty = new \Smarty\Smarty();
    // Configure Smarty...

    // Create and add the IncludeQ extension
    $extension = new \Imponeer\Smarty\Extensions\IncludeQ\IncludeQExtension();
    $smarty->addExtension($extension);

    return $smarty;
});
```

Then in your application code, you can retrieve the Smarty instance:

```php
// Get the configured Smarty instance
$smarty = $container->get(\Smarty\Smarty::class);
```

## Usage

The `{includeq}` tag provides enhanced template inclusion capabilities with support for variable passing and output assignment.

### Basic Template Inclusion

Simple template inclusion:

```smarty
{includeq file="header.tpl"}
```

### Passing Variables to Included Templates

You can pass variables to the included template:

```smarty
{includeq file="user_profile.tpl" user_id=123 show_avatar=true}
```

### Assigning Output to a Variable

Capture the output of the included template into a variable:

```smarty
{includeq file="sidebar.tpl" assign="sidebar_content"}
{* Now you can use $sidebar_content variable *}
<div class="main-content">
    {$sidebar_content}
</div>
```

### Advanced Examples

**Including with dynamic file names:**

```smarty
{includeq file="modules/{$module_name}/template.tpl" module_data=$data}
```

**Conditional inclusion with assignment:**

```smarty
{if $show_sidebar}
    {includeq file="sidebar.tpl" assign="sidebar" user=$current_user}
{/if}
```

**Including with complex variable passing:**

```smarty
{includeq file="product_list.tpl"
          products=$products
          show_prices=true
          currency="USD"
          per_page=20}
```
## Development

### Code Quality Tools

This project uses several tools to ensure code quality:

- **PHPUnit** - For unit testing
  ```bash
  composer test
  ```

- **PHP CodeSniffer** - For coding standards (PSR-12)
  ```bash
  composer phpcs    # Check code style
  composer phpcbf   # Fix code style issues automatically
  ```

- **PHPStan** - For static analysis
  ```bash
  composer phpstan
  ```

## Documentation

API documentation is automatically generated and available in the project's wiki. For more detailed information about the classes and methods, please refer to the [project wiki](https://github.com/imponeer/smarty-includeq/wiki).

## Contributing

Contributions are welcome! Here's how you can contribute:

1. Fork the repository
2. Create a feature branch: `git checkout -b feature-name`
3. Commit your changes: `git commit -am 'Add some feature'`
4. Push to the branch: `git push origin feature-name`
5. Submit a pull request

Please make sure your code follows the PSR-12 coding standard and include tests for any new features or bug fixes.

If you find a bug or have a feature request, please create an issue in the [issue tracker](https://github.com/imponeer/smarty-includeq/issues).