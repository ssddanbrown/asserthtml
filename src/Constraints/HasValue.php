<?php

namespace Ssddanbrown\AssertHtml\Constraints;

use Symfony\Component\DomCrawler\Crawler;

class HasValue extends FormFieldConstraint
{
    /**
     * Get the valid elements.
     */
    protected function validElements(): string
    {
        return 'input,textarea';
    }

    /**
     * Check if the input contains the expected value.
     */
    public function matches($crawler): bool
    {
        $crawler = $this->crawler($crawler);

        return $this->getInputOrTextAreaValue($crawler) == $this->value;
    }

    /**
     * Get the value of an input or textarea.
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function getInputOrTextAreaValue(Crawler $crawler): string
    {
        $field = $this->field($crawler);

        return $field->nodeName() == 'input'
            ? $field->attr('value')
            : $field->text();
    }

    /**
     * Return the description of the failure.
     */
    protected function getFailureDescription(): string
    {
        return sprintf(
            'the field [%s] contains the expected value [%s]',
            $this->selector, $this->value
        );
    }

    /**
     * Returns the reversed description of the failure.
     */
    protected function getReverseFailureDescription(): string
    {
        return sprintf(
            'the field [%s] does not contain the expected value [%s]',
            $this->selector, $this->value
        );
    }
}
