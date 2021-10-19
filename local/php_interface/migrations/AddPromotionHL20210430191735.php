<?php

namespace Sprint\Migration;

class AddPromotionHL20210430191735 extends Version {
    protected $description = "";


    /**
     * @return bool|void
     * @throws Exceptions\HelperException
     */
    public function up() {
        $helper    = $this->getHelperManager();
        $hlblockId = $helper->Hlblock()->saveHlblock(
            [
                'NAME'       => 'VendorActionPrice',
                'TABLE_NAME' => 'vendor_action_price',
                'LANG'       =>
                    [
                        'ru' =>
                            [
                                'NAME' => 'Рекламные компании',
                            ],
                        'en' =>
                            [
                                'NAME' => 'Advertising companies ',
                            ],
                    ],
            ]
        );
        $helper->Hlblock()->saveField(
            $hlblockId,
            [
                'FIELD_NAME'        => 'UF_VENDOR_ID',
                'USER_TYPE_ID'      => 'integer',
                'XML_ID'            => 'UF_VENDOR_ID',
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
                        'MIN_VALUE'     => 0,
                        'MAX_VALUE'     => 0,
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
                'FIELD_NAME'        => 'UF_PRODUCT_ID',
                'USER_TYPE_ID'      => 'integer',
                'XML_ID'            => 'UF_PRODUCT_ID',
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
                        'MIN_VALUE'     => 0,
                        'MAX_VALUE'     => 0,
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
                'FIELD_NAME'        => 'UF_PRICE_CLICK',
                'USER_TYPE_ID'      => 'double',
                'XML_ID'            => 'UF_PRICE_CLICK',
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
                'FIELD_NAME'        => 'UF_PRICE_VIEW',
                'USER_TYPE_ID'      => 'double',
                'XML_ID'            => 'UF_PRICE_VIEW',
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
                'FIELD_NAME'        => 'UF_STATUS',
                'USER_TYPE_ID'      => 'string',
                'XML_ID'            => 'UF_STATUS',
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
                'FIELD_NAME'        => 'UF_BUDGET',
                'USER_TYPE_ID'      => 'double',
                'XML_ID'            => 'UF_BUDGET',
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
                'FIELD_NAME'        => 'UF_DATETIME_START',
                'USER_TYPE_ID'      => 'datetime',
                'XML_ID'            => 'UF_DATETIME_START',
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
                        'USE_SECOND'    => 'Y',
                        'USE_TIMEZONE'  => 'N',
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
