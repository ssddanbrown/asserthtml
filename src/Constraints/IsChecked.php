<?php

namespace Ssddanbrown\AssertHtml\Constraints;

use Symfony\Component\DomCrawler\Crawler;

class IsChecked extends FormFieldConstraint
{

    public function __construct(string $selector)
    {
        $this->selector = $selector;
    }

    /**
     * Get the valid elements.
     */
    protected function validElements(): string
    {
        return "input[type='checkbox']";
    }

    /**
     * Determine if the checkbox is checked.
     */
    public function matches($crawler): bool
    {
        $crawler = $this->crawler($crawler);

        return ! is_null($this->field($crawler)->attr('checked'));
    }

    /**
     * Return the description of the failure.
     */
    protected function getFailureDescription(): string
    {
        return "the checkbox [{$this->selector}] is checked";
    }

    /**
     * Returns the reversed description of the failure.
     */
    protected function getReverseFailureDescription(): string
    {
        return "the checkbox [{$this->selector}] is not checked";
    }
}
