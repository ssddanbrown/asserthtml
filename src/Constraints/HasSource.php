<?php

namespace Ssddanbrown\AssertHtml\Constraints;

use Symfony\Component\DomCrawler\Crawler;

class HasSource extends PageConstraint
{
    /**
     * The expected HTML source.
     */
    protected string $source;

    public function __construct(string $source)
    {
        $this->source = $source;
    }

    /**
     * Check if the source is found in the given crawler.
     */
    protected function matches($crawler): bool
    {
        $pattern = $this->getEscapedPattern($this->source);

        return preg_match("/{$pattern}/i", $this->html($crawler));
    }

    /**
     * Returns a string representation of the object.
     */
    public function toString(): string
    {
        return "the HTML [{$this->source}]";
    }
}
