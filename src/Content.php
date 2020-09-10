<?php


namespace Picqer\BolRetailer;


use GuzzleHttp\Exception\ClientException;
use Picqer\BolRetailer\Exception\HttpException;
use Picqer\BolRetailer\Exception\RateLimitException;
use Picqer\BolRetailer\Exception\ShipmentNotFoundException;
use Picqer\BolRetailer\Model\ContentValidationReport;

class Content extends Model\Content
{
    /**
     * Create content for existing products or new products.
     *
     * @param array $data The data of the content to create.
     *
     * @return ProcessStatus
     */
    public static function create(array $data): ProcessStatus
    {
        try {
            $response = Client::request('POST', "content/product", ['body' => json_encode($data)]);
        } catch (ClientException $e) {
            static::handleException($e);
        }

        return new ProcessStatus(json_decode((string)$response->getBody(), true));
    }

    public static function getValidationReport(string $uploadId): ?ContentValidationReport
    {
        try {
            $response = Client::request('GET', "content/validation-report/${uploadId}");
        } catch (ClientException $e) {
            static::handleException($e);
        }

        return new ContentValidationReport(json_decode((string)$response->getBody(), true));
    }

    private static function handleException(ClientException $e): void
    {
        $response = $e->getResponse();

        if ($response && $response->getStatusCode() === 404) {
            throw new ShipmentNotFoundException(
                json_decode((string)$response->getBody(), true),
                404,
                $e
            );
        } elseif ($response && $response->getStatusCode() === 429) {
            throw new RateLimitException(
                json_decode((string)$response->getBody(), true),
                429,
                $e
            );
        } elseif ($response) {
            throw new HttpException(
                json_decode((string)$response->getBody(), true),
                $response->getStatusCode(),
                $e
            );
        }

        throw $e;
    }
}
