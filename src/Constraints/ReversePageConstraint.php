<?php

namespace Ssddanbrown\AssertHtml\Constraints;

use Symfony\Component\DomCrawler\Crawler;

class ReversePageConstraint extends PageConstraint
{
    /**
     * The page constraint instance.
     */
    protected PageConstraint $pageConstraint;

    public function __construct(PageConstraint $pageConstraint)
    {
        $this->pageConstraint = $pageConstraint;
    }

    /**
     * Reverse the original page constraint result.
     */
    public function matches($crawler): bool
    {
        return ! $this->pageConstraint->matches($crawler);
    }

    /**
     * Get the description of the failure.
     *
     * This method will attempt to negate the original description.
     */
    protected function getFailureDescription(): string
    {
        return $this->pageConstraint->getReverseFailureDescription();
    }

    /**
     * Get a string representation of the object.
     */
    public function toString(): string
    {
        return $this->pageConstraint->toString();
    }
}
