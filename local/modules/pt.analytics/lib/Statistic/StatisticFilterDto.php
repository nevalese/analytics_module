<?php
declare(strict_types=1);

namespace Picktech\Analytics\Statistic;

use Picktech\Analytics\Data\AbstractReader;

final class StatisticFilterDto
{
    /**
     * @var string
     */
    public $dateFrom;


    /**
     * @var string
     */
    public $dateTo;


    /**
     * @var string
     */
    public $productId;


    /**
     * @var string
     */
    public $typeAction;

}
