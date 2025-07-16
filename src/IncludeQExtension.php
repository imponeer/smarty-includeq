<?php

declare(strict_types=1);

namespace Imponeer\Smarty\Extensions\IncludeQ;

use Smarty\Extension\Base;
use Smarty\Compile\CompilerInterface;

/**
 * Smarty IncludeQ Extension
 *
 * @package Imponeer\Smarty\Extensions\IncludeQ
 */
class IncludeQExtension extends Base
{
    /**
     * @inheritDoc
     *
     * @noinspection MethodShouldBeFinalInspection
     */
    public function getTagCompiler(string $tag): ?CompilerInterface
    {
        if ($tag === 'includeq') {
            return new IncludeQCompiler();
        }

        return null;
    }
}
