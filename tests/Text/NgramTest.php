<?php
/**
 * Unit-tests for \Text\Ngram
 *
 * @category Text
 * @package  Text_Ngram
 * @author   Yuya Takeyama <sign.of.the.wolf.pentagram at gmail.com>
 */

/**
 * @namespace
 */
namespace Text;

require_once 'PHPUnit/Framework.php';
require_once __DIR__ . '/../../src/Text/Ngram.php';

/**
 * Test class for Ngram.
 */
class NgramTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Ngram
     */
    protected $ngram;

    protected function setUp()
    {
        $this->bigram  = new Ngram('こんにちは世界！', 2, 'UTF-8');
        $this->trigram = new Ngram('こんにちは世界！', 3, 'UTF-8');
    }

    public function testToArray()
    {
        $expectedBigram = array(
            'こん',
            'んに',
            'にち',
            'ちは',
            'は世',
            '世界',
            '界！'
        );
        $expectedTrigram = array(
            'こんに',
            'んにち',
            'にちは',
            'ちは世',
            'は世界',
            '世界！'
        );
        $this->assertEquals($expectedBigram, $this->bigram->toArray());
        $this->assertEquals($expectedTrigram, $this->trigram->toArray());
    }

    public function testRewind()
    {
        $this->bigram->next();
        $this->assertEquals(1, $this->bigram->key());
        $this->bigram->rewind();
        $this->assertEquals(0, $this->bigram->key());
    }

    public function testCurrent()
    {
        $this->assertEquals('こん', $this->bigram->current());
        $this->bigram->next();
        $this->assertEquals('んに', $this->bigram->current());
    }

    public function testValid()
    {
        $this->bigram->seek(6);
        $this->assertTrue($this->bigram->valid());
        $this->bigram->next();
        $this->assertFalse($this->bigram->valid());
    }

    public function testSeek()
    {
        $expected = 'んに';
        $this->bigram->seek(1);
        $this->assertEquals($expected, $this->bigram->current());
    }

    /**
     * @expectedException OutOfBoundsException
     */
    public function testSeekThrowsOutOfBoundsException()
    {
        $this->bigram->seek(7);
    }

    public function testCount()
    {
        $this->assertEquals(7, count($this->bigram));
        $this->assertEquals(6, count($this->trigram));
    }

    public function testOffsetExists()
    {
        $this->assertTrue($this->bigram->offsetExists(6));
        $this->assertTrue(isset($this->bigram[6]));
        $this->assertFalse($this->bigram->offsetExists(7));
        $this->assertFalse(isset($this->bigram[7]));
    }

    public function testOffsetGet()
    {
        $expected = 'こん';
        $this->assertEquals($expected, $this->bigram->offsetGet(0));
        $this->assertEquals($expected, $this->bigram[0]);
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testOffsetSet()
    {
        $this->bigram[0] = 'foo';
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testOffsetUnset()
    {
        unset($this->bigram[0]);
    }

    public function testGetIndexSize()
    {
        $this->assertEquals(2, $this->bigram->getIndexSize());
        $this->assertEquals(3, $this->trigram->getIndexSize());
    }

    public function testGetTextSize()
    {
        $this->assertEquals(8, $this->bigram->getTextSize());
        $this->assertEquals(8, $this->trigram->getTextSize());
    }

    public function test__toString()
    {
        $this->assertEquals('こん,んに,にち,ちは,は世,世界,界！', $this->bigram->__toString());
        $this->assertEquals('こんに,んにち,にちは,ちは世,は世界,世界！', $this->trigram->__toString());
    }
}
