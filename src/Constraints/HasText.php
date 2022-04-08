<?php

namespace Ssddanbrown\AssertHtml\Constraints;

use Symfony\Component\DomCrawler\Crawler;

class HasText extends PageConstraint
{
    /**
     * The expected text.
     */
    protected string $text;

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    /**
     * Check if the plain text is found in the given crawler.
     */
    protected function matches($crawler): bool
    {
        $pattern = $this->getEscapedPattern($this->text);

        return preg_match("/{$pattern}/i", $this->text($crawler));
    }

    /**
     * Returns a string representation of the object.
     */
    public function toString(): string
    {
        return "the text [{$this->text}]";
    }
}
