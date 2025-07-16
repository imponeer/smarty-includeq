<?php

declare(strict_types=1);

namespace Imponeer\Smarty\Extensions\IncludeQ\Tests;

use Imponeer\Smarty\Extensions\IncludeQ\IncludeQCompiler;
use Imponeer\Smarty\Extensions\IncludeQ\IncludeQExtension;
use PHPUnit\Framework\TestCase;
use Smarty\Exception;
use Smarty\Smarty;

class IncludeQExtensionTest extends TestCase
{
    private Smarty $smarty;

    /**
     * @noinspection MethodShouldBeFinalInspection
     * @noinspection MethodVisibilityInspection
     */
    protected function setUp(): void
    {
        $this->smarty = new Smarty();
        $this->smarty->setCaching(Smarty::CACHING_OFF);
        $this->smarty->addExtension(
            new IncludeQExtension()
        );

        parent::setUp();
    }

    /**
     * @throws Exception
     */
    final public function testIncludeQ(): void
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
}
