<?php
declare(strict_types=1);

namespace Picktech\Analytics\Product;

use Bitrix\Main\ORM\Query\Filter\ConditionTree;
use Bitrix\Main\SystemException;
use Bitrix\Main\UI\PageNavigation;
use Picktech\Analytics\Data\AbstractReader;
use Picktech\Analytics\Data\Paginator;
use Picktech\Analytics\Entity\ProductTable;
use Picktech\Analytics\VendorActionPrice\VendorActionPriceFilterDto;
use Picktech\Analytics\VendorActionPrice\VendorActionPriceReader;
use RuntimeException;
use spaceonfire\Collection\Collection;
use spaceonfire\Collection\CollectionInterface;
use spaceonfire\Collection\TypedCollection;

/**
 * Class ProductReader
 * @package Picktech\Analytics\Product
 */
final class ProductReader extends AbstractReader {
    public function __construct() {
    }

    /**
     * @param int $productId
     *
     * @return ProductDto|null
     */
    public function readOne(int $productId): ?ProductDto {
        try {
            $params = [
                'select' => $this->getSelect()
            ];

            $rawElement = ProductTable::getByPrimary(['ID' => $productId], $params)->fetch();

            if ($rawElement) {
                return $this->createDto([$rawElement]);
            }

            return null;
        } catch (SystemException $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param ProductFilterDto $filter
     * @param PageNavigation   $pageNavigation
     *
     * @return CollectionInterface
     */
    public function readAll(
        ProductFilterDto $filter,
        PageNavigation $pageNavigation
    ): CollectionInterface {
        try {
            $condition = $this->buildCondition($filter);

            $pageNavigation->setRecordCount(ProductTable::getCount($condition));
            if ($pageNavigation->getRecordCount() === 0) {
                return $this->createCollection();
            }

            $rawItems = ProductTable::getList(
                [
                    'select' => $this->getSelect(),
                    'filter' => $condition,
                    'order'  => $this->getOrder('NAME', 'DESC'),
                    'limit'  => (int)$pageNavigation->getLimit(),
                    'offset' => (int)$pageNavigation->getOffset(),
                ]
            );
            $items    = (new Collection($rawItems->fetchAll()))
                ->groupBy('ID')
                ->map(
                    function (CollectionInterface $items) {
                        return $this->createDto($items->all());
                    }
                );
            return $this->createCollection($items);
        } catch (SystemException $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function readAllwithStat(
        ProductFilterDto $filter,
        PageNavigation $pageNavigation,
        string $sort = 'NAME',
        string $sortDirection = 'ASC',
        $dateFrom = false,
        $dateTo = false
    ): CollectionInterface {
        try {
            $condition = $this->buildCondition($filter);
            $orderBy   = $this->getOrder($sort, $sortDirection);
            $pageNavigation->setPageSize(20);

            if ($dateFrom && $dateTo) {
                $statisticReader = new \Picktech\Analytics\Statistic\StatisticReader();
                $statisticFilter = new \Picktech\Analytics\Statistic\StatisticFilterDto();
                //  $statisticFilter->dateFrom = $dateFrom;
                //  $statisticFilter->dateTo   = $dateTo;
                $ids = array_column(
                    $statisticReader->readProductId($statisticFilter),
                    'PRODUCT_ID'
                );
                if (count($ids) > 0) {
                    $condition = new ConditionTree();
                    $condition->whereIn('ID', $ids);
                    $pageNavigation->setRecordCount(ProductTable::getCount($condition));
                } else return $this->createCollection();
            } else {
                $pageNavigation->setRecordCount(ProductTable::getCount($condition));
                if ($pageNavigation->getRecordCount() === 0) {
                    return $this->createCollection();
                }
            }
            $rawItems = ProductTable::getList(
                [
                    'select'  => $this->getStatSelect(),
                    'filter'  => $condition,
                    'order'   => $orderBy,
                    'limit'   => (int)$pageNavigation->getLimit(),
                    'offset'  => (int)$pageNavigation->getOffset(),
                    'runtime' => [
                        'DAILY' => [
                            'data_type' => 'Picktech\Analytics\Entity\DailyStatisticTable',
                            'reference' => [
                                '=this.ID'     => 'ref.UF_PRODUCT_ID',
                                '<ref.UF_DATE' => new \Bitrix\Main\DB\SqlExpression('?', $dateTo->format('Y-m-d')),
                                '>ref.UF_DATE' => new \Bitrix\Main\DB\SqlExpression('?', $dateFrom->format('Y-m-d'))
                            ],
                            'join_type' => 'left'
                        ],
                    ]
                ]
            );
            $items    = (new Collection($rawItems->fetchAll()))
                ->groupBy('ID')
                ->map(
                    function (CollectionInterface $items) {
                        return $this->createDto($items->all());
                    }
                );
            return $this->createCollection($items);
        } catch (SystemException $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param ProductFilterDto $filter
     *
     * @return ConditionTree
     */
    private function buildCondition(ProductFilterDto $filter): ConditionTree {
        $condition = new ConditionTree();

        if (!empty($filter->vendorId)) {
            if (is_array($filter->vendorId)) {
                $condition->whereIn('PROPERTY_SIMPLE.VENDOR', $filter->vendorId);
            } else {
                $condition->where('PROPERTY_SIMPLE.VENDOR', $filter->vendorId);
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
        return new TypedCollection($items, ProductDto::class);
    }

    /**
     * @return array
     */
    private function getStatSelect(): array {
        return [
            'ID',
            'NAME',
            'CODE',
            'IS_PARTNER' => 'PROPERTY_SIMPLE.IS_PARTNER',
            'VENDOR'     => 'PROPERTY_SIMPLE.VENDOR',
            'LOGO'       => 'PROPERTY_SIMPLE.LOGO_URL',
            'VIEW'       => 'DAILY.UF_VIEWS',
            'SHOW'       => 'DAILY.UF_SHOWS',
            'CLICK'      => 'DAILY.UF_CLICKS',
        ];
    }


    private function getSelect(): array {
        return [
            'ID',
            'NAME',
            'CODE',
            'IS_PARTNER' => 'PROPERTY_SIMPLE.IS_PARTNER',
            'VENDOR'     => 'PROPERTY_SIMPLE.VENDOR',
            'LOGO'       => 'PROPERTY_SIMPLE.LOGO_URL',
        ];
    }

    /**
     * @return array
     */
    private function getOrder($sort, $sortDirection): array {
        return [
            $sort => $sortDirection,
        ];
    }

    function checkPriceAction(int $productId, int $vendorId) {
        $vendorActionPriceReader            = new VendorActionPriceReader();
        $vendorActionPriceFilter            = new VendorActionPriceFilterDto();
        $vendorActionPriceFilter->productId = $productId;
        $vendorActionPriceFilter->vendorId  = $vendorId;
        $vendorActionPrice                  = $vendorActionPriceReader->readAll(
            $vendorActionPriceFilter,
            (new Paginator(
                'price_list'
            ))->setPageSize(1)
        );

        return $vendorActionPrice;
    }

    /**
     * @param array $items
     *
     * @return ProductDto
     */
    private function createDto(array $items): ProductDto {
        $items = array_values($items);

        $firstItem = $items[0];

        $dto = new ProductDto();

        $dto->name   = $firstItem['NAME'];
        $dto->id     = $firstItem['ID'];
        $dto->code   = $firstItem['CODE'];
        $dto->vendor = $firstItem['VENDOR'];
        $dto->logo   = \CFile::GetPath($firstItem['LOGO']);
        $dto->views  = $firstItem['VIEW'];
        $dto->clicks = $firstItem['CLICK'];
        $dto->shows  = $firstItem['SHOW'];

        $dto->isPaid = (int)$firstItem['IS_PARTNER'] === 1;

        $pricesList = $this->checkPriceAction((int)$firstItem['ID'], (int)$firstItem['VENDOR']);

        if (count($pricesList) > 0) {
            $dto->isPriceConfirmed = true;
            $dto->priceClick       = $pricesList->first()->priceClick;
            $dto->priceView        = $pricesList->first()->priceView;
            $dto->budget           = $pricesList->first()->budget;
            $dto->dateStart        = $pricesList->first()->dateStart;
            $dto->status           = $pricesList->first()->status;
            $dto->promotionId      = $pricesList->first()->id;
        } else {
            $dto->isPriceConfirmed = false;
        }

        return $dto;
    }
}
