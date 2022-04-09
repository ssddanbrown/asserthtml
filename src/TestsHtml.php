<?php

namespace Ssddanbrown\AssertHtml;

/**
 * Trait for usage in Laravel testing.
 * Simply pass a test response returned from a get/post/put etc... function.
 */
trait TestsHtml
{
    public function withHtml(\Illuminate\Testing\TestResponse $response): HtmlTest
    {
        return new HtmlTest($response->getContent());
    }
}