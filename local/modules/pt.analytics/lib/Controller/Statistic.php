<?php

namespace Picktech\Analytics\Controller;

use Bitrix\Main\Application;
use Bitrix\Main\Type\Date;
use Bitrix\Main\Type\DateTime;
use Picktech\Analytics\Data\Paginator;
use Picktech\Analytics\Product\ProductFilterDto;
use Picktech\Analytics\Product\ProductReader;
use Picktech\Analytics\Statistic\StatisticFilterDto;
use Picktech\Analytics\Statistic\StatisticReader;

/**
 * Class Statistic
 * @package Picktech\Analytics\Controller
 */
class Statistic {

    /**
     * @var StatisticReader
     */
    private $reader;

    /**
     * @return array
     */
    function getWeeklyStatistic() {

        $weeklyStatistic = ['SHOW' => 0, 'VIEW' => 0, 'CLICK' => 0];
        $this->reader    = new StatisticReader();

        foreach ($weeklyStatistic as $key => $action) {
            $filter           = new StatisticFilterDto();
            $filter->dateTo   = (new Date());
            $filter->dateFrom = (new Date())->add('-7 day');

            if ($key === 'CLICK') {
                $filter->typeAction = ['CLICK_SITE', 'CLICK_TRIAL'];
            } else {
                $filter->typeAction = $key;
            }
            $actionCount = $this->reader->readCountActionByDate($filter);

            $weeklyStatistic[$key] = $actionCount;
        }

        return $weeklyStatistic;
    }

    /**
     * @return array
     */
    function getStatisticCountByDate($dateFrom, $dateTo) {

        $weeklyStatistic = ['SHOW' => 0, 'VIEW' => 0, 'CLICK' => 0];
        $this->reader    = new StatisticReader();

        foreach ($weeklyStatistic as $key => $action) {
            $filter           = new StatisticFilterDto();
            $filter->dateTo   = $dateTo;
            $filter->dateFrom = $dateFrom;

            if ($key === 'CLICK') {
                $filter->typeAction = ['CLICK_SITE', 'CLICK_TRIAL'];
            } else {
                $filter->typeAction = $key;
            }
            $actionCount = $this->reader->readCountActionByDate($filter);

            $weeklyStatistic[$key] = $actionCount;
        }

        return $weeklyStatistic;
    }

    /**
     * @param Date $dateFrom
     * @param Date $dateTo
     *
     * @return array
     */

    public function getFullStatisticByProduct(DateTime $dateFrom, DateTime $dateTo): array {


        $request = Application::getInstance()->getContext()->getRequest();

        $productReader  = new ProductReader();
        $productFilter  = new ProductFilterDto();
        $pageNavigation = new Paginator('product_list');

        $pageNavigation->initFromUri();

        if ($request->getQuery('sortDirection') && $request->getQuery('sort')) {
            $sortDirection = strtoupper($request->getQuery('sortDirection'));
            $sort          = strtoupper($request->getQuery('sort'));
        } else {
            $sort          = 'NAME';
            $sortDirection = 'ASC';
        }

        $productList = $productReader->readAllwithStat(
            $productFilter,
            $pageNavigation,
            $sort,
            $sortDirection,
            $dateFrom,
            $dateTo
        );

        $result = [];
        foreach ($productList as $key => $product) {
            $result['PRODUCT'][$key]['PRODUCT_NAME'] = $product->name;
            $result['PRODUCT'][$key]['PRODUCT_ID']   = $product->id;
            $filter                                  = new StatisticFilterDto();
            $filter->productId                       = $product->id;
            $filter->dateTo                          = $dateTo;
            $filter->dateFrom                        = $dateFrom;
            $result['PRODUCT'][$key]['VIEW']         = $product->views;
            $result['PRODUCT'][$key]['CLICK']        = $product->clicks;
            $result['PRODUCT'][$key]['SHOW']         = $product->shows;
        }

        $result['NAV'] = $pageNavigation;

        $result['NAV'] = $pageNavigation;

        return $result;
    }

    /**
     * @return array
     * @throws \Bitrix\Main\ObjectException
     */

    function getWeeklyStatisticSortByDate() {

        $statisticArray            = [];
        $this->reader              = new StatisticReader();
        $statisticFilter           = new StatisticFilterDto();
        $statisticFilter->dateFrom = (new Date())->add('-7 day');
        $statisticFilter->dateTo   = new Date();

        $y = 0;
        foreach (['SHOW', 'VIEW', 'CLICK'] as $action) {
            if ($action === 'CLICK') {
                $statisticFilter->typeAction = ['CLICK_SITE', 'CLICK_TRIAL'];
            } else {
                $statisticFilter->typeAction = $action;
            }
            $actionCount = $this->reader->readCountActionsGroupBy('day', $statisticFilter);
            foreach ($actionCount as $actionArr) {
                $key = array_search($actionArr['DATE'], array_column($statisticArray, 'DATE'));

                if ($key !== false) {
                    $statisticArray[$key]['COUNT_' . $action] = $actionArr['CNT'];
                } else {
                    $statisticArray[$y]['DATE']             = $actionArr['DATE'];
                    $statisticArray[$y]['DATE_FORMAT']      = (new Date($actionArr['DATE'], "Y-m-d"))->toString();
                    $statisticArray[$y]['COUNT_' . $action] = $actionArr['CNT'];
                    $y++;
                }
            }
        }
        return $statisticArray;
    }

    /**
     * @param $dateFrom
     * @param $dateTo
     *
     * @return array
     */

    function getProductStatisticSortByDate(string $dateFrom, string $dateTo, int $productId) {

        $statisticArray             = [];
        $this->reader               = new StatisticReader();
        $statisticFilter            = new StatisticFilterDto();
        $statisticFilter->dateFrom  = new \Bitrix\Main\Type\DateTime($dateFrom);
        $statisticFilter->dateTo    = (new \Bitrix\Main\Type\DateTime($dateTo));
        $statisticFilter->productId = $productId;

        $y = 0;
        foreach (['SHOW', 'VIEW', 'CLICK'] as $action) {
            if ($action === 'CLICK') {
                $statisticFilter->typeAction = ['CLICK_SITE', 'CLICK_TRIAL'];
            } else {
                $statisticFilter->typeAction = $action;
            }
            $actionCount = $this->reader->readCountActionsGroupBy('day', $statisticFilter);
            foreach ($actionCount as $actionArr) {
                $key = array_search($actionArr['DATE'], array_column($statisticArray, 'DATE'));

                if ($key !== false) {
                    $statisticArray[$key]['COUNT_' . $action] = $actionArr['CNT'];
                } else {
                    $statisticArray[$y]['DATE']             = $actionArr['DATE'];
                    $statisticArray[$y]['DATE_FORMAT']      = (new Date($actionArr['DATE'], "Y-m-d"))->toString();
                    $statisticArray[$y]['COUNT_' . $action] = $actionArr['CNT'];
                    $y++;
                }
            }
        }

        return $statisticArray;
    }

    /**
     * @param string $dateFrom
     * @param string $dateTo
     * @param int    $producId
     * @param int    $userId
     *
     * @return array
     */

    function getProductFullStatisticSortByDate(string $dateFrom, string $dateTo, int $productId, int $userId): array {

        $weeklyStatistic = [];
        $this->reader    = new StatisticReader();

        $y = 0;

        $filter            = new StatisticFilterDto();
        $filter->productId = $productId;
        $filter->dateFrom  = new \Bitrix\Main\Type\DateTime($dateFrom);
        $filter->dateTo    = (new \Bitrix\Main\Type\DateTime($dateTo));

        foreach (['SHOW', 'VIEW', 'CLICK', 'COST'] as $action) {

            if ($action === 'COST') {
                $res                          = \CSaleUserTransact::GetList(
                    ['ID' => 'DESC'],
                    [
                        'USER_ID'         => $userId,
                        '>=TRANSACT_DATE' => $filter->dateFrom,
                        '<=TRANSACT_DATE' => $filter->dateTo
                    ]
                );
                while ($arFields = $res->Fetch()) {
                    $paymentOption = unserialize($arFields['DESCRIPTION']);
                    if ((int)$paymentOption[1] === $productId && ($paymentOption[0] === 'click' || $paymentOption[0] === 'view')) {
                        $key = array_search( (new DateTime($arFields['TRANSACT_DATE']))->format('Y-m-d'), array_column($weeklyStatistic, 'DATE'));
                        if ($key !== false) {
                            $weeklyStatistic[$key][$action] += $arFields['AMOUNT'];
                        }
                        else{
                            $weeklyStatistic[$y]['DATE']             = (new DateTime($arFields['TRANSACT_DATE']))->format('Y-m-d');
                            $weeklyStatistic[$y]['DATE_FORMAT']      = $arFields['TRANSACT_DATE'];
                            $weeklyStatistic[$y][$action] = $arFields['AMOUNT'];
                            $y++;
                        }
                    }
                }
            } else {
                if ($action === 'CLICK') {
                    $filter->typeAction = ['CLICK_SITE', 'CLICK_TRIAL'];
                } else {
                    $filter->typeAction = $action;
                }
                $actionCount = $this->reader->readCountActionsGroupBy('day', $filter);
                foreach ($actionCount as $actionArr) {
                    $key = array_search($actionArr['DATE'], array_column($weeklyStatistic, 'DATE'));
                    if ($key !== false) {
                        $weeklyStatistic[$key]['COUNT_' . $action] = $actionArr['CNT'];
                    } else {
                        $weeklyStatistic[$y]['DATE']             = $actionArr['DATE'];
                        $weeklyStatistic[$y]['DATE_FORMAT']      = (new Date($actionArr['DATE'], "Y-m-d"))->toString();
                        $weeklyStatistic[$y]['COUNT_' . $action] = $actionArr['CNT'];
                        $y++;
                    }
                }
            }
        }

        return $weeklyStatistic;
    }

}