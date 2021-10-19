<?php
namespace Picktech\Analytics;
$arJsConfig = [
    'pt_analytics' => [
        'js' => '/bitrix/js/pt_analytics/script.js',
        'rel' => array(),
    ]
];

foreach ($arJsConfig as $ext => $arExt) {
    \CJSCore::RegisterExt($ext, $arExt);
}
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

global $PT_ANALYTICS;
$PT_ANALYTICS = new Module();


