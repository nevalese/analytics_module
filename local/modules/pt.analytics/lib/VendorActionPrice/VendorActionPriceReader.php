<?php
declare(strict_types=1);

namespace Picktech\Analytics\VendorActionPrice;

use Bitrix\Catalog\Model\Product;
use Bitrix\Main\ORM\Query\Filter\ConditionTree;
use Bitrix\Main\SystemException;
use Bitrix\Main\UI\PageNavigation;
use Picktech\Analytics\Data\AbstractReader;
use Picktech\Analytics\Data\Paginator;
use Picktech\Analytics\Entity\VendorActionPriceTable;
use Picktech\Analytics\Product\ProductDto;
use Picktech\Analytics\Product\ProductFilterDto;
use Picktech\Analytics\Product\ProductReader;
use Picktech\Analytics\Vendor\VendorDto;
use Picktech\Analytics\Vendor\VendorReader;
use Picktech\Analytics\VendorActionPrice\VendorActionPriceDto;
use Picktech\Analytics\VendorActionPrice\VendorActionPriceFilterDto;
use RuntimeException;
use spaceonfire\Collection\Collection;
use spaceonfire\Collection\CollectionInterface;
use spaceonfire\Collection\TypedCollection;

/**
 * Class VendorActionPriceReader
 * @package Picktech\Analytics\VendorActionPrice
 */
final class VendorActionPriceReader extends AbstractReader {
    public function __construct() {
    }



    /**
     * @param int $vendorPriceActionId
     * @return VendorActionPriceDto|null
     */
    public function readOne(int $vendorPriceActionId): ?VendorActionPriceDto
    {
        try {
            $params = [
                'select' => $this->getSelect(),
            ];

            $rawElement = VendorActionPriceTable::getByPrimary(['ID' => $vendorPriceActionId], $params)->fetch();

            if ($rawElement) {
                return $this->createDto([$rawElement]);
            }

            return null;

        } catch (SystemException $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }


    /**
     * @param VendorActionPriceDto $filter
     * @param PageNavigation       $pageNavigation
     *
     * @return CollectionInterface
     */
    public function readAll(VendorActionPriceFilterDto $filter, PageNavigation $pageNavigation): CollectionInterface {
        try {

            $condition = $this->buildCondition($filter);
            $orderBy   = $this->getOrder();

            $pageNavigation->setRecordCount(VendorActionPriceTable::getCount($condition));
            if ($pageNavigation->getRecordCount() === 0) {
                return $this->createCollection();
            }

            $ids = VendorActionPriceTable::getList(
                [
                    'select' => ['ID'],
                    'filter' => $condition,
                    'order'  => $orderBy,
                    'limit'  => (int)$pageNavigation->getLimit(),
                    'offset' => (int)$pageNavigation->getOffset(),
                ]
            )->fetchAll();
            $ids = array_column($ids, 'ID');

            $items = [];
            if ($ids) {
                $rawItems = VendorActionPriceTable::getList(
                    [
                        'select' => $this->getSelect(),
                        'filter' => (new ConditionTree())->whereIn('ID', $ids),
                        'order'  => $orderBy,
                    ]
                )->fetchAll();

                $items = (new Collection($rawItems))
                    ->groupBy('ID')
                    ->map(
                        function (CollectionInterface $items) {
                            return $this->createDto($items->all());
                        }
                    );
            }

            return $this->createCollection($items);
        } catch (SystemException $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param VendorActionPriceFilterDto $filter
     *
     * @return ConditionTree
     */
    private function buildCondition(VendorActionPriceFilterDto $filter): ConditionTree {
        $condition = new ConditionTree();

        if (!empty($filter->vendorId)) {
            if (is_array($filter->vendorId)) {
                $condition->whereIn('UF_VENDOR_ID', $filter->vendorId);
            } else {
                $condition->where('UF_VENDOR_ID', $filter->vendorId);
            }
        }

        if (!empty($filter->productId)) {
            if (is_array($filter->productId)) {
                $condition->whereIn('UF_PRODUCT_ID', $filter->productId);
            } else {
                $condition->where('UF_PRODUCT_ID', $filter->productId);
            }
        }

        return $condition;
    }

    /**
     * @param array $items
     *
     * @return CollectionInterface
     */
    private function createCollection($items = []): CollectionInterface {
        return new TypedCollection($items, VendorActionPriceDto::class);
    }

    /**
     * @return array
     */
    private function getSelect(): array {
        return [
            'ID',
            'PRODUCT_ID'  => 'UF_PRODUCT_ID',
            'VENDOR_ID'   => 'UF_VENDOR_ID',
            'PRICE_CLICK' => 'UF_PRICE_CLICK',
            'PRICE_VIEW'  => 'UF_PRICE_VIEW',
            'BUDGET'      => 'UF_BUDGET',
            'DATE_START'  => 'UF_DATETIME_START',
            'STATUS'      => 'UF_STATUS'
        ];
    }

    /**
     * @return array
     */
    private function getOrder(): array {
        return [
            'UF_VENDOR_ID' => 'ASC',
        ];
    }


    private function getProductName(int $productId): ProductDto {
        $productReader = new ProductReader();
        return $productReader->readOne( $productId);
    }

    private function getVendorName(int $vendorId): VendorDto {
        $vendorReader = new VendorReader();
        return $vendorReader->readOne($vendorId);
    }




    /**
     * @param array $items
     *
     * @return VendorActionPriceDto
     */
    private function createDto(array $items): VendorActionPriceDto {
        $items = array_values($items);

        $firstItem = $items[0];

        $dto = new VendorActionPriceDto();
        $dto->id  =  $firstItem['ID'];
        $dto->productId    =  $firstItem['PRODUCT_ID'];
        $dto->vendorId     =  $firstItem['VENDOR_ID'];
        $dto->vendorName   =  $this->getVendorName((int)$firstItem['VENDOR_ID'])->name;
        $dto->priceClick   =  $firstItem['PRICE_CLICK'];
        $dto->priceView    =  $firstItem['PRICE_VIEW'];
        $dto->status       =  $firstItem['STATUS'];
        $dto->budget       =  $firstItem['BUDGET'];
        $dto->dateStart    =  $firstItem['DATE_START'];

        return $dto;
    }
}
