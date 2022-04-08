<?php

namespace Ssddanbrown\HtmlAssert;

trait TestsHtml
{
    public function withHtml(\Illuminate\Testing\TestResponse $response): HtmlTest
    {
        return new HtmlTest($response->getContent());
    }
}