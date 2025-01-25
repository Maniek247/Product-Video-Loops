<?php

declare(strict_types=1);

use PrestaShop\Module\ProductVideoLoops\Entity\CustomCombination;
use PrestaShop\Module\ProductVideoLoops\Entity\CustomProduct;
use PrestaShop\Module\ProductVideoLoops\Form\Modifier\CombinationFormModifier;
use PrestaShop\Module\ProductVideoLoops\Form\Modifier\ProductFormModifier;
use PrestaShop\Module\ProductVideoLoops\Install\Installer;
use PrestaShop\PrestaShop\Core\Domain\Product\Combination\ValueObject\CombinationId;
use PrestaShop\PrestaShop\Core\Domain\Product\ValueObject\ProductId;
use Symfony\Component\Templating\EngineInterface;

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

class ProductVideoLoops extends Module
{
    public function __construct()
    {
        $this->name = 'productvideoloops';
        $this->author = 'Adam Mańko';
        $this->version = '1.0.0';
        $this->ps_versions_compliancy = ['min' => '8.1.0', 'max' => _PS_VERSION_];

        parent::__construct();

        $this->displayName = $this->trans('Product Video Loops', [], 'Modules.Productvideoloops.Config');
        $this->description = $this->trans('Add product video loop viewed as product thumbnail', [], 'Modules.Productvideoloops.Config');
    }

    /**
     * @return bool 
     */
    public function install()
    {
        if (!parent::install()) {
            return false;
        }

        $installer = new Installer();

        return $installer->install($this);
    }

    /**
     * @return bool
     */
    public function uninstall()
    {
        if (!parent::uninstall()) {
            return false;
        }

        $installer = new Installer();

        return $installer->uninstall($this);
    }

    /**
     * @see https://devdocs.prestashop.com/8/modules/creation/module-translation/new-system/#translating-your-module
     *
     * @return bool
     */
    public function isUsingNewTranslationSystem(): bool
    {
        return true;
    }

    /**
     * Modify product form builder
     *
     * @param array $params
     */
    public function hookActionProductFormBuilderModifier(array $params): void
    {
        /** @var ProductFormModifier $productFormModifier */
        $productFormModifier = $this->get(ProductFormModifier::class);
        $productId = isset($params['id']) ? new ProductId((int) $params['id']) : null;

        $productFormModifier->modify($productId, $params['form_builder']);
    }

    /**
     * Hook to display configuration related to the module in the Modules extra tab in product page.
     *
     * @param array $params
     *
     * @return string
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function hookDisplayAdminProductsExtra(array $params): string
    {
        $productId = $params['id_product'];
        $customProduct = new CustomProduct($productId);

        /** @var EngineInterface $twig */
        
        $twig = $this->get('twig');

        return $twig->render('@Modules/productvideoloops/views/templates/admin/product_video_module.html.twig', [
            'customProduct' => $customProduct,
        ]);
    }

    /**
     * Hook that modifies the combination form structure.
     *
     * @param array $params
     */
    public function hookActionCombinationFormFormBuilderModifier(array $params): void
    { 
        /** @var CombinationFormModifier $productFormModifier */
        $productFormModifier = $this->get(CombinationFormModifier::class);
        $combinationId = isset($params['id']) ? new CombinationId((int) $params['id']) : null;

        $productFormModifier->modify($combinationId, $params['form_builder']);
    }

    /**
     * Hook called after form is submitted and combination is updated, custom data is updated here.
     *
     * @param array $params
     */
    public function hookActionAfterUpdateCombinationFormFormHandler(array $params): void
    {
        $combinationId = $params['form_data']['id'];
        $customCombination = new CustomCombination($combinationId);
        $customCombination->filename = $params['form_data']['demo_module_custom_field'] ?? '';

        if (empty($customCombination->id)) {
            // If custom is not found it has not been created yet, so we force its ID to match the combination ID
            $customCombination->id = $combinationId;
            $customCombination->force_id = true;
            $customCombination->add();
        } else {
            $customCombination->update();
        }
    }

    public function hookActionAfterUpdateProductFormHandler(array $params): void
    {
        return;
    }
}
