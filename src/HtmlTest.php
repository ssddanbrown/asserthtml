<?php

namespace Ssddanbrown\AssertHtml;

use Ssddanbrown\AssertHtml\Constraints\HasElement;
use Ssddanbrown\AssertHtml\Constraints\HasInElement;
use Ssddanbrown\AssertHtml\Constraints\HasLink;
use Ssddanbrown\AssertHtml\Constraints\HasValue;
use Ssddanbrown\AssertHtml\Constraints\IsChecked;
use Ssddanbrown\AssertHtml\Constraints\IsSelected;
use Ssddanbrown\AssertHtml\Constraints\PageConstraint;
use Ssddanbrown\AssertHtml\Constraints\ReversePageConstraint;
use Symfony\Component\DomCrawler\Crawler;
use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertThat;

class HtmlTest
{
    protected string $html;
    protected Crawler $crawler;

    public function __construct(string $html)
    {
        $this->html = $html;
        $this->crawler = new Crawler($html);
    }

    /**
     * Assert that an element is present.
     */
    public function assertElementExists(string $selector, array $attributes = []): self
    {
        return $this->assertInPage(new HasElement($selector, $attributes));
    }

    /**
     * Assert that an element is not present.
     */
    public function assertElementNotExists(string $selector, array $attributes = []): self
    {
        return $this->assertInPage(new HasElement($selector, $attributes), true);
    }

    /**
     * Verify the number of DOM elements.
     */
    public function assertElementCount(string $selector, int $count): self
    {
        assertCount($count, $this->crawler->filter($selector));

        return $this;
    }

    /**
     * Assert that a given string is seen inside an element.
     */
    public function assertElementContains(string $selector, string $text): self
    {
        return $this->assertInPage(new HasInElement($selector, $text));
    }

    /**
     * Assert that a given string is not seen inside an element.
     */
    public function assertElementNotContains(string $selector, string $text): self
    {
        return $this->assertInPage(new HasInElement($selector, $text), true);
    }

    /**
     * Assert that a given link is seen on the page.
     */
    public function assertLinkExists(string $url, string $text = null): self
    {
        return $this->assertInPage(new HasLink($url, $text));
    }

    /**
     * Assert that a given link is not seen on the page.
     */
    public function assertLinkNotExists(string $url, string $text = null): self
    {
        return $this->assertInPage(new HasLink($url, $text), true);
    }

    /**
     * Assert that an input field contains the given value.
     */
    public function assertFieldHasValue(string $fieldNameOrId, string $expected): self
    {
        return $this->assertInPage(new HasValue($fieldNameOrId, $expected));
    }

    /**
     * Assert that an input field does not contain the given value.
     */
    public function assertFieldNotHasValue(string $fieldNameOrId, string $value): self
    {
        return $this->assertInPage(new HasValue($fieldNameOrId, $value), true);
    }

    /**
     * Assert that the expected value is selected.
     */
    public function assertFieldHasSelected(string $fieldNameOrId, string $value): self
    {
        return $this->assertInPage(new IsSelected($fieldNameOrId, $value));
    }

    /**
     * Assert that the given value is not selected.
     */
    public function assertFieldNotHasSelected(string $fieldNameOrId, string $value): self
    {
        return $this->assertInPage(new IsSelected($fieldNameOrId, $value), true);
    }

    /**
     * Assert that the given checkbox is selected.
     */
    public function assertCheckboxChecked(string $inputNameOrId): self
    {
        return $this->assertInPage(new IsChecked($inputNameOrId));
    }

    /**
     * Assert that the given checkbox is not selected.
     */
    public function assertCheckboxNotChecked(string $inputNameOrId): self
    {
        return $this->assertInPage(new IsChecked($inputNameOrId), true);
    }

    /**
     * Assert the given constraint.
     */
    protected function assertInPage(PageConstraint $constraint, bool $reverse = false, string $message = ''): self
    {
        if ($reverse) {
            $constraint = new ReversePageConstraint($constraint);
        }

        assertThat($this->crawler, $constraint, $message);

        return $this;
    }

    /**
     * Helper to get the outer HTML of first match of the given selector.
     * If no selector is provided the entire HTML will be returned.
     */
    public function getOuterHtml(?string $selector = null): string
    {
        if (!$selector) {
            return trim($this->html);
        }

        return $this->crawler->filter($selector)->first()->outerHtml();
    }

    /**
     * Helper to get the inner HTML of first match of the given selector.
     */
    public function getInnerHtml(string $selector): string
    {
        return $this->crawler->filter($selector)->first()->html();
    }

}