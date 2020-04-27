<?php

namespace App\Tests\Utils;

use App\Utils\HTMLCleaner;
use PHPUnit\Framework\TestCase;

class HTMLCleanerTest extends TestCase
{
    public function testSanitizeNullStringsWillReturnNull()
    {
        $this->assertNull(HTMLCleaner::sanitizeHtmlEncoding(null));
        $this->assertNull(HTMLCleaner::sanitizeHtmlTags(null));
        $this->assertNull(HTMLCleaner::sanitizeHtmlAttributes(null));
        $this->assertNull(HTMLCleaner::sanitizeHtmlNewLines(null));
        $this->assertNull(HTMLCleaner::sanitizeOpenXML(null));
    }

    public function testSanitizeEncodingWillUnencode()
    {
        $this->assertEquals('&', HTMLCleaner::sanitizeHtmlEncoding('&amp;'));
        $this->assertEquals('é', HTMLCleaner::sanitizeHtmlEncoding('&amp;eacute;'));
        $this->assertEquals('Λοκ[ρος]', HTMLCleaner::sanitizeHtmlEncoding('&Lambda;&omicron;&kappa;[&rho;&omicron;&sigmaf;]'));
        $this->assertEquals('Λοκ[ρος]', HTMLCleaner::sanitizeHtmlEncoding('Λοκ[ρος]'));
    }


    public function testSanitizeTagsWillKeepAllowedTags()
    {
        $allowedTags = [
            'strong',
            'em',
            'u',
            's',
            'sub',
            'sup',
            'span',
        ];
        foreach ($allowedTags as $tag) {
            $before = sprintf('Foo <%s attr="value">Bar</%s> Baz', $tag, $tag);
            $this->assertEquals($before, HTMLCleaner::sanitizeHtmlTags($before));
        }
        // Special cases for <br>
        $this->assertEquals("Foo<br>Bar", HTMLCleaner::sanitizeHtmlTags("Foo<br>Bar"));
        $this->assertEquals("Foo<br >Bar", HTMLCleaner::sanitizeHtmlTags("Foo<br >Bar"));
        $this->assertEquals("Foo<br/>Bar", HTMLCleaner::sanitizeHtmlTags("Foo<br/>Bar"));
        $this->assertEquals("Foo<br />Bar", HTMLCleaner::sanitizeHtmlTags("Foo<br />Bar"));
    }

    public function testSanitizeTagsWillRemoveForbiddenTags()
    {
        $removedTags = [
            'a',
            'div',
            'p'
        ];
        foreach ($removedTags as $tag) {
            $before = sprintf('Foo <%s attr="value">Bar</%s> Baz', $tag, $tag);
            $this->assertEquals(strip_tags($before), HTMLCleaner::sanitizeHtmlTags($before));
        }
    }

    public function testSanitizeAttributesKeepsGreekFontFamily()
    {
        $this->assertEquals(
            'Foo',
            HTMLCleaner::sanitizeHtmlAttributes('Foo')
        );
        $this->assertEquals(
            'Foo <span style="font-family:ifaogreek">Bar</span> Baz',
            HTMLCleaner::sanitizeHtmlAttributes('Foo <span style="font-family: IFAOGreek;">Bar</span> Baz')
        );
        $this->assertEquals(
            'Foo <span style="font-family:ifaogreek">Bar</span> Baz',
            HTMLCleaner::sanitizeHtmlAttributes('Foo <span style="font-family:IFAOGreek;">Bar</span> Baz')
        );
        $this->assertEquals(
            'Foo <span style="font-family:ifaogreek">Bar</span> Baz',
            HTMLCleaner::sanitizeHtmlAttributes('Foo <span style="font-family:IFAOGreek">Bar</span> Baz')
        );
        $this->assertEquals(
            'Foo <span style="font-family:ifaogreek">Bar</span> Baz',
            HTMLCleaner::sanitizeHtmlAttributes('Foo <span style="font-family:IFAOGreek;background-color: rgb(211, 211, 211);">Bar</span> Baz')
        );
    }

    public function testSanitizeAttributesRemovesEmptySpans()
    {
        $this->assertEquals(
            'Foo',
            HTMLCleaner::sanitizeHtmlAttributes('Foo')
        );
        $this->assertEquals(
            'Foo Bar Baz',
            HTMLCleaner::sanitizeHtmlAttributes('Foo <span style="background-color: rgb(211, 211, 211);">Bar</span> Baz')
        );
    }

    public function testSanitizeNewLinesWillRemoveParagraphsAndBlocks()
    {
        $this->assertEquals(
            'Foo',
            HTMLCleaner::sanitizeHtmlNewLines('Foo')
        );
        $this->assertEquals(
            'Foo<br/>Bar',
            HTMLCleaner::sanitizeHtmlNewLines('Foo<br/>Bar')
        );
        $this->assertEquals(
            'Foo<br/>Bar',
            HTMLCleaner::sanitizeHtmlNewLines('<p>Foo</p><p>Bar</p>')
        );
        $this->assertEquals(
            'Foo<br/>Bar',
            HTMLCleaner::sanitizeHtmlNewLines('<div>Foo</div><div>Bar</div>')
        );
        $this->assertEquals(
            'Foo<br/>Bar<br/>Baz<br/>Qux',
            HTMLCleaner::sanitizeHtmlNewLines('<p>Foo</p>Bar<div>Baz</div>Qux')
        );
    }

    public function testSanitizeNewLinesWillRemoveEmptyLines()
    {
        $this->assertEquals(
            'Foo',
            HTMLCleaner::sanitizeHtmlNewLines('Foo')
        );
        $this->assertEquals(
            'Foo<br/>Bar',
            HTMLCleaner::sanitizeHtmlNewLines('Foo<br/>Bar')
        );
        $this->assertEquals(
            'Foo<br/>Bar',
            HTMLCleaner::sanitizeHtmlNewLines('<p>Foo<br/></p><br/> <br/> <br/> <p><br/>Bar</p>')
        );
    }

    public function testSanitizeOpenXMLWillRemoveFragments()
    {
        $this->assertEquals(
            'Foo',
            HTMLCleaner::sanitizeHtmlNewLines('Foo')
        );
        $this->assertEquals(
            'Foo<br/>Bar',
            HTMLCleaner::sanitizeHtmlNewLines('Foo<br/>Bar')
        );
        $this->assertEquals(
            'Foo',
            HTMLCleaner::sanitizeOpenXML('<!--StartFragment-->Foo<!--EndFragment-->')
        );
    }
}
