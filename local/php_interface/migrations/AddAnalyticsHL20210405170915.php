<?php

namespace Sprint\Migration;

class AddAnalyticsHL20210405170915 extends Version {
    protected $description = "Добавление HL для статистики";

    /**
     * @throws Exceptions\HelperException
     * @return bool|void
     */
    public function up() {
        $helper    = $this->getHelperManager();
        $hlblockId = $helper->Hlblock()->saveHlblock(
            [
                'NAME'       => 'Analytics',
                'TABLE_NAME' => 'product_analytics',
                'LANG'       =>
                    [
                        'ru' =>
                            [
                                'NAME' => 'Данные по аналитике',
                            ],
                        'en' =>
                            [
                                'NAME' => 'Analytics data ',
                            ],
                    ],
            ]
        );
        $helper->Hlblock()->saveField(
            $hlblockId,
            [
                'FIELD_NAME'        => 'UF_PRODUCT_ID',
                'USER_TYPE_ID'      => 'integer',
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
                        'SIZE'          => 20,
                        'MIN_VALUE'     => 0,
                        'MAX_VALUE'     => 0,
                        'DEFAULT_VALUE' => '',
                    ],
                'EDIT_FORM_LABEL'   =>
                    [
                        'en' => 'PRODUCT ID',
                        'ru' => 'PRODUCT ID',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'en' => 'PRODUCT ID',
                        'ru' => 'PRODUCT ID',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'en' => 'PRODUCT ID',
                        'ru' => 'PRODUCT ID',
                    ],
                'ERROR_MESSAGE'     =>
                    [
                        'en' => '',
                        'ru' => '',
                    ],
                'HELP_MESSAGE'      =>
                    [
                        'en' => 'PRODUCT ID',
                        'ru' => 'PRODUCT ID',
                    ],
            ]
        );
        $helper->Hlblock()->saveField(
            $hlblockId,
            [
                'FIELD_NAME'        => 'UF_DATETIME_ACTION',
                'USER_TYPE_ID'      => 'datetime',
                'XML_ID'            => 'UF_DATETIME_ACTION',
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
                                'TYPE'  => 'NOW',
                                'VALUE' => '',
                            ],
                        'USE_SECOND'    => 'Y',
                        'USE_TIMEZONE'  => 'N',
                    ],
                'EDIT_FORM_LABEL'   =>
                    [
                        'en' => 'Date of action ',
                        'ru' => 'Date of action ',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'en' => 'Date of action ',
                        'ru' => 'Date of action ',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'en' => 'Date of action ',
                        'ru' => 'Date of action ',
                    ],
                'ERROR_MESSAGE'     =>
                    [
                        'en' => '',
                        'ru' => '',
                    ],
                'HELP_MESSAGE'      =>
                    [
                        'en' => 'Date of action ',
                        'ru' => 'Date of action ',
                    ],
            ]
        );
        $helper->Hlblock()->saveField(
            $hlblockId,
            [
                'FIELD_NAME'        => 'UF_REFER_URL',
                'USER_TYPE_ID'      => 'string',
                'XML_ID'            => 'UF_REFER_URL',
                'SORT'              => '100',
                'MULTIPLE'          => 'N',
                'MANDATORY'         => 'N',
                'SHOW_FILTER'       => 'N',
                'SHOW_IN_LIST'      => 'Y',
                'EDIT_IN_LIST'      => 'Y',
                'IS_SEARCHABLE'     => 'N',
                'SETTINGS'          =>
                    [
                        'SIZE'          => 20,
                        'ROWS'          => 1,
                        'REGEXP'        => '',
                        'MIN_LENGTH'    => 0,
                        'MAX_LENGTH'    => 0,
                        'DEFAULT_VALUE' => '',
                    ],
                'EDIT_FORM_LABEL'   =>
                    [
                        'en' => 'Refer URL',
                        'ru' => 'Refer URL',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'en' => 'Refer URL',
                        'ru' => 'Refer URL',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'en' => 'Refer URL',
                        'ru' => 'Refer URL',
                    ],
                'ERROR_MESSAGE'     =>
                    [
                        'en' => '',
                        'ru' => '',
                    ],
                'HELP_MESSAGE'      =>
                    [
                        'en' => 'Refer URL',
                        'ru' => 'Refer URL',
                    ],
            ]
        );
        $helper->Hlblock()->saveField(
            $hlblockId,
            [
                'FIELD_NAME'        => 'UF_TYPE_ACTION',
                'USER_TYPE_ID'      => 'string',
                'XML_ID'            => 'UF_TYPE_ACTION',
                'SORT'              => '100',
                'MULTIPLE'          => 'N',
                'MANDATORY'         => 'N',
                'SHOW_FILTER'       => 'N',
                'SHOW_IN_LIST'      => 'Y',
                'EDIT_IN_LIST'      => 'Y',
                'IS_SEARCHABLE'     => 'N',
                'SETTINGS'          =>
                    [
                        'SIZE'          => 20,
                        'ROWS'          => 1,
                        'REGEXP'        => '',
                        'MIN_LENGTH'    => 0,
                        'MAX_LENGTH'    => 0,
                        'DEFAULT_VALUE' => '',
                    ],
                'EDIT_FORM_LABEL'   =>
                    [
                        'en' => 'TYPE ACTION',
                        'ru' => 'TYPE ACTION',
                    ],
                'LIST_COLUMN_LABEL' =>
                    [
                        'en' => 'TYPE ACTION',
                        'ru' => 'TYPE ACTION',
                    ],
                'LIST_FILTER_LABEL' =>
                    [
                        'en' => 'TYPE ACTION',
                        'ru' => 'TYPE ACTION',
                    ],
                'ERROR_MESSAGE'     =>
                    [
                        'en' => '',
                        'ru' => '',
                    ],
                'HELP_MESSAGE'      =>
                    [
                        'en' => 'TYPE ACTION',
                        'ru' => 'TYPE ACTION',
                    ],
            ]
        );
    }

    public function down() {
        //your code ...
    }
}
