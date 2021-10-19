<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Picktech\Analytics\Data\Paginator;
use Picktech\Analytics\Entity\VendorActionPriceTable;
use Picktech\Analytics\VendorActionPrice\VendorActionPriceFilterDto;
use Picktech\Analytics\VendorActionPrice\VendorActionPriceReader;
use Picktech\Analytics\Product\ProductReader;

\Bitrix\Main\UI\Extension::load("ui.buttons");

Loader::includeModule("pt.analytics");
global $USER;
Loc::loadMessages(__FILE__);

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");
CUtil::InitJSCore(['jquery', 'pt_analytics']);
$request = Application::getInstance()->getContext()->getRequest()->toArray();

if (isset($request['saveVendor'])) {

    $promotionReader = new VendorActionPriceReader();
    $promotionFilter = new VendorActionPriceFilterDto();
    $promotionFilter->productId = $request['product'];

    $promotion = $promotionReader->readAll($promotionFilter, (new Paginator('promotion_list'))->allRecords());

    if ($promotion->count() > 0){
        ShowError("Рекламная кампания для этого продукта уже добавлена.");
    }
    else {
        $result = VendorActionPriceTable::add(
            [
                'UF_VENDOR_ID'   => $request['vendor_id'],
                'UF_PRODUCT_ID'  => $request['product'],
                'UF_PRICE_CLICK' => $request['price_click'],
                'UF_PRICE_VIEW'  => $request['price_view'],
            ]
        );
        ShowMessage(["TYPE"=>"OK", "MESSAGE"=>"Рекламная кампания сохранена"]);
    }
}

if (isset($request['updateVendor'])) {

    $result = VendorActionPriceTable::update(
        $request['id'],
        [
            'UF_PRICE_CLICK' => $request['price_click'],
            'UF_PRICE_VIEW'  => $request['price_view'],
        ]
    );
}

if (isset($request['addVendor'])) {
    $aTabs = [
        [
            "DIV"   => "edit0",
            "TAB"   => Loc::getMessage("pt_analytics_ADD_VENDOR_PRICE"),
            "ICON"  => "",
            "TITLE" => Loc::getMessage("pt_analytics_ADD_VENDOR_PRICE")
        ],
    ];
} else {
    $aTabs = [
        [
            "DIV"   => "edit0",
            "TAB"   => Loc::getMessage("pt_analytics_PAYMENT"),
            "ICON"  => "",
            "TITLE" => Loc::getMessage("pt_analytics_PAYMENT")
        ],
    ];
}

$tabControl = new CAdminTabControl("ptAnalyticsTabControl", $aTabs, true, true);
$tabControl->Begin();
$tabControl->BeginNextTab();

?>


<link rel="stylesheet" href="/local/modules/pt.analytics/assets/css/style.css">
<link rel="stylesheet" href="/local/modules/pt.analytics/assets/js/libs/select2/select2.min.css">
<link rel="stylesheet" href="/local/modules/pt.analytics/assets/js/libs/air-datepicker/css/datepicker.min.css">
<script type="text/javascript" src="/local/modules/pt.analytics/assets/js/libs/select2/select2.min.js"></script>
<script type="text/javascript" src="/local/modules/pt.analytics/assets/js/libs/select2/i18n/ru.js"></script>
<script type="text/javascript"
        src="/local/modules/pt.analytics/assets/js/libs/air-datepicker/js/datepicker.min.js"></script>
<script type="text/javascript" src="/local/modules/pt.analytics/assets/js/libs/jquery.validate1.14.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


<?php
if (isset($request['addVendor'])) { ?>
    <script>
        function get_result(q, type, code) {
            $.ajax({
                type:       "POST",
                url:        "/local/modules/pt.analytics/assets/ajax/ajaxSearchVendor.php",
                data:       "q=" + q,
                dataType:   'json',
                beforeSend: function (xhr) {

                },
                success:    function (json) {
                    BX.closeWait('vendor_search');
                    $('.search-result').html('<a href="javascript:void(0)" class="search_close"><i class="fa fa-times"></i></a>');
                    if (json.length > 0) {
                        if (type == 'button') {
                            //
                        } else {
                            if (code == '13') {
                                $(".search-result").fadeOut(200);
                            } else {
                                $('.search-result').html('<a href="javascript:void(0)" class="search_close"><i class="fa fa-times"></i></a><div id="catalog_search"></div>');
                                $.each(json, function (index, element) {
                                    $('#catalog_search').append('<div data-vendor-id=' + element.ID + '  class="analytics-product-line"><span>' + element.TITLE + '</span></div>');
                                });
                            }
                        }
                    } else {
                        $('.search-result').append('<div id="catalog_search_empty">По вашему запросу ничего не найдено.</div>');
                    }

                    $('.search-result').css({top: "40px"});
                    $('.search-result').show();


                    $(".analytics-product-line").on('click', function (event) {
                        vendor_id = $(this).data('vendor-id');
                        $("#vendor_search").val($(this).text());
                        $(".input-vendor-id").val(vendor_id);
                        $(".search-result").fadeOut(200);
                        BX.showWait('vendor_product');
                        $.ajax({
                            type:       "POST",
                            url:        "/local/modules/pt.analytics/assets/ajax/ajaxProductByVendor.php",
                            data:       "q=" + vendor_id,
                            dataType:   'json',
                            beforeSend: function (xhr) {

                            },
                            success:    function (json) {
                                selectStr = '';
                                $.each(json, function (index, element) {
                                    selectStr += '<option value="' + element.ID + '">' + element.TITLE + '</option>';
                                });
                                $(".select-product").html(selectStr).select2();
                                BX.closeWait('vendor_product');
                            }

                        });

                    });


                    $(".search_close").click(function () {
                        $(".search-result").fadeOut(200);
                    });
                    $(this).keydown(function (eventObject) {
                        if (eventObject.which == 27)
                            $(".search-result").fadeOut(200);
                    });

                }
            });
        }

        $(function () {
            "use strict";
            $("#vendor_search").keyup(function (event) {

                $(".search-result").fadeOut(200);
                var q, timer;
                q = this.value;
                clearTimeout(timer);
                if (q.length > 3) {
                    BX.showWait('vendor_search');
                    get_result(q, 'input', event.keyCode);
                }

            });
        });


    </script>

    <form method="post" action="pt_analytics_payment.php">
        <table style="width:50%;margin:0 auto;">

            <tr>
                <td><div style="position: relative">
                    <div class="ui-select__label ui-input">
                        <input
                                autocomplete="off"
                                style="border:none; width:100%;border-radius: inherit; box-shadow: none"
                                id="vendor_search" type="text"
                                placeholder="Начните вводить имя вендора" name="SEARCH" value="">
                              <input class="input-vendor-id" type="hidden" name="vendor_id" value="">
                    </div>
                    <div style="display: none;" class="search-result"></div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <select style="width: 100%;" name="product" required class="select-product">
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="ui-input">
                        <input id="priceClick" style="border:none; width:100%;border-radius: inherit; box-shadow: none"
                               type="text" value="" name="price_click"
                               placeholder="Введите цену для клика"/>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="ui-input">
                        <input type="text"
                               id="priceView"
                               style="border:none; width:100%;border-radius: inherit; box-shadow: none"
                               value=""
                               name="price_view" placeholder="Введите цену для просмотра"
                        />
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <button name="saveVendor" class="ui-btn ui-btn-primary">Сохранить</button>
                </td>
            </tr>
        </table>


    </form>


<?php } elseif (isset($request['element_id'])) {
    $vendorActionPriceReader = new VendorActionPriceReader();
    $vendor                  = $vendorActionPriceReader->readOne((int)$request['element_id']);
    $productReader = new ProductReader();
    $product =  $productReader->readOne( $vendor->productId);
    ?>
    <form action="pt_analytics_payment.php">
        <table style="width:50%;margin:0 auto;">

            <tr>
                <td>
                    <label for="productVendor">Имя вендора</label>
                    <div class="ui-input">
                        <input id="productVendor"
                               style="border:none; width:100%;border-radius: inherit; box-shadow: none" disabled
                               type="text" name="vendor" value="<?= $vendor->vendorName; ?>"/>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="productName">Имя продукта</label>
                    <div class="ui-input">
                        <input style="border:none; width:100%;border-radius: inherit; box-shadow: none" id="productName"
                               disabled type="text" value="<?= $product->name; ?>" name="productName"/>
                    </div>
                </td>
            </tr>
            <tr>

                <td>
                    <label for="priceClick">Стоимость клика</label>
                    <div class="ui-input">
                        <input id="priceClick" style="border:none; width:100%;border-radius: inherit; box-shadow: none"
                               type="text" value="<?= $vendor->priceClick; ?>" name="price_click"
                               placeholder="Введите цену для клика"/>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="priceView">Стоимость клика</label>
                    <div class="ui-input">
                        <input type="text"
                               id="priceView"
                               style="border:none; width:100%;border-radius: inherit; box-shadow: none"
                               value="<?= $vendor->priceView; ?>"
                               name="price_view" placeholder="Введите цену для просмотра"
                        />
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <input type="hidden" name="id" value="<?= $vendor->id; ?>"/>
                    <button name="updateVendor" class="ui-btn ui-btn-primary">Сохранить</button>
                </td>
            </tr>
        </table>


    </form>


<?php } else { ?>
    <form action="pt_analytics_payment.php">
        <button name="addVendor" class="ui-btn ui-btn-success ui-btn-sm">Добавить</button>
    </form>

    <div class="statistic-title"></div>
    <div class="ui-table">
        <table class="ui-table__table">
            <thead class="ui-table__thead ui-table__thead_background_gray">
                <tr class="ui-table__tr">
                    <td class="ui-table__td">Вендор</td>
                    <td class="ui-table__td">Продукт</td>
                    <td class="ui-table__td">Стоимость клика</td>
                    <td class="ui-table__td">Стоимость просмотра</td>
                    <td class="ui-table__td"></td>
                </tr>
            </thead>
            <tbody class="views_statistic ui-table__tbody">
                <?php
                $vendorActionPriceReader = new VendorActionPriceReader();
                $vendorActionPriceFilter = new VendorActionPriceFilterDto();
                $pageNavigation          = new Paginator('vendor_price_list');

                $vendorActionPriceList = $vendorActionPriceReader->readAll(
                    $vendorActionPriceFilter,
                    $pageNavigation->allRecords()
                );

                foreach ($vendorActionPriceList as $vendorActionPrice) {
                    $productReader = new ProductReader();
                    $product =  $productReader->readOne( $vendorActionPrice->productId);
                    ?>
                    <tr class="ui-table__tr">
                        <td class="ui-table__td"><?= $vendorActionPrice->vendorName ?></td>
                        <td class="ui-table__td"><?= $product->name ?></td>
                        <td class="ui-table__td"><?= $vendorActionPrice->priceClick ?></td>
                        <td class="ui-table__td"><?= $vendorActionPrice->priceView ?></td>
                        <td class="ui-table__td"><a class="ui-btn ui-btn-danger-dark"
                                                    href="/bitrix/admin/pt_analytics_payment.php?element_id=<?= $vendorActionPrice->id ?>">Редактировать</a>
                        </td>
                    </tr>
                    <?php
                }
                ?>

            </tbody>
        </table>
    </div>

<?php } ?>


