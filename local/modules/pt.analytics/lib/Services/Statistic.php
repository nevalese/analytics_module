<?php

namespace Picktech\Analytics\Services;

use Bitrix\Main\Diag\Debug;
use Picktech\Analytics\Entity\AnalyticsTable;

/**
 * Class Statistic
 * @package Picktech\Analytics\Services
 */
class Statistic {

    /**
     * @param $product_id
     * @param $dateFrom
     * @param $dateTo
     * @param $type
     *
     * @return mixed
     */


    function getAction($product_id, $dateFrom, $dateTo, $type) {
        $arFilter = [
            'UF_PRODUCT_ID'        => $product_id,
            '><UF_DATETIME_ACTION' => [$dateFrom, $dateTo],
            'UF_TYPE_ACTION'       => $type,
        ];
        return $items = AnalyticsTable::getList(
            [
                'filter' => $arFilter,
                'select' => [
                    'ID',
                    'UF_DATETIME_ACTION',
                    'UF_REFER_URL'
                ],
            ]
        )->fetchAll();
    }

    /**
     * @param $product_id
     * @param $dateFrom
     * @param $dateTo
     *
     * @return array
     */


    public static function getStatistic($product_id, $dateFrom, $dateTo) {
        $result                   = [];
        $resultArr['VIEW']        = self::getAction($product_id, $dateFrom, $dateTo, 'VIEW');
        $resultArr['SHOW']        = self::getAction($product_id, $dateFrom, $dateTo, 'SHOW');
        $resultArr['CLICK_SITE']  = self::getAction($product_id, $dateFrom, $dateTo, 'CLICK_SITE');
        $resultArr['CLICK_TRIAL'] = self::getAction($product_id, $dateFrom, $dateTo, 'CLICK_TRIAL');

        $result['VIEW'] = count($resultArr['VIEW']);

        $result['SHOW'] = count($resultArr['SHOW']);
        $result['CLICK'] = count( $resultArr['CLICK_SITE'])+ count($resultArr['CLICK_TRIAL']);

        return $result;
    }

    /**
     * @param $product_id
     * @param $dateFrom
     * @param $dateTo
     *
     * @return string
     */


    public static function  getViews($product_id, $dateFrom, $dateTo){
        $result                   = [];
        $resultArr = self::getAction($product_id, $dateFrom, $dateTo, 'VIEW');

        $i=0;
        $dateToCompare = false;
        foreach ($resultArr as $res){

            $dateAction = $res['UF_DATETIME_ACTION'];

            if ($dateAction->format("Y-m-d") === $dateToCompare){
                $result[$i]['COUNT'] ++;
                $result[$i]['REFER'] .=  ($res['UF_REFER_URL'])? '<br />'.$res['UF_REFER_URL'] : "";
            }
            else{
                $i++;
                $result[$i]['DATE'] = $res['UF_DATETIME_ACTION']->toString();
                $result[$i]['REFER'] .= ($res['UF_REFER_URL'])? '<br />'.$res['UF_REFER_URL'] : "";
                $result[$i]['COUNT'] =1;
            }

            $dateToCompare = $dateAction->format("Y-m-d");

        }

        return json_encode($result);
    }

    /**
     * @param $product_id
     * @param $dateFrom
     * @param $dateTo
     *
     * @return string
     */


    public static function  getClicks($product_id, $dateFrom, $dateTo){
        $result                   = [];
        $resultArr = self::getAction($product_id, $dateFrom, $dateTo, 'CLICK_SITE');

        $resultArr2 = self::getAction($product_id, $dateFrom, $dateTo, 'CLICK_TRIAL');

        $i=0;

        foreach ($resultArr as $res){

            $dateAction = $res['UF_DATETIME_ACTION'];

            if ($dateAction->format("Y-m-d") === $dateToCompare){
                $result[$i]['COUNT'] ++;
                $result[$i]['REFER'] .=   ($res['UF_REFER_URL'])? '<br />'.$res['UF_REFER_URL'] : "";
            }
            else{
                $i++;
                $result[$i]['DATE'] = $res['UF_DATETIME_ACTION']->toString();
                $result[$i]['REFER'] .= ($res['UF_REFER_URL'])? '<br />'.$res['UF_REFER_URL'] : "";
                $result[$i]['COUNT'] =1;
            }

            $dateToCompare = $dateAction->format("Y-m-d");

        }


        foreach ($resultArr2 as $res){

            $dateAction = $res['UF_DATETIME_ACTION'];

            if ($dateAction->format("Y-m-d") === $dateToCompare){
                $result[$i]['COUNT'] ++;
                $result[$i]['REFER'] .=   ($res['UF_REFER_URL'])? '<br />'.$res['UF_REFER_URL'] : "";
            }
            else{
                $i++;
                $result[$i]['DATE'] = $res['UF_DATETIME_ACTION']->toString();
                $result[$i]['REFER'] .= ($res['UF_REFER_URL'])? '<br />'.$res['UF_REFER_URL'] : "";
                $result[$i]['COUNT'] =1;
            }

            $dateToCompare = $dateAction->format("Y-m-d");

        }

        return json_encode($result);
    }


    /**
     * @param $product_id
     * @param $dateFrom
     * @param $dateTo
     *
     * @return string
     */

    public static function  getShows($product_id, $dateFrom, $dateTo){
        $result                   = [];
        $resultArr = self::getAction($product_id, $dateFrom, $dateTo, 'SHOW');


        $i=0;
        foreach ($resultArr as $res){

            $dateAction = $res['UF_DATETIME_ACTION'];

            if ($dateAction->format("Y-m-d") === $dateToCompare){
                $result[$i]['COUNT'] ++;
                $result[$i]['REFER'] .= ($res['UF_REFER_URL'])? '<br />'.$res['UF_REFER_URL'] : "";
            }
            else{
                $i++;
                $result[$i]['DATE'] = $res['UF_DATETIME_ACTION']->toString();
                $result[$i]['REFER'] = $res['UF_REFER_URL'];
                $result[$i]['COUNT'] =1;
            }

            $dateToCompare = $dateAction->format("Y-m-d");

        }

        return json_encode($result);
    }




}