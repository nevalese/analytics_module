<?php
declare(strict_types=1);

namespace Picktech\Analytics\Statistic;

use Bitrix\Iblock\ElementTable;
use Bitrix\Main\Application;
use Bitrix\Main\Entity\ExpressionField;
use Bitrix\Main\ORM\Query\Filter\ConditionTree;
use Bitrix\Main\SystemException;
use Bitrix\Main\UI\PageNavigation;
use Picktech\Analytics\Data\AbstractReader;
use Picktech\Analytics\Entity\AnalyticsTable;
use RuntimeException;
use spaceonfire\Collection\Collection;
use spaceonfire\Collection\CollectionInterface;
use spaceonfire\Collection\TypedCollection;

/**
 * Class StatisticReader
 * @package Picktech\Analytics\Statistic
 */
final class StatisticReader extends AbstractReader {
    public function __construct() {
    }

    /**
     * @param StatisticFilterDto $filter
     *
     * @return CollectionInterface
     */
    public function readAll(StatisticFilterDto $filter): CollectionInterface {
        try {
            Application::getConnection()->startTracker();
            if (!empty($filter->productId) && is_array($filter->productId)) {
                 $productsId =  $filter->productId;
                foreach ($productsId as $key => $productId) {

                    $filter->productId = $productId;

                    $condition = $this->buildCondition($filter);
                    $orderBy   = $this->getOrder();

                    $countViewItems = AnalyticsTable::getCount($condition->where('UF_TYPE_ACTION', 'VIEW'));

                    $condition = $this->buildCondition($filter);

                    $countShowItems = AnalyticsTable::getCount($condition->where('UF_TYPE_ACTION', 'SHOW'));

                    $condition     = $this->buildCondition($filter);
                    $countClickItems = AnalyticsTable::getCount($condition->whereIn('UF_TYPE_ACTION', ['CLICK_SITE', 'CLICK_TRIAL']));

                    $product = ElementTable::getById($filter->productId)->fetch();
                    $rawItems[$productId]['PRODUCT_NAME']       = $product['NAME'];
                    $rawItems[$productId]['PRODUCT_ID'] = $filter->productId;
                    $rawItems[$productId]['DATE_FROM']  = $filter->dateFrom;
                    $rawItems[$productId]['DATE_TO']    = $filter->dateTo;
                    $rawItems[$productId]['VIEWS']      = $countViewItems;
                    $rawItems[$productId]['SHOWS']      = $countShowItems;
                    $rawItems[$productId]['CLICKS']     = $countClickItems;
                }

                $items = (new Collection($rawItems))
                    ->groupBy('PRODUCT_ID')
                    ->map(
                        function (CollectionInterface $items) {
                            return $this->createDto($items->all());
                        }
                    );

                return $this->createCollection($items);
            }
            else{
                return $this->createCollection();
            }
        } catch (SystemException $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param StatisticFilterDto $filter
     *
     * @return int
     */


    public function readCountActionByDate(StatisticFilterDto $filter, $cacheTime= 3600): int {

        try {
            $condition = $this->buildCondition($filter);
            return (int) AnalyticsTable::getCount($condition, ["ttl"=>$cacheTime]);
          } catch (SystemException $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);

        }
    }

    /**
     * @param string             $by
     * @param StatisticFilterDto $filter
     *
     * @return array
     */


    public function readCountActionsGroupBy(string $by, StatisticFilterDto $filter, $cacheTime = 3600): array{

        try {
            $condition = $this->buildCondition($filter);
            if ($by === 'day'){
                $dateExpression =  '%%Y-%%m-%%d';
            }
            elseif ($by === 'month'){
                $dateExpression =  '%%Y-%%m';
            }
            elseif ($by === 'year'){
                $dateExpression =  '%%Y';
            }
            else{
                $dateExpression =  '%%Y-%%m-%%d';
            }

            $rawItems = AnalyticsTable::getList(
                [
                    'select' => ['CNT', 'DATE'],
                    'filter' => $condition,
                    'group' => ['DATE'],
                    'runtime' =>[
                            new ExpressionField('CNT', 'COUNT(*)'),
                            new  ExpressionField('DATE', "date_format(UF_DATETIME_ACTION, '".$dateExpression."')"),
                         ],
                    "cache"=>["ttl"=>$cacheTime]
                ]
            );

            return $rawItems->fetchAll();

        } catch (SystemException $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);

        }

    }

    public function readProductId(StatisticFilterDto $filter){
        try {
            $condition = $this->buildCondition($filter);
            $rawItems = AnalyticsTable::getList(
                [
                    'select' => ['PRODUCT_ID'],
                    'filter' => $condition,
                    'runtime' =>[
                        new ExpressionField('PRODUCT_ID', 'DISTINCT %s', ['UF_PRODUCT_ID'])
                    ]
                ]
            );

            return $rawItems->fetchAll();

        } catch (SystemException $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }








    public function readCountActionsGroupByProduct( StatisticFilterDto $filter, $cacheTime = 3600){
        try {
            $condition = $this->buildCondition($filter);

            $rawItems = AnalyticsTable::getList(
                [
                    'select' => ['CNT', 'UF_PRODUCT_ID'],
                    'filter' => $condition,
                    'group' => ['UF_PRODUCT_ID'],
                    'runtime' =>[
                        new ExpressionField('CNT', 'COUNT(*)'),
                    ],
                    "cache"=>["ttl"=>$cacheTime]
                ]
            );

            return $rawItems->fetchAll();

        } catch (SystemException $e) {
            throw new RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }






    /**
     * @param StatisticFilterDto $filter
     *
     * @return ConditionTree
     */
    private function buildCondition(StatisticFilterDto $filter): ConditionTree {
        $condition = new ConditionTree();

        if (!empty($filter->dateFrom) && !empty($filter->dateTo)) {
            $condition->whereBetween('UF_DATETIME_ACTION', $filter->dateFrom,  $filter->dateTo);
        }

        if (!empty($filter->typeAction)) {
            if (is_array($filter->typeAction)) {
                $condition->whereIn('UF_TYPE_ACTION', $filter->typeAction);
            } else {
                $condition->where('UF_TYPE_ACTION', $filter->typeAction);
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
        return new TypedCollection($items, StatisticDto::class);
    }

    /**
     * @return array
     */
    private function getSelect(): array {
        return [
            'DATETIME'   => 'UF_DATETIME_ACTION',
            'PRODUCT_ID' => 'UF_PRODUCT_ID',
            'REFER'      => 'UF_REFER_URL',
            'ACTION'     => 'UF_TYPE_ACTION',
        ];
    }

    /**
     * @return array
     */
    private function getOrder(): array {
        return [
            'DATETIME' => 'ASC',
        ];
    }

    /**
     * @param array $items
     *
     * @return StatisticDto
     */
    private function createDto(array $items): StatisticDto {
        $items = array_values($items);

        $firstItem = $items[0];

        $dto = new StatisticDto();

        $dto->productId                 = $firstItem['PRODUCT_ID'];
        $dto->productName               = $firstItem['PRODUCT_NAME'];
        $dto->clicks                    = $firstItem['CLICKS'];
        $dto->shows                     = $firstItem['SHOWS'];
        $dto->views                     = $firstItem['VIEWS'];
        $dto->conversionInClickFromShow = 0;
        $dto->conversionInClickFromView = 0;
        $dto->conversionInView          = 0;
        $dto->dateFrom                  = $firstItem['DATE_FROM'];
        $dto->dateTo                    = $firstItem['DATE_TO'];

        return $dto;
    }
}
