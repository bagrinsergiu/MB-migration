<?php

namespace Utils\Rector\Rector;

use MBMigration\Core\Logger;
use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class UtilsMessagePoolRector extends \Rector\Rector\AbstractRector
{
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        // what node types are we looking for?
        // pick from
        // https://github.com/rectorphp/php-parser-nodes-docs/
        return [StaticCall::class];
    }

    private function isClassSupported($class)
    {
        if ($class == 'MBMigration\Core\Utils') {
            return true;
        }

        return false;
    }

    public function refactor(Node $node): ?Node
    {
        if (!$this->isClassSupported($node->class)) {
            return null;
        }

        $methodCallName = $this->getName($node->name);
        if ($methodCallName === null) {
            return null;
        }

        if ($methodCallName !== 'MESSAGES_POOL') {
            // return null to skip it
            return null;
        }

        $fullyQualified = new Node\Name\FullyQualified(Logger::class);
        $staticCall = new StaticCall($fullyQualified, 'instance', [], []);


        $logMessage = $node->args[0]->value;
        $logMethod = 'info';


        //$node->name = new Identifier('debug');
        $args = [];
        $args[] = $node->args[0];
        $methodCall = new Node\Expr\MethodCall($staticCall, $logMethod, $args);

        return $methodCall;
    }

    /**
     * This method helps other to understand the rule
     * and to generate documentation.
     */
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Change method Utils::logs method call to Utils::{mathod} ', [
                new CodeSample(
                // code before
                    'Utils::log(\'Upload Logo menu\', 1, \'createMenu\');',
                    // code after
                    'Logger::info(\'Upload Logo menu\');'
                ),
            ]
        );
    }
}