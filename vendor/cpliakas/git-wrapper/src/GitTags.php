<?php

declare(strict_types=1);

namespace GitWrapper;

use ArrayIterator;
use IteratorAggregate;
use Nette\Utils\Strings;

/**
 * Class that parses and returnes an array of Tags.
 */
final class GitTags implements IteratorAggregate
{
    /**
     * @var GitWorkingCopy
     */
    private $gitWorkingCopy;

    public function __construct(GitWorkingCopy $gitWorkingCopy)
    {
        $this->gitWorkingCopy = clone $gitWorkingCopy;
    }

    /**
     * Fetches the Tags via the `git branch` command.
     * @api
     * @return string[]
     */
    public function fetchTags(): array
    {
        $output = $this->gitWorkingCopy->tag([
            'l' => true,
        ]);
        $tags = Strings::split(rtrim($output), "/\r\n|\n|\r/");
        return array_map(function (string $branch): string {
            return $this->trimTags($branch);
        }, $tags);
    }

    /**
     * Strips unwanted characters from the branch
     */
    public function trimTags(string $branch): string
    {
        return ltrim($branch, ' *');
    }

    public function getIterator(): ArrayIterator
    {
        $tags = $this->all();
        return new ArrayIterator($tags);
    }

    /**
     * @api
     * @return mixed[]
     */
    public function all(): array
    {
        return $this->fetchTags();
    }
}
