<?php namespace Ssddanbrown\AssertHtml\Tests;

use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use Ssddanbrown\AssertHtml\HtmlTest;

class AssertionTest extends TestCase
{

    public function test_assert_element_exists()
    {
        $html = new HtmlTest('
            <body>
                <p id="donkey">Hello</p>
                <p data-test="barry" class="cat">There</p>
            </body>
        ');

        $html->assertElementExists('#donkey');
        $html->assertElementExists('p[id="donkey"]');
        $html->assertElementExists('p', ['id' => 'donkey']);
        $html->assertElementExists('p', ['data-test' => 'barry', 'class' => 'cat']);
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

    public function test_assert_element_contains()
    {
        $html = new HtmlTest('
            <body>
                <p class="a">Barry was here</p>
                <div>Barry was not here</div>
            </body>
        ');

        $html->assertElementContains('.a', 'Barry was here');
        $html->assertElementContains('div', 'Barry was not here');
        $html->assertElementNotContains('.a', 'Barry was not here');
        $this->assertFailure(fn() => $html->assertElementContains('.a', 'Barry was not here'));
        $this->assertFailure(fn() => $html->assertElementContains('div', 'Barry was here'));
        $this->assertFailure(fn() => $html->assertElementNotContains('p', 'Barry was here'));
    }

    public function test_assert_link_exists()
    {
        $html = new HtmlTest('
            <body>
                <p id="a"><a href="https://example.com/donkey-a">Click here</a></p>
                <p id="b"><a href="https://example.com/donkey-b">Click somewhere else</a></p>
            </body>
        ');

        $html->assertLinkExists('https://example.com/donkey-a');
        $html->assertLinkExists('https://example.com/donkey-b', 'Click somewhere else');
        $html->assertLinkNotExists('https://examples.com/donkey-a');
        $html->assertLinkNotExists('https://example.com/donkey-a', 'Click there');
        $this->assertFailure(fn() => $html->assertLinkExists('https://example.com/donkey-c'));
        $this->assertFailure(fn() => $html->assertLinkExists('https://example.com/donkey-a', 'Click there'));
        $this->assertFailure(fn() => $html->assertLinkNotExists('https://example.com/donkey-a', 'Click here'));
    }

    public function test_assert_field_has_value()
    {
        $html = new HtmlTest('
            <body>
                <input type="text" name="abc" id="def" value="cat">
                <textarea name="hij" id="klm">dog</textarea>
            </body>
        ');

        $html->assertFieldHasValue('abc', 'cat');
        $html->assertFieldHasValue('def', 'cat');
        $html->assertFieldHasValue('hij', 'dog');
        $html->assertFieldNotHasValue('abc', 'cats');
        $this->assertFailure(fn() => $html->assertFieldHasValue('abc', 'cats'));
        $this->assertFailure(fn() => $html->assertFieldNotHasValue('klm', 'dog'));
    }

    public function test_assert_field_has_selected()
    {
        $html = new HtmlTest('
            <body>
                <select name="abc" id="def">
                    <option value="a">Option A</option>
                    <option value="b" selected>Option B</option>
                    <option value="c">Option C</option>
                </select>
                <input type="radio" name="breakfast" value="Beans">
                <input type="radio" name="breakfast" checked value="Toast">
                <input type="radio" name="breakfast" value="Tomato">
            </body>
        ');

        $html->assertFieldHasSelected('abc', 'b');
        $html->assertFieldHasSelected('def', 'b');
        $html->assertFieldHasSelected('breakfast', 'Toast');
        $html->assertFieldNotHasSelected('abc', 'a');
        $html->assertFieldNotHasSelected('breakfast', 'Tomato');
        $this->assertFailure(fn() => $html->assertFieldHasSelected('abc', 'cat'));
        $this->assertFailure(fn() => $html->assertFieldHasSelected('abcd', 'b'));
        $this->assertFailure(fn() => $html->assertFieldHasSelected('breakfast', 'Beans'));
        $this->assertFailure(fn() => $html->assertFieldNotHasSelected('breakfast', 'Toast'));
        $this->assertFailure(fn() => $html->assertFieldNotHasSelected('def', 'b'));
    }

    public function test_assert_checkbox_checked()
    {
        $html = new HtmlTest('
            <body>
                <input type="checkbox" name="breakfast-a" id="a" value="Beans">
                <input type="checkbox" name="breakfast-b" id="b" value="Tomato" checked>
                <input type="checkbox" name="breakfast-c" id="c" value="Toast">
            </body>
        ');

        $html->assertCheckboxChecked('b');
        $html->assertCheckboxChecked('breakfast-b');
        $html->assertCheckboxNotChecked('c');
        $this->assertFailure(fn() => $html->assertCheckboxChecked('c'));
        $this->assertFailure(fn() => $html->assertCheckboxChecked('donkey'));
        $this->assertFailure(fn() => $html->assertCheckboxNotChecked('b'));
        $this->assertFailure(fn() => $html->assertCheckboxNotChecked('breakfast-b'));
    }

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