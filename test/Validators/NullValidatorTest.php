<?php

namespace League\Csv\Test\Validators;

use InvalidArgumentException;
use League\Csv\Writer;
use League\Csv\Exporter\Validators\NullValidator;
use PHPUnit_Framework_TestCase;
use SplFileObject;
use SplTempFileObject;

/**
 * @group validators
 */
class NullValidatorTest extends PHPUnit_Framework_TestCase
{
    private $csv;

    public function setUp()
    {
        $this->csv = Writer::createFromFileObject(new SplTempFileObject());
    }

    public function tearDown()
    {
        $csv = new SplFileObject(dirname(__DIR__).'/foo.csv', 'w');
        $csv->setCsvControl();
        $csv->fputcsv(["john", "doe", "john.doe@example.com"], ",", '"');
        $this->csv = null;
    }

    public function testInsertNullThrowsException()
    {
        $validator = new NullValidator();
        $validator_name = 'null_as_exception';
        $expected = ['john', null, 'john.doe@example.com'];
        $this->csv->addValidator($validator, $validator_name);
        try {
            $this->csv->insertOne($expected);
        } catch (InvalidArgumentException $e) {
            $this->assertSame($validator_name, $this->csv->getLastValidatorErrorName());
            $this->assertSame($expected, $this->csv->getLastValidatorErrorData());
        }
    }
}