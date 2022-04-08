<?php

namespace Ssddanbrown\AssertHtml\Constraints;

use Symfony\Component\DomCrawler\Crawler;

class HasLink extends PageConstraint
{
    /**
     * The URL expected to be linked in the <a> tag.
     */
    protected string $url;

    /**
     * The text expected to be found.
     */
    protected ?string $text;

    public function __construct(string $url, ?string $text = null)
    {
        $this->url = $url;
        $this->text = $text;
    }

    /**
     * Check if the link is found in the given crawler.
     */
    public function matches($crawler): bool
    {
        $url = str_replace(['[', ']'], ['\\[', '\\]'], $this->url);
        $links = $this->crawler($crawler)->filter('[href="' . $url . '"]');

        if ($links->count() == 0) {
            return false;
        }

        // If the text is null we assume the developer only wants to find a link
        // with the given url regardless of the text. So if we find the link
        // we will return true. Otherwise, we will look for the given text.
        if ($this->text == null) {
            return true;
        }

        foreach ($links as $link) {
            if ($link->textContent == $this->text) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns the description of the failure.
     */
    public function getFailureDescription(): string
    {
        $description = "has a link with the text [{$this->text}]";

        if ($this->url) {
            $description .= " and the URL [{$this->url}]";
        }

        return $description;
    }

    /**
     * Returns the reversed description of the failure.
     */
    protected function getReverseFailureDescription(): string
    {
        $description = "does not have a link with the text [{$this->text}]";

        if ($this->url) {
            $description .= " and the URL [{$this->url}]";
        }

        return $description;
    }
}
