<?php

use Imponeer\Smarty\Extensions\IncludeQ\IncludeQCompiler;
use PHPUnit\Framework\TestCase;

class IncludeQCompilerTest extends TestCase
{

    /**
     * @var Smarty
     */
    private $smarty;

    protected function setUp(): void
    {
        $this->plugin = new IncludeQCompiler();

        $this->smarty = new Smarty();
        $this->smarty->caching = Smarty::CACHING_OFF;
        $this->smarty->registerPlugin(
            'compiler',
            $this->plugin->getName(),
            [$this->plugin, 'execute']
        );

        parent::setUp();
    }

    public function testGetName() {
        $this->assertSame(
            'includeq',
            $this->plugin->getName()
        );
    }

    public function testInvoking() {
        $src = urlencode(
            sprintf(
                "{includeq file=\"%s\"}",
                'eval:test'
            )
        );
        $ret = $this->smarty->fetch('eval:urlencode:'.$src);

        $this->assertSame('test', $ret);
    }

}