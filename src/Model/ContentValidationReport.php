<?php


namespace Picqer\BolRetailer\Model;

/**
 * @property ContentValidationReportItem[] $productContents
 */
class ContentValidationReport extends AbstractModel
{
    public function getProductContents(): array
    {
        /** @var array<array-key, mixed> */
        $productContents = $this->data['productContents'] ?? [];

        return array_map(function (array $data): ContentValidationReportItem {
            return new ContentValidationReportItem($data);
        }, $productContents);
    }
}
