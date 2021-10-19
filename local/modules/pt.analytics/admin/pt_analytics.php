<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Type\DateTime;
use Picktech\Analytics\Controller\Statistic;

Loader::includeModule("pt.analytics");
global $USER;
Loc::loadMessages(__FILE__);

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");
CUtil::InitJSCore(['jquery', 'pt_analytics']);

if ($USER->IsAdmin()) {

    $request = Application::getInstance()->getContext()->getRequest();

    $typeReport = htmlspecialchars($request->getQuery("type"));

    switch ($typeReport) {
        case 'view':
            $aTabs = [
                [
                    "DIV"   => "edit0",
                    "TAB"   => Loc::getMessage("pt_analytics_MAIN_VIEWS"),
                    "ICON"  => "",
                    "TITLE" => Loc::getMessage("pt_analytics_MAIN_VIEWS")
                ],
            ];
            break;
        case 'click':
            $aTabs = [
                [
                    "DIV"   => "edit0",
                    "TAB"   => Loc::getMessage("pt_analytics_MAIN_CLICKS"),
                    "ICON"  => "",
                    "TITLE" => Loc::getMessage("pt_analytics_MAIN_CLICKS")
                ],
            ];
            break;
        case 'show':
            $aTabs = [
                [
                    "DIV"   => "edit0",
                    "TAB"   => Loc::getMessage("pt_analytics_MAIN_SHOWS"),
                    "ICON"  => "",
                    "TITLE" => Loc::getMessage("pt_analytics_MAIN_SHOWS")
                ],
            ];
            break;
        case 'conversion_in_view':
            $aTabs = [
                [
                    "DIV"   => "edit0",
                    "TAB"   => Loc::getMessage("pt_analytics_MAIN_CONVERSION_IN_VIEW"),
                    "ICON"  => "",
                    "TITLE" => Loc::getMessage("pt_analytics_MAIN_CONVERSION_IN_VIEW")
                ],
            ];
            break;

        case 'conversion_in_click_from_show':

            $aTabs = [
                [
                    "DIV"   => "edit0",
                    "TAB"   => Loc::getMessage("pt_analytics_MAIN_CONVERSION_IN_CLOCK_FROM_SHOWS"),
                    "ICON"  => "",
                    "TITLE" => Loc::getMessage("pt_analytics_MAIN_CONVERSION_IN_CLOCK_FROM_SHOWS")
                ],
            ];

            break;
        case 'conversion_in_click_from_view':

            $aTabs = [
                [
                    "DIV"   => "edit0",
                    "TAB"   => Loc::getMessage("pt_analytics_MAIN_CONVERSION_IN_CLOCK_FROM_VIEWS"),
                    "ICON"  => "",
                    "TITLE" => Loc::getMessage("pt_analytics_MAIN_CONVERSION_IN_CLOCK_FROM_VIEWS")
                ],
            ];

            break;

        default:
            $aTabs = [
                [
                    "DIV"   => "edit0",
                    "TAB"   => Loc::getMessage("pt_analytics_MAIN"),
                    "ICON"  => "",
                    "TITLE" => Loc::getMessage("pt_analytics_MAIN")
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
    <script type="text/javascript"
            src="/local/modules/pt.analytics/assets/js/libs/jquery.validate1.14.0.min.js"></script>
    <script type="text/javascript">
        $(document).on('click', '.datepicker--button--select', function (e) {
            e.stopPropagation();
            $('#analytics_date')
                .data('datepicker')
                .hide();
        });

        function getStatistic(date) {

            product_id = $(".input-analytic-product-id").val();
            BX.showWait('analytics_date');
            $.ajax({
                type:       "POST",
                url:        "/local/modules/pt.analytics/assets/ajax/ajaxGetStatistic.php",
                data:       "product_id=" + product_id + "&date=" + date,
                dataType:   'json',
                beforeSend: function (xhr) {

                },
                success:    function (json) {
                    BX.closeWait('analytics_date');
                    $('.analytics-product-show').text(json.SHOW);
                    $('.analytics-product-view').text(json.VIEW);
                    $('.analytics-product-click').text(json.CLICK);

                    var statistics_row;
                    $.each(json.BYDATE, function (index, element) {

                        if (typeof element.COUNT_SHOW === "undefined") {
                            element.COUNT_SHOW = 0;
                        }
                        if (typeof element.COUNT_VIEW === "undefined") {
                            element.COUNT_VIEW = 0;
                        }
                        if (typeof element.COUNT_CLICK === "undefined") {
                            element.COUNT_CLICK = 0;
                        }

                        statistics_row += '<tr class="ui-table__tr"><td class="ui-table__td"><span>'
                            + element.DATE_FORMAT + '</span></td><td class="ui-table__td"><span>'
                            + element.COUNT_SHOW + '</span></td><td class="ui-table__td"><span>'
                            + element.COUNT_VIEW + '</span></td><td class="ui-table__td"><span>'
                            + element.COUNT_CLICK + '</span></td></tr>';
                    });

                    $('.main_statistic').html(statistics_row);


                    $('#analytics_date').val(date);
                    $('.statistic-title').html('Статистика по продукту за ' + date);
                }
            });
        }


        function getViews(date) {

            product_id = $(".input-analytic-product-id").val();

            $.ajax({
                type:       "POST",
                url:        "/local/modules/pt.analytics/assets/ajax/ajaxGetViews.php",
                data:       "product_id=" + product_id + "&date=" + date,
                dataType:   'json',
                beforeSend: function (xhr) {

                },
                success:    function (json) {
                    var statistics_row;
                    $.each(json, function (index, element) {
                        statistics_row += '<tr class="ui-table__tr"><td class="ui-table__td"><span>'
                            + element.DATE + '</span></td><td class="ui-table__td"><span>'
                            + element.COUNT + '</span></td> <td class="ui-table__td"><span>'
                            + element.REFER + '</span></td> </tr>';
                    });

                    $('.views_statistic').html(statistics_row);
                    $('.statistic-title').html('Статистика по продукту за ' + date);
                }
            });
        }






        function getClicks(date) {

            product_id = $(".input-analytic-product-id").val();

            $.ajax({
                type:       "POST",
                url:        "/local/modules/pt.analytics/assets/ajax/ajaxGetClicks.php",
                data:       "product_id=" + product_id + "&date=" + date,
                dataType:   'json',
                beforeSend: function (xhr) {

                },
                success:    function (json) {
                    var statistics_row;
                    $.each(json, function (index, element) {
                        statistics_row += '<tr class="ui-table__tr"><td class="ui-table__td"><span>'
                            + element.DATE + '</span></td><td class="ui-table__td"><span>1</span></td> <td class="ui-table__td"><span>'
                            + element.REFER + '</span></td> </tr>';
                    });

                    $('.clicks_statistic').html(statistics_row);
                    $('.statistic-title').html('Статистика по продукту за ' + date);
                }
            });
        }


        function getShows(date) {
            product_id = $(".input-analytic-product-id").val();

            $.ajax({
                type:       "POST",
                url:        "/local/modules/pt.analytics/assets/ajax/ajaxGetShows.php",
                data:       "product_id=" + product_id + "&date=" + date,
                dataType:   'json',
                beforeSend: function (xhr) {

                },
                success:    function (json) {
                    var statistics_row;
                    $.each(json, function (index, element) {
                        statistics_row += '<tr class="ui-table__tr"><td class="ui-table__td"><span>'
                            + element.DATE + '</span></td><td class="ui-table__td"><span>1</span></td> <td class="ui-table__td"><span>'
                            + element.REFER + '</span></td> </tr>';
                    });

                    $('.shows_statistic').html(statistics_row);
                    $('.statistic-title').html('Статистика по продукту за ' + date);
                }
            });
        }

        function getConversion(date, type) {
            product_id = $(".input-analytic-product-id").val();

            $.ajax({
                type:       "POST",
                url:        "/local/modules/pt.analytics/assets/ajax/ajaxGetConversion.php",
                data:       "product_id=" + product_id + "&date=" + date + "&type=" + type,
                dataType:   'json',
                beforeSend: function (xhr) {

                },
                success:    function (json) {
                    var statistics_row;
                    $.each(json, function (index, element) {
                        if (type === 1) {
                            statistics_row += '<tr class="ui-table__tr"><td class="ui-table__td"><span>'
                                + element.DATE + '</span></td><td class="ui-table__td"><span>'
                                + element.COUNT_VIEW + '</span></td> <td class="ui-table__td"><span>'
                                + element.COUNT_SHOW + '</span></td> <td class="ui-table__td"><span>'
                                + element.CONVERSION + '%</span></td> </tr>';
                        }
                        if (type === 2) {
                            statistics_row += '<tr class="ui-table__tr"><td class="ui-table__td"><span>'
                                + element.DATE + '</span></td><td class="ui-table__td"><span>'
                                + element.COUNT_CLICK + '</span></td> <td class="ui-table__td"><span>'
                                + element.COUNT_SHOW + '</span></td> <td class="ui-table__td"><span>'
                                + element.CONVERSION + '%</span></td> </tr>';
                        }
                        if (type === 3) {
                            statistics_row += '<tr class="ui-table__tr"><td class="ui-table__td"><span>'
                                + element.DATE + '</span></td><td class="ui-table__td"><span>'
                                + element.COUNT_CLICK + '</span></td> <td class="ui-table__td"><span>'
                                + element.COUNT_VIEW + '</span></td> <td class="ui-table__td"><span>'
                                + element.CONVERSION + '%</span></td> </tr>';
                        }
                    });

                    $('.conversion').html(statistics_row);
                    $('.statistic-title').html('Статистика по продукту за ' + date);
                }
            });
        }


        function get_result(q, type, code) {
            $.ajax({
                type:       "POST",
                url:        "/local/modules/pt.analytics/assets/ajax/ajaxSearchProduct.php",
                data:       "q=" + q,
                dataType:   'json',
                beforeSend: function (xhr) {

                },
                success:    function (json) {
                    BX.closeWait('analytics_search');
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
                                    $('#catalog_search').append('<div data-product-id=' + element.ID + '  class="analytics-product-line"><span>' + element.TITLE + '</span></div>');
                                });
                            }
                        }
                    } else {
                        $('.search-result').append('<div id="catalog_search_empty">По вашему запросу ничего не найдено.</div>');
                    }

                    $('.search-result').css({top: "40px"});
                    $('.search-result').show();


                    $(".analytics-product-line").on('click', function (event) {
                        product_id = $(this).data('product-id');
                        $("#analytics_search").val($(this).text());
                        $(".input-analytic-product-id").val(product_id);
                        $(".search-result").fadeOut(200);
                        $(".product-statistic").fadeIn(200);
                        //                        dateReq = new Date().toLocaleDateString();
                        dateReq = $('#analytics_date').val();

                        $('#analytics_date').removeAttr('disabled');


                        getStatistic(dateReq);

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
            var $pickerwrap = $('#analytics_date').parent().parent();
            $pickerwrap.click(function () {
                $('#analytics_date').trigger('focus');
            });
            $("#analytics_search").keyup(function (event) {

                $(".search-result").fadeOut(200);
                var q, timer;
                q = this.value;
                clearTimeout(timer);
                if (q.length > 3) {
                    BX.showWait('analytics_search');
                    get_result(q, 'input', event.keyCode);
                }

            });
        });

    </script>

    <?php
    $dateTo   = (new DateTime())->format("d.m.Y");
    $dateFrom = (new DateTime())->add("-7 day")->format("d.m.Y");
    ?>
    <div class="lk-vendor-analytics-products-page">
        <div class="lk-vendor-header-wrap">
            <div class="lk-vendor-header-wrap__left">
                <div class="ui-select__label ui-input ui-search-input">
                    <input
                            style="border:none; width:100%;border-radius: inherit; box-shadow: none"
                            id="analytics_search" type="text"
                            placeholder="Начните набирать продукт" name="SEARCH" value="">
                    <input class="input-analytic-product-id" type="hidden" name="PRODUCT_ID" value="">
                </div>
                <div style="display: none;" class="search-result"></div>
            </div>

            <div class="lk-vendor-header-wrap__right">
                <div class="ui-select ui-select_icon_calendar ui-select__select-date" data-placeholder="">
                    <div class="ui-select__label ui-input">
                        <input id="analytics_date"
                               disabled=""
                               style="border:none; width:100%;border-radius: inherit; box-shadow: none"
                               data-range="true"
                               data-multiple-dates-separator=" - "
                               type="text"
                               placeholder="Выберете промежуток времени" name="DATE"
                               value="<?= $dateFrom; ?> - <?= $dateTo; ?>" autocomplete="off">
                    </div>
                </div>
            </div>
        </div>
        <? if ((string)$typeReport === 'view') { ?>
            <script>
                function CLkVendorAnalyticCom(target) {
                    "use strict";

                    this.$com = $(target);
                    this.selector = ".lk-vendor-analytics-products-page";
                    this.block = "lk-vendor-analytics-products-page";
                    this.endpoint = "";
                    this.picker = $('#analytics_date');
                    /**
                     * Конструктор
                     */
                    this.init = function () {
                        this.$com.data("bxcom", this);
                        this.endpoint = document.location.href;
                        this.picker.datepicker({
                            multipleDates: true,
                            range:         true,
                            todayButton:   true,
                            maxDate:       new Date(),
                            onShow:        function (inst) {
                                $('.datepicker--buttons').html('<span class="datepicker--button datepicker--button--select" style="color:#FF9A19" onclick="getViews($(\'#analytics_date\').val())">Выбрать</span>');
                            },
                            //                        onSelect: function (inst, animationCompleted) {
                            //                            getViews($('#analytics_date').val());
                            //                        }
                        });
                        this.initEvents();
                    };
                    /**
                     * Инициализация событий
                     */
                    this.initEvents = function () {
                    };

                    this.init(); // Вызов конструктора
                }

                $(function () {
                    "use strict";

                    $(".lk-vendor-analytics-products-page").each(function () {
                        new CLkVendorAnalyticCom(this);
                    });
                });
            </script>
            <div class="statistic-title"></div>
            <div class="ui-table">
                <table class="ui-table__table">
                    <thead class="ui-table__thead ui-table__thead_background_gray">
                        <tr class="ui-table__tr">
                            <td class="ui-table__td">Дата</td>
                            <td class="ui-table__td">Просмотры</td>
                            <td class="ui-table__td">Страница взаимодействия</td>
                        </tr>
                    </thead>
                    <tbody class="views_statistic ui-table__tbody">

                    </tbody>
                </table>
            </div>
        <? } elseif ((string)$typeReport === 'click') { ?>

            <script>
                function CLkVendorAnalyticCom(target) {
                    "use strict";

                    this.$com = $(target);
                    this.selector = ".lk-vendor-analytics-products-page";
                    this.block = "lk-vendor-analytics-products-page";
                    this.endpoint = "";
                    this.picker = $('#analytics_date');
                    /**
                     * Конструктор
                     */
                    this.init = function () {
                        this.$com.data("bxcom", this);
                        this.endpoint = document.location.href;
                        this.picker.datepicker({
                            multipleDates: true,
                            range:         true,
                            todayButton:   true,
                            maxDate:       new Date(),
                            onShow:        function (inst) {
                                $('.datepicker--buttons').html('<span class="datepicker--button datepicker--button--select" style="color:#FF9A19" onclick="getClicks($(\'#analytics_date\').val())">Выбрать</span>');
                            },
//                        onSelect: function (inst, animationCompleted) {
//                            getClicks($('#analytics_date').val());
//                        }
                        });
                        this.initEvents();
                    };
                    /**
                     * Инициализация событий
                     */
                    this.initEvents = function () {
                    };

                    this.init(); // Вызов конструктора
                }

                $(function () {
                    "use strict";

                    $(".lk-vendor-analytics-products-page").each(function () {
                        new CLkVendorAnalyticCom(this);
                    });
                });
            </script>

            <div class="statistic-title"></div>
            <div class="ui-table">
                <table class="ui-table__table">
                    <thead class="ui-table__thead ui-table__thead_background_gray">
                        <tr class="ui-table__tr">
                            <td class="ui-table__td">Дата</td>
                            <td class="ui-table__td">Клики</td>
                            <td class="ui-table__td">Страница взаимодействия</td>
                        </tr>
                    </thead>
                    <tbody class="clicks_statistic ui-table__tbody">

                    </tbody>
                </table>
            </div>


        <? } elseif ((string)$typeReport === 'show') { ?>


            <script>
                function CLkVendorAnalyticCom(target) {
                    "use strict";

                    this.$com = $(target);
                    this.selector = ".lk-vendor-analytics-products-page";
                    this.block = "lk-vendor-analytics-products-page";
                    this.endpoint = "";
                    this.picker = $('#analytics_date');
                    /**
                     * Конструктор
                     */
                    this.init = function () {
                        this.$com.data("bxcom", this);
                        this.endpoint = document.location.href;
                        this.picker.datepicker({
                            multipleDates: true,
                            range:         true,
                            todayButton:   true,
                            maxDate:       new Date(),
                            onShow:        function (inst) {
                                $('.datepicker--buttons').html('<span class="datepicker--button datepicker--button--select" style="color:#FF9A19" onclick="getShows($(\'#analytics_date\').val())">Выбрать</span>');
                            },
//                        onSelect: function (inst, animationCompleted) {
//                            getShows($('#analytics_date').val());
//                        }
                        });
                        this.initEvents();
                    };
                    /**
                     * Инициализация событий
                     */
                    this.initEvents = function () {
                    };

                    this.init(); // Вызов конструктора
                }

                $(function () {
                    "use strict";

                    $(".lk-vendor-analytics-products-page").each(function () {
                        new CLkVendorAnalyticCom(this);
                    });
                });
            </script>

            <div class="statistic-title"></div>
            <div class="ui-table">
                <table class="ui-table__table">
                    <thead class="ui-table__thead ui-table__thead_background_gray">
                        <tr class="ui-table__tr">
                            <td class="ui-table__td">Дата</td>
                            <td class="ui-table__td">Показы</td>
                            <td class="ui-table__td">Страница взаимодействия</td>
                        </tr>
                    </thead>
                    <tbody class="shows_statistic ui-table__tbody">

                    </tbody>
                </table>
            </div>


        <? } elseif ((string)$typeReport === 'conversion_in_view') { ?>

            <script>
                function CLkVendorAnalyticCom(target) {
                    "use strict";

                    this.$com = $(target);
                    this.selector = ".lk-vendor-analytics-products-page";
                    this.block = "lk-vendor-analytics-products-page";
                    this.endpoint = "";
                    this.picker = $('#analytics_date');
                    /**
                     * Конструктор
                     */
                    this.init = function () {
                        this.$com.data("bxcom", this);
                        this.endpoint = document.location.href;
                        this.picker.datepicker({
                            multipleDates: true,
                            range:         true,
                            todayButton:   true,
                            maxDate:       new Date(),
                            onShow:        function (inst) {
                                $('.datepicker--buttons').html('<span class="datepicker--button datepicker--button--select" style="color:#FF9A19" onclick="getConversion($(\'#analytics_date\').val(),1)">Выбрать</span>');
                            },
//                        onSelect: function (inst, animationCompleted) {
//                            getStatistic($('#analytics_date').val());
//                        }
                        });
                        this.initEvents();
                    };
                    /**
                     * Инициализация событий
                     */
                    this.initEvents = function () {
                    };

                    this.init(); // Вызов конструктора
                }

                $(function () {
                    "use strict";

                    $(".lk-vendor-analytics-products-page").each(function () {
                        new CLkVendorAnalyticCom(this);
                    });
                });
            </script>


            <div class="statistic-title"></div>
            <div class="ui-table">
                <table class="ui-table__table">
                    <thead class="ui-table__thead ui-table__thead_background_gray">
                        <tr class="ui-table__tr">
                            <td class="ui-table__td">Дата</td>
                            <td class="ui-table__td">Просмотры</td>
                            <td class="ui-table__td">Показы</td>
                            <td class="ui-table__td">Конверсия, %</td>
                        </tr>
                    </thead>
                    <tbody class="conversion ui-table__tbody">

                    </tbody>
                </table>
            </div>


        <?php } elseif ((string)$typeReport === 'conversion_in_click_from_show') { ?>

            <script>
                function CLkVendorAnalyticCom(target) {
                    "use strict";

                    this.$com = $(target);
                    this.selector = ".lk-vendor-analytics-products-page";
                    this.block = "lk-vendor-analytics-products-page";
                    this.endpoint = "";
                    this.picker = $('#analytics_date');
                    /**
                     * Конструктор
                     */
                    this.init = function () {
                        this.$com.data("bxcom", this);
                        this.endpoint = document.location.href;
                        this.picker.datepicker({
                            multipleDates: true,
                            range:         true,
                            todayButton:   true,
                            maxDate:       new Date(),
                            onShow:        function (inst) {
                                $('.datepicker--buttons').html('<span class="datepicker--button datepicker--button--select" style="color:#FF9A19" onclick="getConversion($(\'#analytics_date\').val(),2)">Выбрать</span>');
                            },
//                        onSelect: function (inst, animationCompleted) {
//                            getStatistic($('#analytics_date').val());
//                        }
                        });
                        this.initEvents();
                    };
                    /**
                     * Инициализация событий
                     */
                    this.initEvents = function () {
                    };

                    this.init(); // Вызов конструктора
                }

                $(function () {
                    "use strict";

                    $(".lk-vendor-analytics-products-page").each(function () {
                        new CLkVendorAnalyticCom(this);
                    });
                });
            </script>

            <div class="statistic-title"></div>
            <div class="ui-table">
                <table class="ui-table__table">
                    <thead class="ui-table__thead ui-table__thead_background_gray">
                        <tr class="ui-table__tr">
                            <td class="ui-table__td">Дата</td>
                            <td class="ui-table__td">Клики</td>
                            <td class="ui-table__td">Показы</td>
                            <td class="ui-table__td">Конверсия, %</td>
                        </tr>
                    </thead>
                    <tbody class="conversion ui-table__tbody">

                    </tbody>
                </table>
            </div>


        <?php } elseif ((string)$typeReport === 'conversion_in_click_from_view') { ?>

            <script>
                function CLkVendorAnalyticCom(target) {
                    "use strict";

                    this.$com = $(target);
                    this.selector = ".lk-vendor-analytics-products-page";
                    this.block = "lk-vendor-analytics-products-page";
                    this.endpoint = "";
                    this.picker = $('#analytics_date');
                    /**
                     * Конструктор
                     */
                    this.init = function () {
                        this.$com.data("bxcom", this);
                        this.endpoint = document.location.href;
                        this.picker.datepicker({
                            multipleDates: true,
                            range:         true,
                            todayButton:   true,
                            maxDate:       new Date(),
                            onShow:        function (inst) {
                                $('.datepicker--buttons').html('<span class="datepicker--button datepicker--button--select" style="color:#FF9A19" onclick="getConversion($(\'#analytics_date\').val(),3)">Выбрать</span>');
                            },
//                        onSelect: function (inst, animationCompleted) {
//                            getStatistic($('#analytics_date').val());
//                        }
                        });
                        this.initEvents();
                    };
                    /**
                     * Инициализация событий
                     */
                    this.initEvents = function () {
                    };

                    this.init(); // Вызов конструктора
                }

                $(function () {
                    "use strict";

                    $(".lk-vendor-analytics-products-page").each(function () {
                        new CLkVendorAnalyticCom(this);
                    });
                });
            </script>

            <div class="statistic-title"></div>
            <div class="ui-table">
                <table class="ui-table__table">
                    <thead class="ui-table__thead ui-table__thead_background_gray">
                        <tr class="ui-table__tr">
                            <td class="ui-table__td">Дата</td>
                            <td class="ui-table__td">Клики</td>
                            <td class="ui-table__td">Просмотры</td>
                            <td class="ui-table__td">Конверсия, %</td>
                        </tr>
                    </thead>
                    <tbody class="conversion ui-table__tbody">

                    </tbody>
                </table>
            </div>

        <?php } elseif ($typeReport === 'all_products') {
        $weeklyStatistic = new Statistic;

        $page = $APPLICATION->GetCurPageParam('', ['sort', 'sortDirection']);
        $pageDate = $APPLICATION->GetCurPageParam('', ['date']);
        $request = Application::getInstance()->getContext()->getRequest();

        if ($request->getQuery('date')){
            $dateArr = explode(' - ', $request->getQuery('date'));
            $objDateTimeFrom = new DateTime($dateArr[0]);
            $objDateTimeTo = new DateTime($dateArr[1]);
            $dateFrom = $dateArr[0];
            $dateTo = $dateArr[1];
            $statisticWeekly = $weeklyStatistic->getStatisticCountByDate($objDateTimeFrom, $objDateTimeTo);
        }
        else{
            $objDateTimeFrom = (new DateTime())->add("-7 day");
            $objDateTimeTo = (new DateTime());
            $statisticWeekly = $weeklyStatistic->getWeeklyStatistic();
        }




        $statisticWeeklyTable = $weeklyStatistic->getFullStatisticByProduct($objDateTimeFrom, $objDateTimeTo);
        ?>

            <div class="statistic-title">Статистика по всем продуктам за <?= $dateFrom ?> - <?= $dateTo ?></div>
            <div class="product-statistic">
                <div class="product-statistic__item">
                    <div class="product-statistic__title">Показы</div>
                    <div class="product-statistic__counts">
                        <div class="product-statistic__count analytics-product-show"><?= $statisticWeekly['SHOW'] ?></div>
                        <span class="product-statistic__percent"></span>
                    </div>
                </div>
                <div class="product-statistic__item">
                    <div class="product-statistic__title">Просмотры</div>
                    <div class="product-statistic__counts">
                        <div class="product-statistic__count analytics-product-view"><?= $statisticWeekly['VIEW'] ?></div>
                        <span class="product-statistic__percent"></span>
                    </div>
                </div>
                <div class="product-statistic__item">
                    <div class="product-statistic__title">Клики</div>
                    <div class="product-statistic__counts">
                        <div class="product-statistic__count analytics-product-click"><?= $statisticWeekly['CLICK'] ?></div>
                        <span class="product-statistic__percent product-statistic__percent_color_red"></span>
                    </div>
                </div>
            </div>

            <script>
                function getStatisticByProduct (date) {
                    window.location.replace('<?=$pageDate;?>' + '&date='+date);
                }

                function CLkVendorAnalyticCom(target) {
                    "use strict";

                    this.$com = $(target);
                    this.selector = ".lk-vendor-analytics-products-page";
                    this.block = "lk-vendor-analytics-products-page";
                    this.endpoint = "";
                    this.picker = $('#analytics_date');
                    /**
                     * Конструктор
                     */
                    this.init = function () {
                        this.$com.data("bxcom", this);
                        this.endpoint = document.location.href;
                        this.picker.datepicker({
                            multipleDates: true,
                            range:         true,
                            todayButton:   true,
                            maxDate:       new Date(),
                            onShow:        function (inst) {
                                $('.datepicker--buttons').html('<span class="datepicker--button datepicker--button--select" style="color:#FF9A19" onclick="getStatisticByProduct($(\'#analytics_date\').val())">Выбрать</span>');
                            },

                        });
                        this.initEvents();
                    };
                    /**
                     * Инициализация событий
                     */
                    this.initEvents = function () {
                    };

                    this.init(); // Вызов конструктора
                }

                $(function () {
                    "use strict";
                    $('#analytics_date').removeAttr('disabled').val('<?= $dateFrom ?> - <?= $dateTo ?>');;
                    $('.ui-search-input').hide();

                    $(".lk-vendor-analytics-products-page").each(function () {
                        new CLkVendorAnalyticCom(this);
                    });
                });
            </script>



            <div class="statistic-product-data ui-table">
                <table class="ui-table__table">
                    <thead class="ui-table__thead ui-table__thead_background_gray">
                        <tr class="ui-table__tr">
                            <td class="ui-table__td"><a href="<?=$page;?>&sort=name&sortDirection=<?=($request->getQuery('sortDirection') && $request->getQuery('sortDirection') === 'DESC')?'ASC':'DESC'?>">Продукт</a></td>
                            <td class="ui-table__td"><a href="<?=$page;?>&sort=show&sortDirection=<?=($request->getQuery('sortDirection') && $request->getQuery('sortDirection') === 'DESC')?'ASC':'DESC'?>">Показы</a></td>
                            <td class="ui-table__td"><a href="<?=$page;?>&sort=view&sortDirection=<?=($request->getQuery('sortDirection') && $request->getQuery('sortDirection') === 'DESC')?'ASC':'DESC'?>">Просмотры</a></td>
                            <td class="ui-table__td"><a href="<?=$page;?>&sort=click&sortDirection=<?=($request->getQuery('sortDirection') && $request->getQuery('sortDirection') === 'DESC')?'ASC':'DESC'?>">Клики</a></td>
                        </tr>
                    </thead>
                    <tbody class="ui-table__tbody statistic-product-data">
                        <?php foreach ($statisticWeeklyTable['PRODUCT'] as $key => $row) { ?>
                            <tr class="ui-table__tr">
                                <td class="ui-table__td"><?= $row['PRODUCT_NAME']; ?></a></td>
                                <td class="ui-table__td"><?= (($row['SHOW']) ? $row['SHOW'] : '0') ?></td>
                                <td class="ui-table__td"><?= (($row['VIEW']) ? $row['VIEW'] : '0') ?></td>
                                <td class="ui-table__td"><?= (($row['CLICK']) ? $row['CLICK'] : '0') ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <?
                $APPLICATION->IncludeComponent(
                    "bitrix:main.pagenavigation",
                    "",
                    array(
                        "NAV_OBJECT" => $statisticWeeklyTable['NAV'],
                        "SEF_MODE" => "N",
                    ),
                    false
                );
                ?>
            </div>



        <?php } else {
        $weeklyStatistic = new Statistic;
        $statisticWeekly = $weeklyStatistic->getWeeklyStatistic();

        $statisticWeeklyTable = $weeklyStatistic->getWeeklyStatisticSortByDate();
        ?>
            <script>
                function CLkVendorAnalyticCom(target) {
                    "use strict";

                    this.$com = $(target);
                    this.selector = ".lk-vendor-analytics-products-page";
                    this.block = "lk-vendor-analytics-products-page";
                    this.endpoint = "";
                    this.picker = $('#analytics_date');
                    /**
                     * Конструктор
                     */
                    this.init = function () {
                        this.$com.data("bxcom", this);
                        this.endpoint = document.location.href;
                        this.initEvents();
                    };

                    /**
                     * Инициализация событий
                     */
                    this.initEvents = function () {
                        this.picker.datepicker({
                            multipleDates: true,
                            range:         true,
                            todayButton:   true,
                            maxDate:       new Date(),
                            onShow:        function (inst) {
                                $('.datepicker--buttons').html('<span class="datepicker--button datepicker--button--select" style="color:#FF9A19" onclick="getStatistic($(\'#analytics_date\').val())">Выбрать</span>');
                            },
//                        onSelect: function (inst, animationCompleted) {
//                            getStatistic($('#analytics_date').val());
//                        }
                        });
                    };

                    this.init(); // Вызов конструктора
                }

                $(function () {
                    "use strict";
                    $(".lk-vendor-analytics-products-page").each(function () {
                        new CLkVendorAnalyticCom(this);
                    });
                });
            </script>

            <div class="statistic-title">Статистика по всем продуктам за <?= $dateFrom ?> - <?= $dateTo ?></div>
            <div class="product-statistic">
                <div class="product-statistic__item">
                    <div class="product-statistic__title">Показы</div>
                    <div class="product-statistic__counts">
                        <div class="product-statistic__count analytics-product-show"><?= $statisticWeekly['SHOW'] ?></div>
                        <span class="product-statistic__percent"></span>
                    </div>
                </div>
                <div class="product-statistic__item">
                    <div class="product-statistic__title">Просмотры</div>
                    <div class="product-statistic__counts">
                        <div class="product-statistic__count analytics-product-view"><?= $statisticWeekly['VIEW'] ?></div>
                        <span class="product-statistic__percent"></span>
                    </div>
                </div>
                <div class="product-statistic__item">
                    <div class="product-statistic__title">Клики</div>
                    <div class="product-statistic__counts">
                        <div class="product-statistic__count analytics-product-click"><?= $statisticWeekly['CLICK'] ?></div>
                        <span class="product-statistic__percent product-statistic__percent_color_red"></span>
                    </div>
                </div>
            </div>

            <div class="ui-table">
                <table class="ui-table__table">
                    <thead class="ui-table__thead ui-table__thead_background_gray">
                        <tr class="ui-table__tr">
                            <td class="ui-table__td">Дата</td>
                            <td class="ui-table__td">Показы</td>
                            <td class="ui-table__td">Просмотры</td>
                            <td class="ui-table__td">Клики</td>
                        </tr>
                    </thead>
                    <tbody class="main_statistic ui-table__tbody">
                        <?php foreach ($statisticWeeklyTable as $key => $row) { ?>
                            <tr class="ui-table__tr">
                                <td class="ui-table__td"><?= $row['DATE_FORMAT']; ?></td>
                                <td class="ui-table__td"><?= (($row['COUNT_SHOW']) ? $row['COUNT_SHOW'] : '0') ?></td>
                                <td class="ui-table__td"><?= (($row['COUNT_VIEW']) ? $row['COUNT_VIEW'] : '0') ?></td>
                                <td class="ui-table__td"><?= (($row['COUNT_CLICK']) ? $row['COUNT_CLICK'] : '0') ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>


        <?php } ?>

    </div>
    <?php $tabControl->End(); ?>
<?php } ?>
