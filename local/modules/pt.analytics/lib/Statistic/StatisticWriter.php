<?php

/**
 * Class StatisticWriter
 * @package Picktech\Analytics\Statistic
 */

namespace Picktech\Analytics\Statistic;

use Bitrix\Main\Application;
use Bitrix\Main\Type\DateTime;
use Picktech\Analytics\Data\Paginator;
use Picktech\Analytics\Entity\AnalyticsTable;
use Picktech\Analytics\Entity\VendorActionPriceTable;
use Picktech\Analytics\VendorActionPrice\VendorActionPriceFilterDto;
use Picktech\Analytics\VendorActionPrice\VendorActionPriceReader;

class StatisticWriter {

    static $actions = ['SHOW', 'VIEW', 'CLICK_SITE', 'CLICK_TRIAL'];

    /**
     * StatisticWriter constructor.
     */

    public function __construct() {
    }

    /**
     * @param string $type
     * @param int    $productId
     * @param string $refer
     */

    private function writeAction(int $type, int $productId, string $refer) {

        $objDateTime = new DateTime();
        $result      = AnalyticsTable::add(
            [
                'UF_PRODUCT_ID'      => $productId,
                'UF_TYPE_ACTION'     => self::$actions[$type],
                'UF_DATETIME_ACTION' => $objDateTime->toString(),
                'UF_REFER_URL'       => $refer,
            ]
        );

        if (!$result->isSuccess()) {
            throw new RuntimeException(implode('; ', $result->getErrorMessages()));
        }
    }

    /**
     * @param int    $productId
     * @param string $refer
     */

    public static function writeShow(int $productId, string $refer) {
        self::writeAction(0, $productId, $refer);
    }

    /**
     * @param $action
     * @param $productId
     */

    public function payAction($action, $productId): void {
        $promotionReader            = new VendorActionPriceReader();
        $promotionFilter            = new VendorActionPriceFilterDto();
        $promotionFilter->productId = $productId;
        $promotionList              = $promotionReader->readAll(
            $promotionFilter,
            (new Paginator('promotion_list'))->setPageSize(1)
        );
        if ($promotion  = $promotionList->first()) {

            $userReader = \CUser::GetList(
                $sort = 'id',
                $order = 'asc',
                ['UF_VENDOR_ID' => $promotion->vebdorId],
                ['SELECT' => ['ID']]
            );
            $user       = $userReader->Fetch();
            if ($promotion->status === 'run' && $promotion->budget > 0) {
                $bSuccessPayment = \CSaleUserAccount::UpdateAccount(
                    $user['ID'],
                    ($action === 'click') ? -(int)$promotion->priceClick : -(int)$promotion->priceView,
                    'RUB',
                    serialize([$action, $productId]),
                    false,
                    ''
                );

                $sumAction = ($action === 'click') ? -(int)$promotion->priceClick : -(int)$promotion->priceView;
                $budgetSum = $promotion->budget - $sumAction;

                $result = VendorActionPriceTable::update(
                    $promotion->id,
                    [
                        'UF_BUDGET' => $budgetSum,
                    ]
                );

                if ($bill = \CSaleUserAccount::GetByUserID($user['ID'], "RUB")) {

                    if ($budgetSum < $promotion->priceClick
                        && $budgetSum < $promotion->priceVie) {
                        $result = VendorActionPriceTable::update(
                            $promotion->id,
                            [
                                'UF_STATUS' => 'pause',
                            ]
                        );
                    } elseif ($bill['CURRENT_BUDGET'] < $promotion->priceClick
                        && $bill['CURRENT_BUDGET'] < $promotion->priceView) {
                        $result = VendorActionPriceTable::update(
                            $promotion->id,
                            [
                                'UF_STATUS' => 'pause',
                            ]
                        );
                    }
                }
            } else {
                $result = VendorActionPriceTable::update(
                    $promotion->id,
                    [
                        'UF_STATUS' => 'pause',
                    ]
                );
            }
        }
    }

    /**
     * @param int    $productId
     * @param string $refer
     */

    public static function writeView(int $productId, string $refer) {
        $session = Application::getInstance()->getSession();

        if (!$session->has('viewed_prod' . $productId)) {
            self::writeAction(1, $productId, $refer);
            self::payAction('view', $productId);
            $session->set('viewed_prod' . $productId, 'Y');
        }
    }

    /**
     * @param int    $productId
     * @param string $refer
     * @param string $type
     */

    public static function writeClick(int $productId, string $refer, string $type) {
        self::writeAction(($type === 'SITE') ? 2 : 3, $productId, $refer);
        self::payAction('click', $productId);
    }

    /**
     * @param int    $sectionId
     * @param string $refer
     * @param bool   $page
     * @param bool   $pageSize
     */

    public static function writeShowFromCat(int $sectionId, string $refer, $page = false, $pageSize = false) {

        $pageArr = [];

        if ((int)$page > 0) {
            $pageArr = ['nPageSize' => $pageSize, 'iNumPage' => $page];
        }

        $resProds = \CIBlockElement::GetList(
            [],
            ['SECTION_ID' => $sectionId, 'ACTIVE' => 'Y'],
            false,
            $pageArr,
            ['ID']
        );
        while ($prodArr = $resProds->GetNext()) {
            self::writeAction(0, $prodArr['ID'], $refer);
        }
    }

}