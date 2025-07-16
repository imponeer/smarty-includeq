<?php

declare(strict_types=1);

namespace Imponeer\Smarty\Extensions\IncludeQ;

use Imponeer\Contracts\Smarty\Extension\SmartyCompilerInterface;
use Smarty_Internal_SmartyTemplateCompiler;
use SmartyCompilerException;

/**
 * Defines {includeq} smarty tag
 *
 * @package Imponeer\Smarty\Extensions\IncludeQ
 */
class IncludeQCompiler implements SmartyCompilerInterface
{
    /**
     * @inheritDoc
     *
     * @throws SmartyCompilerException
     */
    public function execute($args, Smarty_Internal_SmartyTemplateCompiler $compiler)
    {
        $this->validateArguments($args, $compiler);

        $ret = '';

        if (isset($args['assign'])) {
            $ret .= "ob_start();\n";
        }

        $ret .= sprintf(
            '$_smarty_tpl->_subTemplateRender(%s, %s, %s, 0, %s, %s, 0, true);',
            $args['file'],
            '$_smarty_tpl->cache_id',
            '$_smarty_tpl->compile_id',
            '$_smarty_tpl->cache_lifetime',
            $this->renderOtherArgs($args)
        );

        if (isset($args['assign'])) {
            $ret .= sprintf(
                '$_smarty_tpl->assign(%s, ob_get_contents()); ob_end_clean();',
                $args['assign']
            );
        }

        return '<?php ' . $ret . ' ?>';
    }

    /**
     * Validates tag arguments
     *
     * @param array $args Arguments supplied for tag
     * @param Smarty_Internal_SmartyTemplateCompiler $compiler Current smarty compiler instance
     *
     * @throws SmartyCompilerException
     */
    protected function validateArguments(array $args, Smarty_Internal_SmartyTemplateCompiler $compiler): void
    {
        if (!isset($args['file'])) {
            $compiler->trigger_template_error(
                'includeq must have "file" attribute',
                null,
                true
            );
        }

        if (empty($args['file'])) {
            $compiler->trigger_template_error(
                'includeq must have non empty "file" attribute',
                null,
                true
            );
        }

        if (isset($args['assign']) && !$this->isVariableName($args['assign'])) {
            $compiler->trigger_template_error(
                'includeq "assign" attribute must be variable name',
                null,
                true
            );
        }
    }

    /**
     * Checks if argument can be variable name
     *
     * @param string $arg Argument name
     *
     * @return bool
     */
    private function isVariableName(string $arg): bool
    {
        return (bool)preg_match('/^\w+$/', $arg);
    }

    /**
     * Renders other arguments as string
     *
     * @param array $args All supplied tag arguments
     *
     * @return string
     */
    protected function renderOtherArgs(array $args): string
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
     * @param array $args Supplied for tag arguments
     *
     * @return array
     */
    protected function getOtherArguments(array $args): array
    {
        $ret = array_merge($args);

        foreach (['file', 'assign'] as $arg) {
            if (isset($ret[$arg])) {
                unset($ret[$arg]);
            }
        }

        return $ret;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'includeq';
    }
}
