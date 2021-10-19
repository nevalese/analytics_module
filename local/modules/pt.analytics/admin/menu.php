<?php
$aMenu[] = [
    "parent_menu" => "global_menu_statistics",
    "sort"        => 1800,
    "text"        => GetMessage("pt_analytics_MENU_MAIN"),
    "title"       => GetMessage("pt_analytics_MENU_MAIN"),
    "url"         => "pt_analytics.php?lang=" . LANGUAGE_ID,
    "icon"        => "statistic_icon_traffic",
    "page_icon"   => "statistic_icon_traffic",
    "items_id"    => "menu_util",
    "items"       => [
        [
            "text"     => GetMessage("pt_analytics_MENU_ALL_PRODUCTS"),
            "url"      => "pt_analytics.php?type=all_products&lang=" . LANGUAGE_ID,
            "more_url" => [],
            "title"    => GetMessage("pt_analytics_MENU_ALL_PRODUCTS"),
        ],
        [
            "text"     => GetMessage("pt_analytics_MENU_VIEWS"),
            "url"      => "pt_analytics.php?type=view&lang=" . LANGUAGE_ID,
            "more_url" => [],
            "title"    => GetMessage("pt_analytics_MENU_VIEWS"),
        ],
        [
            "text"     => GetMessage("pt_analytics_MENU_CLIKS"),
            "url"      => "pt_analytics.php?type=click&lang=" . LANGUAGE_ID,
            "more_url" => [],
            "title"    => GetMessage("pt_analytics_MENU_CLIKS"),
        ],
        [
            "text"     => GetMessage("pt_analytics_MENU_SHOWS"),
            "url"      => "pt_analytics.php?type=show&lang=" . LANGUAGE_ID,
            "more_url" => [],
            "title"    => GetMessage("pt_analytics_MENU_SHOWS"),
        ],
        [
            "text"     => GetMessage("pt_analytics_MENU_CONVERSION_IN_VIEW"),
            "url"      => "pt_analytics.php?type=conversion_in_view&lang=" . LANGUAGE_ID,
            "more_url" => [],
            "title"    => GetMessage("pt_analytics_MENU_CONVERSION_IN_VIEW"),
        ],
        [
            "text"     => GetMessage("pt_analytics_MENU_CONVERSION_IN_CLICK_FROM_SHOW"),
            "url"      => "pt_analytics.php?type=conversion_in_click_from_show&lang=" . LANGUAGE_ID,
            "more_url" => [],
            "title"    => GetMessage("pt_analytics_MENU_CONVERSION_IN_CLICK_FROM_SHOW"),
        ],
        [
            "text"     => GetMessage("pt_analytics_MENU_CONVERSION_IN_CLICK_FROM_VIEWS"),
            "url"      => "pt_analytics.php?type=conversion_in_click_from_view&lang=" . LANGUAGE_ID,
            "more_url" => [],
            "title"    => GetMessage("pt_analytics_MENU_CONVERSION_IN_CLICK_FROM_VIEWS"),
        ],
    ],
];

$aMenu[] = [
    "parent_menu" => "global_menu_statistics",
    "sort"        => 1800,
    "text"        => GetMessage("pt_analytics_MENU_PAYMENT"),
    "title"       => GetMessage("pt_analytics_MENU_PAYMENT"),
    "url"         => "pt_analytics_payment.php?lang=" . LANGUAGE_ID,
    "icon"        => "currency_menu_icon",
    "page_icon"   => "statistic_icon_traffic",
    "items_id"    => "menu_util",
    "items"       => [
        [
            "text"     => GetMessage("pt_analytics_MENU_PAYMENT_VENDOR"),
            "url"      => "pt_analytics_payment.php?lang=" . LANGUAGE_ID,
            "more_url" => [],
            "title"    => GetMessage("pt_analytics_MENU_PAYMENT_VENDOR"),
        ],
        [
            "text"     => GetMessage("pt_analytics_MENU_PAYMENT_VENDOR_BUDGET"),
            "url"      => "pt_analytics_payment.php?lang=" . LANGUAGE_ID,
            "more_url" => [],
            "title"    => GetMessage("pt_analytics_MENU_PAYMENT_VENDOR_BUDGET"),
        ],
    ]
];

return $aMenu;