<?php
declare(strict_types=1);

namespace Picktech\Analytics\Vendor;

use Bitrix\Main\ORM\Query\Filter\ConditionTree;
use Bitrix\Main\SystemException;
use Bitrix\Main\UI\PageNavigation;
use Picktech\Analytics\Data\AbstractReader;
use Picktech\Analytics\Entity\ProductTable;
use Picktech\Analytics\Entity\VendorTable;
use Picktech\Analytics\Vendor\VendorDto;
use Picktech\Analytics\VendorActionPrice\VendorActionPriceDto;
use Picktech\Analytics\VendorActionPrice\VendorActionPriceFilterDto;
use RuntimeException;
use spaceonfire\Collection\Collection;
use spaceonfire\Collection\CollectionInterface;
use spaceonfire\Collection\TypedCollection;

/**
 * Class VendorReader
 * @package Picktech\Analytics\Vendor
 */
final class VendorReader extends AbstractReader {
    public function __construct() {
    }


    /**
     * @param int $vendorId
     * @return VendorDto|null
     */
    public function readOne(int $vendorId): ?VendorDto
    {
        try {
            $params = [
                'select' => $this->getSelect(),
            ];

            $rawElement = VendorTable::getByPrimary(['ID' => $vendorId], $params)->fetch();

            if ($rawElement) {
                return $this->createDto([$rawElement]);
            }

            return null;

        } catch (SystemException $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }


    /**
     * @return array
     */
    private function getSelect(): array {
        return [
            'ID',
            'NAME'
        ];
    }


    /**
     * @param array $items
     *
     * @return VendorDto
     */
    private function createDto(array $items): VendorDto {
        $items = array_values($items);

        $firstItem = $items[0];

        $dto = new VendorDto();

        $dto->name  = $firstItem['NAME'];
        $dto->id   = $firstItem['ID'];

        return $dto;
    }
}
