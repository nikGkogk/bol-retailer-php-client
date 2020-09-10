<?php


namespace Picqer\BolRetailer\Tests\Model;


use Picqer\BolRetailer\Model\ContentValidationReport;
use Picqer\BolRetailer\Model\ContentValidationReportItem;

class ContentValidationReportTest extends \PHPUnit\Framework\TestCase
{
    private $report;

    public function setup(): void
    {
        $this->report = new ContentValidationReport(
            json_decode(file_get_contents(__DIR__ . '/../Fixtures/json/content-validation-report.json'), true)
        );
    }

    public function testContainsProductContents()
    {
        $productContents = $this->report->productContents;
        $this->assertIsArray($productContents);
        $this->assertGreaterThan(0, count($productContents));
        $this->assertInstanceOf(ContentValidationReportItem::class, $productContents[0]);
    }
}
