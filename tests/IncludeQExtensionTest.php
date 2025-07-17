<?php

declare(strict_types=1);

namespace Imponeer\Smarty\Extensions\IncludeQ\Tests;

use Imponeer\Smarty\Extensions\IncludeQ\IncludeQExtension;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;
use Smarty\Exception;
use Smarty\Smarty;

/**
 * Test class for IncludeQ extension functionality.
 */
class IncludeQExtensionTest extends TestCase
{
    private Smarty $smarty;
    private vfsStreamDirectory $vfsRoot;

    /**
     * @noinspection MethodShouldBeFinalInspection
     * @noinspection MethodVisibilityInspection
     */
    protected function setUp(): void
    {
        $this->vfsRoot = vfsStream::setup('templates');

        $this->smarty = new Smarty();
        $this->smarty->setCaching(Smarty::CACHING_OFF);

        $this->smarty->setTemplateDir($this->vfsRoot->url());

        $this->smarty->addExtension(
            new IncludeQExtension()
        );

        parent::setUp();
    }

    /**
     * @throws Exception
     */
    final public function testBasicIncludeQ(): void
    {
        $src = urlencode(
            sprintf(
                "{includeq file=\"%s\"}",
                'eval:test'
            )
        );
        $ret = $this->smarty->fetch('eval:urlencode:' . $src);

        $this->assertSame('test', $ret);
    }

    /**
     * @throws Exception
     */
    final public function testIncludeQWithVariablePassing(): void
    {
        $templateContent = '{$user_id}-{if $show_avatar}avatar{/if}';

        vfsStream::newFile('user_profile.tpl')
            ->withContent($templateContent)
            ->at($this->vfsRoot);

        $src = urlencode(
            sprintf(
                "{includeq file=\"%s\" user_id=123 show_avatar=true}",
                'user_profile.tpl'
            )
        );
        $ret = $this->smarty->fetch('eval:urlencode:' . $src);

        $this->assertSame('123-avatar', $ret);
    }

    /**
     * @throws Exception
     */
    final public function testIncludeQWithAssignment(): void
    {
        $src = urlencode(
            sprintf(
                "{includeq file=\"%s\" assign=\"sidebar_content\"}{\$sidebar_content}",
                'eval:sidebar content'
            )
        );
        $ret = $this->smarty->fetch('eval:urlencode:' . $src);

        $this->assertSame('sidebar content', $ret);
    }

    /**
     * @throws Exception
     */
    final public function testIncludeQWithVariablePassingAndAssignment(): void
    {
        $templateContent = 'Hello {$name}, you are {$age} years old';

        vfsStream::newFile('greeting.tpl')
            ->withContent($templateContent)
            ->at($this->vfsRoot);

        $src = urlencode(
            sprintf(
                "{includeq file=\"%s\" assign=\"result\" name=\"John\" age=25}{\$result}",
                'greeting.tpl'
            )
        );
        $ret = $this->smarty->fetch('eval:urlencode:' . $src);

        $this->assertSame('Hello John, you are 25 years old', $ret);
    }

    /**
     * @throws Exception
     */
    final public function testIncludeQWithComplexVariables(): void
    {
        $this->smarty->assign('products', ['laptop', 'mouse', 'keyboard']);

        $templateContent = '{foreach $products as $product}{$product} in {$currency}' .
                           '{if not $product@last}, {/if}{/foreach}';

        vfsStream::newFile('product_list.tpl')
            ->withContent($templateContent)
            ->at($this->vfsRoot);

        $src = urlencode(
            sprintf(
                "{includeq file=\"%s\" products=\$products show_prices=true currency=\"USD\"}",
                'product_list.tpl'
            )
        );
        $ret = $this->smarty->fetch('eval:urlencode:' . $src);

        $this->assertSame('laptop in USD, mouse in USD, keyboard in USD', $ret);
    }

    /**
     * @throws Exception
     */
    final public function testIncludeQWithDynamicFileName(): void
    {
        $this->smarty->assign('module_name', 'user');

        $templateContent = 'Module: {$module_name} - Data: {$module_data}';

        vfsStream::newFile('module.tpl')
            ->withContent($templateContent)
            ->at($this->vfsRoot);

        $src = urlencode(
            sprintf(
                "{includeq file=\"%s\" module_data=\"test_data\"}",
                'module.tpl'
            )
        );
        $ret = $this->smarty->fetch('eval:urlencode:' . $src);

        $this->assertSame('Module: user - Data: test_data', $ret);
    }
}
