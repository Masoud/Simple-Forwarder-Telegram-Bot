<?php

namespace Tests\DiDom;

use Tests\TestCase;
use DiDom\Document;
use DiDom\Query;

class SelectorTest extends TestCase
{
    public function testTag()
    {
        $html = '
            <ul id="first">
                <li><a href="#">Item 1</a></li>
                <li><a href="#">Item 2</a></li>
                <li><a href="#">Item 3</a></li>
            </ul>
            <ol id="second">
                <li><a href="#">Item 1</a></li>
                <li><a href="#">Item 2</a></li>
                <li><a href="#">Item 3</a></li>
            </ol>
        ';

        $document = new Document($html);

        $expected = ['Item 1', 'Item 2', 'Item 3', 'Item 1', 'Item 2', 'Item 3'];

        $result = [];

        foreach ($document->find('li') as $element) {
            $result[] = $element->text();
        }

        $this->assertEquals($expected, $result);
    }

    public function testNestedTag()
    {
        $html = '
            <ul id="first">
                <li><a href="#">Item 1</a></li>
                <li><a href="#">Item 2</a></li>
                <li><a href="#">Item 3</a></li>
            </ul>
            <ol id="second">
                <li><a href="#">Item 1</a></li>
                <li><a href="#">Item 2</a></li>
                <li><a href="#">Item 3</a></li>
            </ol>
        ';

        $document = new Document($html);

        $expected = ['Item 1', 'Item 2', 'Item 3'];

        $result = [];

        foreach ($document->find('ul a') as $element) {
            $result[] = $element->text();
        }

        $this->assertEquals($expected, $result);
    }

    public function testDirectChild()
    {
        $html = '
            <div>
                <p><span>Lorem ipsum.</span></p>
                <span>Lorem ipsum.</span>
            </div>
        ';

        $document = new Document($html);

        $expected = ['Lorem ipsum.'];

        $result = [];

        foreach ($document->find('div > span') as $element) {
            $result[] = $element->text();
        }

        $this->assertEquals($expected, $result);
    }

    public function testId()
    {
        $html = '
            <span>Lorem ipsum dolor.</span>
            <span id="second">Tenetur totam, nostrum.</span>
            <span>Iste, doloremque, praesentium.</span>
        ';

        $document = new Document($html);

        $expected = ['Tenetur totam, nostrum.'];

        $result = [];

        foreach ($document->find('#second') as $element) {
            $result[] = $element->text();
        }

        $this->assertEquals($expected, $result);
    }

    public function testClass()
    {
        $html = '
            <span class="odd first">Lorem ipsum dolor.</span>
            <span class="even second">Tenetur totam, nostrum.</span>
            <span class="odd third">Iste, doloremque, praesentium.</span>
        ';

        $document = new Document($html);

        $expected = ['Lorem ipsum dolor.', 'Iste, doloremque, praesentium.'];

        $result = [];

        foreach ($document->find('.odd') as $element) {
            $result[] = $element->text();
        }

        $this->assertEquals($expected, $result);

        $expected = ['Iste, doloremque, praesentium.'];

        $result = [];

        foreach ($document->find('.odd.third') as $element) {
            $result[] = $element->text();
        }

        $this->assertEquals($expected, $result);
    }

    public function testAttributes()
    {
        $html = '
            <ul class="links">
                <li>
                    <a href="https://foo.com" title="Foo" target="_blank">Foo</a>
                    <a href="http://bar.com" title="Bar" rel="noreferrer">Bar</a>
                    <a href="https://baz.org" title="Baz" rel="nofollow noreferrer">Baz</a>
                    <a href="http://qux.org" title="Qux" target="_blank" rel="nofollow">Qux</a>
                </li>
            </ul>
        ';

        $document = new Document($html);

        // has attribute

        $expected = ['Foo', 'Qux'];

        $result = [];

        foreach ($document->find('a[target]') as $element) {
            $result[] = $element->text();
        }

        $this->assertEquals($expected, $result);

        // has no attribute

        $expected = ['Bar', 'Baz'];

        $result = [];

        foreach ($document->find('a[!target]') as $element) {
            $result[] = $element->text();
        }

        $this->assertEquals($expected, $result);

        // equals

        $expected = ['Baz'];

        $result = [];

        foreach ($document->find('a[href="https://baz.org"]') as $element) {
            $result[] = $element->text();
        }

        $this->assertEquals($expected, $result);

        // not equals

        $expected = ['Foo', 'Bar', 'Qux'];

        $result = [];

        foreach ($document->find('a[href!="https://baz.org"]') as $element) {
            $result[] = $element->text();
        }

        $this->assertEquals($expected, $result);

        // starts with

        $expected = ['Foo', 'Baz'];

        $result = [];

        foreach ($document->find('a[href^="https"]') as $element) {
            $result[] = $element->text();
        }

        // ends with

        $expected = ['Baz', 'Qux'];

        $result = [];

        foreach ($document->find('a[href$="org"]') as $element) {
            $result[] = $element->text();
        }

        $this->assertEquals($expected, $result);

        // contains word

        $expected = ['Bar', 'Baz'];

        $result = [];

        foreach ($document->find('a[rel~="noreferrer"]') as $element) {
            $result[] = $element->text();
        }

        $this->assertEquals($expected, $result);
        $this->assertEquals([], $document->find('a[rel~="noref"]'));

        // contains substring

        $expected = ['Bar', 'Baz'];

        $result = [];

        foreach ($document->find('a[href*="ba"]') as $element) {
            $result[] = $element->text();
        }

        $this->assertEquals($expected, $result);

        // multiple attribute conditions

        $expected = ['Qux'];

        $result = [];

        foreach ($document->find('a[target="_blank"][rel="nofollow"]') as $element) {
            $result[] = $element->text();
        }

        $this->assertEquals($expected, $result);
    }
}