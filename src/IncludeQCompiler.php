<?php

declare(strict_types=1);

namespace Imponeer\Smarty\Extensions\IncludeQ;

use Smarty\Compile\Base;
use Smarty\Compiler\Template;

/**
 * Defines {includeq} smarty tag compiler
 *
 * @package Imponeer\Smarty\Extensions\IncludeQ
 */
class IncludeQCompiler extends Base
{
    /**
     * Required attributes for the tag
     *
     * @var string[]
     */
    protected $required_attributes = ['file'];

    /**
     * Optional attributes for the tag
     *
     * @var string[]
     */
    protected $optional_attributes = ['assign', '_any'];

    /**
     * Override getAttributes to allow any additional attributes
     *
     * @param mixed $compiler
     * @param mixed $attributes
     * @return array<string, mixed>
     *
     * @noinspection MethodShouldBeFinalInspection
     * @noinspection MethodVisibilityInspection
     */
    protected function getAttributes($compiler, $attributes): array
    {
        $resultAttr = [];

        if (is_array($attributes)) {
            foreach ($attributes as $arg) {
                if (is_array($arg)) {
                    /** @noinspection SlowArrayOperationsInLoopInspection */
                    $resultAttr = array_merge($resultAttr, $arg);
                }
            }
        }

        foreach ($this->required_attributes as $attr) {
            if (!isset($resultAttr[$attr])) {
                $compiler->trigger_template_error("missing '{$attr}' attribute");
            }
        }

        return $resultAttr;
    }

    /**
     * Compiles code for the {includeq} tag
     *
     * @param list<array<string, mixed>> $args array with attributes from parser
     * @param Template $compiler compiler object
     * @param mixed[] $parameter array with compilation parameter
     * @param string|null $tag tag name
     * @param string|null $function function name
     *
     * @return string compiled code as a string
     *
     * @noinspection MethodShouldBeFinalInspection
     */
    public function compile($args, Template $compiler, $parameter = [], $tag = null, $function = null): string
    {
        $_attr = $this->getAttributes($compiler, $args);

        $ret = '';

        if (isset($_attr['assign'])) {
            $ret .= "ob_start();\n";
        }

        $ret .= sprintf(
            '$_smarty_tpl->renderSubTemplate(%s, %s, %s, %d, %s, %s, %d, true);',
            $_attr['file'],
            '$_smarty_tpl->cache_id',
            '$_smarty_tpl->compile_id',
            0,
            '$_smarty_tpl->cache_lifetime',
            $this->renderOtherArgs($_attr),
            0
        );

        if (isset($_attr['assign'])) {
            $ret .= sprintf(
                '$_smarty_tpl->assign(%s, ob_get_contents()); ob_end_clean();',
                $_attr['assign']
            );
        }

        return '<?php ' . $ret . ' ?>';
    }



    /**
     * Renders other arguments as string
     *
     * @param array<string, mixed> $args All supplied tag arguments
     *
     * @return string
     */
    private function renderOtherArgs(array $args): string
    {
        $ret = '[';

        foreach ($this->getOtherArguments($args) as $name => $value) {
            $ret .= sprintf(
                "'%s' => %s,",
                $name,
                is_bool($value) ? var_export($value, true) : $value
            );
        }

        return $ret . ']';
    }

    /**
     * Gets arguments that doesn't have any specific logic for usability
     *
     * @param array<string, mixed> $args Supplied for tag arguments
     *
     * @return array<string, mixed>
     */
    private function getOtherArguments(array $args): array
    {
        $ret = array_merge($args);

        foreach (['file', 'assign'] as $arg) {
            if (isset($ret[$arg])) {
                unset($ret[$arg]);
            }
        }

        return $ret;
    }
}
