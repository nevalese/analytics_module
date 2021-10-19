<?php
declare(strict_types=1);

namespace Picktech\Analytics\Statistic;

final class StatisticDto
{
    /**
     * @var int
     */
    public $productId;

    /**
     * @var string
     */
    public $productName;

    /**
     * @var int
     */
    public $views;

    /**
     * @var int
     */
    public $shows;


    /**
     * @var int
     */
    public $clicks;


    /**
     * @var int
     */
    public $conversionInView;


    /**
     * @var int
     */
    public $conversionInClickFromShow;


    /**
     * @var int
     */
    public $conversionInClickFromView;

    /**
     * @var Bitrix\Main\Type\DateTime
     */
    public $dateFrom;


    /**
     * @var Bitrix\Main\Type\DateTime
     */
    public $dateTo;

}
