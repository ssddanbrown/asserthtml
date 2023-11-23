<?php

namespace Ssddanbrown\AssertHtml;

/**
 * Trait for usage in Laravel testing.
 * Optionally pass a test response returned from a get/post/put etc... function.
 */
trait TestsHtml
{
    /**
     * @param TestResponse|null $response if null, will use the most recent response
     * @return HtmlTest
     * @link https://github.com/ssddanbrown/asserthtml
     */
    public function withHtml(TestResponseIlluminate\Testing\TestResponse $response = null): HtmlTest
    {
        return new HtmlTest(($response ?? static::$latestResponse)->getContent());
    }
}
