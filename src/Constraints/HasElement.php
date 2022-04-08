<?php

namespace Ssddanbrown\AssertHtml\Constraints;

use Symfony\Component\DomCrawler\Crawler;

class HasElement extends PageConstraint
{
    /**
     * The name or ID of the element.
     */
    protected string $selector;

    /**
     * The attributes the element should have.
     */
    protected array $attributes;

    public function __construct(string $selector, array $attributes = [])
    {
        $this->selector = $selector;
        $this->attributes = $attributes;
    }

    /**
     * Check if the element is found in the given crawler.
     */
    public function matches($crawler): bool
    {
        $elements = $crawler->filter($this->selector);

        if ($elements->count() == 0) {
            return false;
        }

        if (empty($this->attributes)) {
            return true;
        }

        $elements = $elements->reduce(function (Crawler $element) {
            return $this->hasAttributes($element);
        });

        return $elements->count() > 0;
    }

    /**
     * Determines if the given element has the attributes.
     */
    protected function hasAttributes(Crawler $element): bool
    {
        foreach ($this->attributes as $name => $value) {
            if (is_numeric($name)) {
                if (is_null($element->attr($value))) {
                    return false;
                }
            } else {
                if ($element->attr($name) != $value) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Returns a string representation of the object.
     */
    public function toString(): string
    {
        $message = "the element [{$this->selector}]";

        if (! empty($this->attributes)) {
            $message .= ' with the attributes '.json_encode($this->attributes);
        }

        return $message;
    }
}
