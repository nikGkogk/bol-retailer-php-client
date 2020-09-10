<?php


namespace Picqer\BolRetailer\Tests;

use GuzzleHttp\Psr7;
use GuzzleHttp\ClientInterface;
use Picqer\BolRetailer\Client;
use Picqer\BolRetailer\Content;
use Picqer\BolRetailer\Model\ContentValidationReport;
use Picqer\BolRetailer\ProcessStatus;

class ContentTest extends \PHPUnit\Framework\TestCase
{
    private $http;

    public function setup(): void
    {
        $this->http = $this->prophesize(ClientInterface::class);

        Client::setHttp($this->http->reveal());
    }

    public function testCreate()
    {
        $response = Psr7\parse_response(file_get_contents(__DIR__ . '/Fixtures/http/202-process-status'));

        $data = [
            "language" => "nl",
            "productContents" => [
                [
                    "internalReference" => "USER-REFERENCE",
                    "attributes" => [
                        [
                            "id" => "Width",
                            "values" => [
                                [
                                    "value" => "14.5",
                                    "unitId" => "mm"
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $this->http
            ->request('POST', 'content/product', [ 'body' => json_encode($data) ])
            ->willReturn($response);

        $processStatus = Content::create($data);

        $this->assertInstanceOf(ProcessStatus::class, $processStatus);
        $this->assertTrue($processStatus->isPending);
    }

    public function testGetValidationReport()
    {
        $response = Psr7\parse_response(file_get_contents(__DIR__ . '/Fixtures/http/200-content-validation-report'));
        $uploadId = '6ff736b5-cdd0-4150-8c67-78269ee986f5';

        $this->http
            ->request('GET', 'content/validation-report/' . $uploadId, [])
            ->willReturn($response);

        $contentValidationReport = Content::getValidationReport($uploadId);

        $this->assertInstanceOf(ContentValidationReport::class, $contentValidationReport);
    }
}
