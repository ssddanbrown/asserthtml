<?php

namespace Ssddanbrown\AssertHtml\Constraints;

use Symfony\Component\DomCrawler\Crawler;

class HasInElement extends PageConstraint
{
    /**
     * A selector to find the element.
     */
    protected string $selector;

    /**
     * The text expected to be found.
     */
    protected string $text;


    public function __construct(string $selector, string $text)
    {
        $this->text = $text;
        $this->selector = $selector;
    }

    /**
     * Check if the source or text is found within the element in the given crawler.
     */
    public function matches($crawler): bool
    {
        $elements = $this->crawler($crawler)->filter($this->selector);

        $pattern = $this->getEscapedPattern($this->text);

        foreach ($elements as $element) {
            $element = new Crawler($element);

            if (preg_match("/$pattern/i", $element->html())) {
                return true;
            }

            if (preg_match("/$pattern/i", $element->text())) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns the description of the failure.
     */
    protected function getFailureDescription(): string
    {
        return sprintf('[%s] contains %s', $this->selector, $this->text);
    }

    /**
     * Returns the reversed description of the failure.
     */
    protected function getReverseFailureDescription(): string
    {
        return sprintf('[%s] does not contain %s', $this->selector, $this->text);
    }
}
