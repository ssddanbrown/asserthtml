<?php

namespace Ssddanbrown\AssertHtml\Constraints;

use DOMElement;
use Symfony\Component\DomCrawler\Crawler;

class IsSelected extends FormFieldConstraint
{
    /**
     * Get the valid elements.
     */
    protected function validElements(): string
    {
        return 'select,input[type="radio"]';
    }

    /**
     * Determine if the select or radio element is selected.
     */
    protected function matches($crawler): bool
    {
        $crawler = $this->crawler($crawler);

        return in_array($this->value, $this->getSelectedValue($crawler));
    }

    /**
     * Get the selected value of a select field or radio group.
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function getSelectedValue(Crawler $crawler): array
    {
        $field = $this->field($crawler);

        return $field->nodeName() == 'select'
            ? $this->getSelectedValueFromSelect($field)
            : [$this->getCheckedValueFromRadioGroup($field)];
    }

    /**
     * Get the selected value from a select field.
     */
    protected function getSelectedValueFromSelect(Crawler $select): array
    {
        $selected = [];

        foreach ($select->children() as $option) {
            if ($option->nodeName === 'optgroup') {
                foreach ($option->childNodes as $child) {
                    if ($child->hasAttribute('selected')) {
                        $selected[] = $this->getOptionValue($child);
                    }
                }
            } elseif ($option->hasAttribute('selected')) {
                $selected[] = $this->getOptionValue($option);
            }
        }

        return $selected;
    }

    /**
     * Get the selected value from an option element.
     */
    protected function getOptionValue(DOMElement $option): string
    {
        if ($option->hasAttribute('value')) {
            return $option->getAttribute('value');
        }

        return $option->textContent;
    }

    /**
     * Get the checked value from a radio group.
     */
    protected function getCheckedValueFromRadioGroup(Crawler $radioGroup): ?string
    {
        foreach ($radioGroup as $radio) {
            if ($radio->hasAttribute('checked')) {
                return $radio->getAttribute('value');
            }
        }
    }

    /**
     * Returns the description of the failure.
     */
    protected function getFailureDescription(): string
    {
        return sprintf(
            'the element [%s] has the selected value [%s]',
            $this->selector, $this->value
        );
    }

    /**
     * Returns the reversed description of the failure.
     */
    protected function getReverseFailureDescription(): string
    {
        return sprintf(
            'the element [%s] does not have the selected value [%s]',
            $this->selector, $this->value
        );
    }
}
