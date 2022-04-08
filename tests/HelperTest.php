<?php namespace Ssddanbrown\AssertHtml\Tests;

use PHPUnit\Framework\TestCase;
use Ssddanbrown\AssertHtml\HtmlTest;

class HelperTest extends TestCase
{
    public function test_get_outer_html()
    {
        $html = new HtmlTest('
            <body>
                <p id="donkey">Hello</p>
            </body>
        ');

        $this->assertEquals('<body>
                <p id="donkey">Hello</p>
            </body>', $html->getOuterHtml());
        $this->assertEquals('<p id="donkey">Hello</p>', $html->getOuterHtml('p#donkey'));
    }

    public function test_get_inner_html()
    {
        $html = new HtmlTest('
            <body>
                <p id="donkey"><span>Hello</span>There</p>
            </body>
        ');

        $this->assertEquals('<span>Hello</span>There', $html->getInnerHtml('p#donkey'));
        $this->assertEquals('Hello', $html->getInnerHtml('span'));
    }

}