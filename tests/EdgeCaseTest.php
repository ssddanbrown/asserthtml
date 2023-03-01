<?php

namespace Ssddanbrown\AssertHtml\Tests;

use PHPUnit\Framework\TestCase;
use Ssddanbrown\AssertHtml\HtmlTest;

class EdgeCaseTest extends TestCase
{

    /**
     * Ensure the usage of quote characters through the process remains valid
     */
    public function test_assert_element_contains_can_match_quotes()
    {
        $html = new HtmlTest(<<<HTML
            <body>
                <p>Here's the thing</p>
                <p>They said "cheese"</p>
            </body>
        HTML);

        $html->assertElementContains('p', "Here's the thing");
        $html->assertElementContains('p', 'They said "cheese"');

        $test = new HtmlTest('<p>Test \' & String</p>');

        $test->assertElementContains('p', 'Test \' & String');
    }

    /**
     * Ensure that the escaping/quoting behaviour between quotes and ampersands remains compatible
     * for when doing our matching. This test was added since lone ampersands (although valid in HTML5+)
     * would be auto-escaped on the HTML parser side, then quotes are handled specifically when testing
     * against given text so the two scenarios could conflict and cause trouble.
     */
    public function test_assert_element_contains_can_match_unambiguous_lone_ampersands_with_quotes()
    {
        $html = new HtmlTest(<<<HTML
            <body>
                <p>Here's the real &amp; honest thing</p>
                <p>Being "cool & floofy" is rad</p>
                <p>My name&apos;s Barry & Steve</p>
                <p>Mittens has &quot;tortitude & ferocity&quot;</p>
            </body>
        HTML);

        $html->assertElementContains('p', "Here's the real & honest thing");
        $html->assertElementContains('p', 'Being "cool & floofy" is rad');
        $html->assertElementContains('p', "My name's Barry & Steve");
        $html->assertElementContains('p', 'Mittens has "tortitude & ferocity"');
    }

}