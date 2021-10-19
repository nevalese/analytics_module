<?php

namespace Sprint\Migration;

class AddDailyStatistic20210513193754 extends Version {
    protected $description = "";

    /**
     * @return bool|void
     * @throws Exceptions\HelperException
     */
    public function up() {
        $helper    = $this->getHelperManager();
        $hlblockId = $helper->Hlblock()->saveHlblock(
            [
                'NAME'       => 'DailyStatistic',
                'TABLE_NAME' => 'daily_statistic',
                'LANG'       =>
                    [
                        'ru' =>
                            [
                                'NAME' => 'Статистика по продуктам за день',
                            ],
                        'en' =>
                            [
                                'NAME' => 'Daily Statistic',
                            ],
                    ],
            ]
        );
        $helper->Hlblock()->saveField(
            $hlblockId,
            [
                'FIELD_NAME'        => 'UF_PRODUCT_ID',
                'USER_TYPE_ID'      => 'double',
                'XML_ID'            => 'UF_PRODUCT_ID',
                'SORT'              => '100',
                'MULTIPLE'          => 'N',
                'MANDATORY'         => 'N',
                'SHOW_FILTER'       => 'I',
                'SHOW_IN_LIST'      => 'Y',
                'EDIT_IN_LIST'      => 'Y',
                'IS_SEARCHABLE'     => 'N',
                'SETTINGS'          =>
                    [
                        'PRECISION'     => 4,
                        'SIZE'          => 20,
                        'MIN_VALUE'     => 0.0,
                        'MAX_VALUE'     => 0.0,
                        'DEFAULT_VALUE' => 0.0,
                    ],
                'EDIT_FORM_LABEL'   =>
                    [
                        'en' => '',
                        'ru' => '',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'en' => '',
                        'ru' => '',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'en' => '',
                        'ru' => '',
                    ],
                'ERROR_MESSAGE'     =>
                    [
                        'en' => '',
                        'ru' => '',
                    ],
                'HELP_MESSAGE'      =>
                    [
                        'en' => '',
                        'ru' => '',
                    ],
            ]
        );
        $helper->Hlblock()->saveField(
            $hlblockId,
            [
                'FIELD_NAME'        => 'UF_CLICKS',
                'USER_TYPE_ID'      => 'double',
                'XML_ID'            => 'UF_CLICKS',
                'SORT'              => '100',
                'MULTIPLE'          => 'N',
                'MANDATORY'         => 'N',
                'SHOW_FILTER'       => 'N',
                'SHOW_IN_LIST'      => 'Y',
                'EDIT_IN_LIST'      => 'Y',
                'IS_SEARCHABLE'     => 'N',
                'SETTINGS'          =>
                    [
                        'PRECISION'     => 4,
                        'SIZE'          => 20,
                        'MIN_VALUE'     => 0.0,
                        'MAX_VALUE'     => 0.0,
                        'DEFAULT_VALUE' => '',
                    ],
                'EDIT_FORM_LABEL'   =>
                    [
                        'en' => '',
                        'ru' => '',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'en' => '',
                        'ru' => '',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'en' => '',
                        'ru' => '',
                    ],
                'ERROR_MESSAGE'     =>
                    [
                        'en' => '',
                        'ru' => '',
                    ],
                'HELP_MESSAGE'      =>
                    [
                        'en' => '',
                        'ru' => '',
                    ],
            ]
        );
        $helper->Hlblock()->saveField(
            $hlblockId,
            [
                'FIELD_NAME'        => 'UF_VIEWS',
                'USER_TYPE_ID'      => 'double',
                'XML_ID'            => 'UF_VIEWS',
                'SORT'              => '100',
                'MULTIPLE'          => 'N',
                'MANDATORY'         => 'N',
                'SHOW_FILTER'       => 'N',
                'SHOW_IN_LIST'      => 'Y',
                'EDIT_IN_LIST'      => 'Y',
                'IS_SEARCHABLE'     => 'N',
                'SETTINGS'          =>
                    [
                        'PRECISION'     => 4,
                        'SIZE'          => 20,
                        'MIN_VALUE'     => 0.0,
                        'MAX_VALUE'     => 0.0,
                        'DEFAULT_VALUE' => '',
                    ],
                'EDIT_FORM_LABEL'   =>
                    [
                        'en' => '',
                        'ru' => '',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'en' => '',
                        'ru' => '',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'en' => '',
                        'ru' => '',
                    ],
                'ERROR_MESSAGE'     =>
                    [
                        'en' => '',
                        'ru' => '',
                    ],
                'HELP_MESSAGE'      =>
                    [
                        'en' => '',
                        'ru' => '',
                    ],
            ]
        );
        $helper->Hlblock()->saveField(
            $hlblockId,
            [
                'FIELD_NAME'        => 'UF_SHOWS',
                'USER_TYPE_ID'      => 'double',
                'XML_ID'            => 'UF_SHOWS',
                'SORT'              => '100',
                'MULTIPLE'          => 'N',
                'MANDATORY'         => 'N',
                'SHOW_FILTER'       => 'N',
                'SHOW_IN_LIST'      => 'Y',
                'EDIT_IN_LIST'      => 'Y',
                'IS_SEARCHABLE'     => 'N',
                'SETTINGS'          =>
                    [
                        'PRECISION'     => 4,
                        'SIZE'          => 20,
                        'MIN_VALUE'     => 0.0,
                        'MAX_VALUE'     => 0.0,
                        'DEFAULT_VALUE' => '',
                    ],
                'EDIT_FORM_LABEL'   =>
                    [
                        'en' => '',
                        'ru' => '',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'en' => '',
                        'ru' => '',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'en' => '',
                        'ru' => '',
                    ],
                'ERROR_MESSAGE'     =>
                    [
                        'en' => '',
                        'ru' => '',
                    ],
                'HELP_MESSAGE'      =>
                    [
                        'en' => '',
                        'ru' => '',
                    ],
            ]
        );
        $helper->Hlblock()->saveField(
            $hlblockId,
            [
                'FIELD_NAME'        => 'UF_DATE',
                'USER_TYPE_ID'      => 'date',
                'XML_ID'            => 'UF_DATE',
                'SORT'              => '100',
                'MULTIPLE'          => 'N',
                'MANDATORY'         => 'N',
                'SHOW_FILTER'       => 'N',
                'SHOW_IN_LIST'      => 'Y',
                'EDIT_IN_LIST'      => 'Y',
                'IS_SEARCHABLE'     => 'N',
                'SETTINGS'          =>
                    [
                        'DEFAULT_VALUE' =>
                            [
                                'TYPE'  => 'NONE',
                                'VALUE' => '',
                            ],
                    ],
                'EDIT_FORM_LABEL'   =>
                    [
                        'en' => '',
                        'ru' => '',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'en' => '',
                        'ru' => '',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'en' => '',
                        'ru' => '',
                    ],
                'ERROR_MESSAGE'     =>
                    [
                        'en' => '',
                        'ru' => '',
                    ],
                'HELP_MESSAGE'      =>
                    [
                        'en' => '',
                        'ru' => '',
                    ],
            ]
        );
    }

    public function down() {
        //your code ...
    }
}
