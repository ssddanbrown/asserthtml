<?php

namespace Ssddanbrown\AssertHtml\Constraints;

use Symfony\Component\DomCrawler\Crawler;

abstract class FormFieldConstraint extends PageConstraint
{
    /**
     * The name or ID of the element.
     */
    protected string $selector;

    /**
     * The expected value.
     */
    protected string $value;

    public function __construct(string $selector, $value)
    {
        $this->selector = $selector;
        $this->value = (string) $value;
    }

    /**
     * Get the valid elements.
     *
     * Multiple elements should be separated by commas without spaces.
     */
    abstract protected function validElements(): string;

    /**
     * Get the form field.
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    protected function field(Crawler $crawler): Crawler
    {
        $field = $crawler->filter(implode(', ', $this->getElements()));

        if ($field->count() > 0) {
            return $field;
        }

        $this->fail($crawler, sprintf(
            'There is no %s with the name or ID [%s]',
            $this->validElements(), $this->selector
        ));
    }

    /**
     * Get the elements relevant to the selector.
     */
    protected function getElements(): array
    {
        $name = str_replace('#', '', $this->selector);

        $id = str_replace(['[', ']'], ['\\[', '\\]'], $name);

        return array_map(function ($element) use ($name, $id) {
            return "{$element}#{$id}, {$element}[name='{$name}']";
        }, explode(',', $this->validElements()));
    }
}
