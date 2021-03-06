<?php

namespace LeagueTest\Csv;

use League\Csv\CharsetConverter;
use League\Csv\Exception\RuntimeException;
use League\Csv\JsonConverter;
use League\Csv\Reader;
use League\Csv\Statement;
use PHPUnit\Framework\TestCase;

/**
 * @group converter
 * @coversDefaultClass League\Csv\JsonConverter
 */
class JsonConverterTest extends TestCase
{
    private $records;

    private $converter;

    public function setUp()
    {
        $csv = Reader::createFromPath(__DIR__.'/data/prenoms.csv', 'r')
            ->setDelimiter(';')
            ->setHeaderOffset(0)
        ;

        $stmt = (new Statement())
            ->offset(3)
            ->limit(5)
        ;

        $this->records = $stmt->process($csv);
        $this->converter = new JsonConverter();
    }

    public function tearDown()
    {
        $this->records = null;
        $this->converter = null;
    }

    /**
     * @covers ::options
     * @covers ::convert
     */
    public function testToJson()
    {
        $charset_converter = (new CharsetConverter())->inputEncoding('iso-8859-15');
        $records = $charset_converter->convert($this->records);
        $this->assertContains('[{', $this->converter->options(JSON_HEX_QUOT)->convert($records));
    }

    /**
     * @covers ::convert
     */
    public function testJsonEncodingThrowsException()
    {
        $this->expectException(RuntimeException::class);
        $this->converter->convert($this->records);
    }
}
