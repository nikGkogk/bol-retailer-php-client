<?php


namespace Picqer\BolRetailer\Tests\Model;

use Picqer\BolRetailer\Model\ContentValidationReportItem;

class ContentValidationReportItemTest extends \PHPUnit\Framework\TestCase
{
    private $reportItem;

    public function setup(): void
    {
        $this->reportItem = new ContentValidationReportItem(
            json_decode(file_get_contents(__DIR__ . '/../Fixtures/json/content-validation-report-item.json'), true)
        );
    }

    public function testContainsInternalReference()
    {
        $this->assertEquals('USER-REFERENCE', $this->reportItem->internalReference);
    }

    public function testContainsRejectedAttributes()
    {
        $this->assertIsArray($this->reportItem->rejectedAttributes);
    }

    public function testContainsStatus()
    {
        $this->assertEquals('VALIDATED_WITH_ATTRIBUTE_FAILURES', $this->reportItem->status);
    }

    public function testContainsErrorCode()
    {
        $this->assertEquals(1000, $this->reportItem->errorCode);
    }

    public function testContainsErrorDescription()
    {
        $this->assertEquals('Example rejection message.', $this->reportItem->errorDescription);
    }
}
