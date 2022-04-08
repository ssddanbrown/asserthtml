<?php

namespace Ssddanbrown\AssertHtml\Constraints;

use PHPUnit\Framework\Constraint\Constraint;
use PHPUnit\Framework\ExpectationFailedException;
use SebastianBergmann\Comparator\ComparisonFailure;
use Symfony\Component\DomCrawler\Crawler;

abstract class PageConstraint extends Constraint
{
    /**
     * Make sure we obtain the HTML from the crawler or the response.
     *
     * @param Crawler|string  $crawler
     */
    protected function html($crawler): string
    {
        return is_object($crawler) ? $crawler->html() : $crawler;
    }

    /**
     * Make sure we obtain the HTML from the crawler or the response.
     *
     * @param Crawler|string  $crawler
     */
    protected function text($crawler): string
    {
        return is_object($crawler) ? $crawler->text() : strip_tags($crawler);
    }

    /**
     * Create a crawler instance if the given value is not already a Crawler.
     *
     * @param Crawler|string  $crawler
     */
    protected function crawler($crawler): Crawler
    {
        return is_object($crawler) ? $crawler : new Crawler($crawler);
    }

    /**
     * Get the escaped text pattern for the constraint.
     */
    protected function getEscapedPattern(string $text): string
    {
        $rawPattern = preg_quote($text, '/');

        $escapedPattern = preg_quote(htmlspecialchars($text, ENT_QUOTES, 'UTF-8', true), '/');

        return $rawPattern == $escapedPattern
            ? $rawPattern : "({$rawPattern}|{$escapedPattern})";
    }

    /**
     * Throw an exception for the given comparison and test description.
     *
     * @throws ExpectationFailedException
     */
    protected function fail($crawler, $description, ComparisonFailure $comparisonFailure = null): void
    {
        $html = $this->html($crawler);

        $failureDescription = sprintf(
            "%s\n\n\nFailed asserting that %s",
            $html, $this->getFailureDescription()
        );

        if (! empty($description)) {
            $failureDescription .= ": {$description}";
        }

        if (trim($html) != '') {
            $failureDescription .= '. Please check the content above.';
        } else {
            $failureDescription .= '. The response is empty.';
        }

        throw new ExpectationFailedException($failureDescription, $comparisonFailure);
    }

    /**
     * Get the description of the failure.
     */
    protected function getFailureDescription(): string
    {
        return 'the page contains '.$this->toString();
    }

    /**
     * Returns the reversed description of the failure.
     */
    protected function getReverseFailureDescription(): string
    {
        return 'the page does not contain '.$this->toString();
    }

    /**
     * Get a string representation of the object.
     *
     * Placeholder method to avoid forcing definition of this method.
     */
    public function toString(): string
    {
        return '';
    }
}
