<?php

declare(strict_types=1);

namespace Picktech\Analytics\Product;

final class ProductDto {
    /**
     * @var int
     */
    public $id;

    /**
     * @var int
     */
    public $xmlId;

    /**
     * @var string
     */
    public $code;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $logo;

    /**
     * @var int
     */
    public $vendor;

    /**
     * @var int
     */
    public $priceClick;

    /**
     * @var int
     */
    public $priceView;

    /**
     * @var bool
     */

    public $isPaid;

    /**
     * @var bool
     */

    public $isPriceConfirmed;

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

    /**
     * @var int
     */

    public $promotionId;

    /**
     * @var int
     */

    public $views;

    /**
     * @var int
     */

    public $clicks;

    /**
     * @var int
     */

    public $shows;

}
