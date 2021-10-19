<?php
declare(strict_types=1);

namespace Picktech\Analytics\VendorActionPrice;

final class VendorActionPriceDto
{
    /**
     * @var int
     */
    public $id;

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
    public $vendorId;

    /**
     * @var string
     */

    public $vendorName;

    /**
     * @var float
     */

    public $priceClick;

    /**
     * @var float
     */

    public $priceView;


    /**
     * @var float
     */

    public $budget;

    /**
     * @var string
     */

    public $dateStart;

    /**
     * @var string
     */

    public $status;
}


