<?php namespace Ssddanbrown\HtmlTest\Tests;

use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Ssddanbrown\HtmlTest\HtmlTest;

class AssertionTest extends TestCase
{

    public function test_assert_element_exists()
    {
        $html = new HtmlTest('
            <body>
                <p id="donkey">Hello</p>
            </body>
        ');

        $html->assertElementExists('#donkey');
        $html->assertElementExists('p[id="donkey"]');
        $html->assertElementNotExists('p[id="donkeys"]');
        $html->assertElementNotExists('div');

        $this->assertFailure(fn() => $html->assertElementExists('#donkeys'));
        $this->assertFailure(fn() => $html->assertElementNotExists('#donkey'));
    }

    public function test_assert_element_count()
    {
        $html = new HtmlTest('
            <body>
                <ul>
                <li>Item A</li>
                <li id="donkey">Item B</li>
                <li>Item C</li>
                </ul>
            </body>
        ');

        $html->assertElementCount('li', 3);
        $html->assertElementCount('ul', 1);
        $html->assertElementCount('#donkey', 1);
        $html->assertElementCount('ul,li', 4);
        $this->assertFailure(fn() => $html->assertElementCount('ul', 3));
    }

    // TODO - Continue Testing

    protected function assertFailure(callable $callback): void
    {
        $failed = false;

        try {
            $callback();
        } catch (ExpectationFailedException $exception) {
            $failed = true;
        }

        static::assertTrue($failed);
    }

}