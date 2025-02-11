<?php

declare(strict_types=1);

namespace PrestaShop\Module\ProductVideoLoops\Install;

use Module;
use PrestaShopBundle\Install\SqlLoader;

class Installer
{
    /**
     * Module's installation entry point.
     *
     * @param \Module $module
     *
     * @return bool
     */
    public function install(\Module $module): bool
    {
        if (!$this->registerHooks($module)) {
            return false;
        }
        if (!$this->executeSqlFromFile($module->getLocalPath() . 'src/Install/install.sql')) {
            return false;
        }

        return true;
    }

    /**
     * @param \Module $module
     *
     * @return bool
     */
    public function uninstall(\Module $module): bool
    {
        return $this->executeSqlFromFile($module->getLocalPath() . 'src/Install/uninstall.sql');
    }

    /**
     * Register hooks for the module.
     *
     * @see https://devdocs.prestashop.com/8/modules/concepts/hooks/
     *
     * @param \Module $module
     *
     * @return bool
     */
    private function registerHooks(\Module $module): bool
    {
        $hooks = [
            'actionProductFormBuilderModifier',
            'actionCombinationFormFormBuilderModifier',
            'displayAdminProductsExtra',
            'actionPresentProduct',
            'displayProductAdditionalInfo',
            'displayOverrideTemplate',
        ];

        return (bool) $module->registerHook($hooks);
    }

    /**
     * @param string $filepath
     *
     * @return bool
     */
    private function executeSqlFromFile(string $filepath): bool
    {
        if (!file_exists($filepath)) {
            return true;
        }

        $allowedCollations = ['utf8mb4_general_ci', 'utf8mb4_unicode_ci'];
        $databaseCollation = \Db::getInstance()->getValue('SELECT @@collation_database');
        $sqlLoader = new SqlLoader();
        $sqlLoader->setMetaData([
            'PREFIX_' => _DB_PREFIX_,
            'ENGINE_TYPE' => _MYSQL_ENGINE_,
            'COLLATION' => (empty($databaseCollation) || !in_array($databaseCollation, $allowedCollations)) ? '' : 'COLLATE ' . $databaseCollation,
        ]);

        return $sqlLoader->parseFile($filepath);
    }
}
