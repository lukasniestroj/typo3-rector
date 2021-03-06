<?php

declare(strict_types=1);

namespace Ssch\TYPO3Rector\Rector\v8\v6;

use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;
use TYPO3\CMS\Core\Utility\ArrayUtility;

/**
 * @see https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/8.6/Deprecation-79316-DeprecateArrayUtilityinArray.html
 */
final class ArrayUtilityInArrayToFuncInArrayRector extends AbstractRector
{
    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /**
     * @param StaticCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $this->isMethodStaticCallOrClassMethodObjectType($node, ArrayUtility::class)) {
            return null;
        }
        if (! $this->isName($node->name, 'inArray')) {
            return null;
        }

        return $this->createFuncCall('in_array', [$node->args[1], $node->args[0], $this->createTrue()]);
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Method inArray from ArrayUtility to in_array', [
            new CodeSample('ArrayUtility::inArray()', 'in_array'),
        ]);
    }
}
