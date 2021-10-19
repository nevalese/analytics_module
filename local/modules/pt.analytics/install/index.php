<?

Class pt_analytics extends CModule
{
    var $MODULE_ID = "pt.analytics";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_CSS;

    function  __construct()
    {
        $arModuleVersion = array();

        $path = str_replace("\\", "/", __FILE__);
        $path = substr($path, 0, strlen($path) - strlen("/index.php"));
        include($path."/version.php");

        if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion))
        {
            $this->MODULE_VERSION = $arModuleVersion["VERSION"];
            $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        }

        $this->MODULE_NAME = "Аналитика";
        $this->MODULE_DESCRIPTION = "Аналитика показов, кликов, переходов";
    }

    function InstallFiles()
    {

        /*
        CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/dv_module/install/components",
            $_SERVER["DOCUMENT_ROOT"]."/bitrix/components", true, true);
        */
        return true;
    }

    function UnInstallFiles()
    {
         /*
        DeleteDirFilesEx("/bitrix/components/dv");
         */
        return true;
    }

    function DoInstall()
    {
        global $DOCUMENT_ROOT, $APPLICATION;
        $this->InstallFiles();
        RegisterModule("pt.analytics");
        $APPLICATION->IncludeAdminFile("Установка модуля pt.analytics", $DOCUMENT_ROOT."/local/modules/pt.analytics/install/step.php");
    }

    function DoUninstall()
    {
        global $DOCUMENT_ROOT, $APPLICATION;
        $this->UnInstallFiles();
        UnRegisterModule("pt.analytics");
        $APPLICATION->IncludeAdminFile("Деинсталляция модуля pt.analytics", $DOCUMENT_ROOT."/local/modules/pt.analytics/install/unstep.php");
    }
}
?>