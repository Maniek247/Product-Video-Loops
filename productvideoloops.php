<?php

declare(strict_types=1);

use PrestaShop\Module\ProductVideoLoops\Install\Installer;
use PrestaShop\Module\ProductVideoLoops\Entity\ProductVideo;
use PrestaShop\Module\ProductVideoLoops\Factory\ProductVideoFactory;
use Symfony\Component\Templating\EngineInterface;
use PrestaShop\Module\ProductVideoLoops\Form\Modifier\ProductFormModifier;
use PrestaShop\Module\ProductVideoLoops\Form\Modifier\CombinationFormModifier;
use PrestaShop\Module\ProductVideoLoops\Repository\ProductVideoRepository;
use PrestaShop\Module\ProductVideoLoops\CQRS\QueryHandler\GetProductVideoQueryHandler;
use PrestaShop\Module\ProductVideoLoops\Subscriber\FrontControllerHookSubscriber;
use PrestaShop\Module\ProductVideoLoops\Subscriber\ProductsListingHookSubscriber;

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
        /** @var ProductVideoFactory $productVideoFactory */
        $productVideoFactory = $this->get(ProductVideoFactory::class);

        $productId = $params['id_product'];
        $productVideo = $productVideoFactory->createProductVideo($productId);
        $imgDir = _PS_BASE_URL_.__PS_BASE_URI__.'img/';

        /** @var EngineInterface $twig */
        
        $twig = $this->get('twig');

        return $twig->render('@Modules/productvideoloops/views/templates/admin/product_video_module.html.twig', [
            'productVideo' => $productVideo,
            'imgDir' => $imgDir,
        ]);
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
        $idProduct = !empty($params['id']) ? (int) $params['id'] : null;

        $productFormModifier->modify($idProduct, $params['form_builder']);
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
       // $combinationId = isset($params['id']) ? new CombinationId((int) $params['id']) : null;

        $productFormModifier->modify(/* $combinationId, */$params['form_builder']);
    }

    /**
     * Modifies product data before page final render.
     *
     * @param array $params
     */
    public function hookActionPresentProduct(array $params): void
    {
        $productVideoFactory = new ProductVideoFactory();
        
        $repo = new ProductVideoRepository($productVideoFactory);
        $handler = new GetProductVideoQueryHandler($repo);

        $subscriber = new FrontControllerHookSubscriber($handler);
        $subscriber->onActionPresentProduct($params);
    }

    /**
     * Modifies product data before page final render.
     *
     * @param array $params
     */
    public function hookActionPresentProductListing(array $params): void
    {
        $productVideoFactory = new ProductVideoFactory();

        $repo = new ProductVideoRepository($productVideoFactory);
        $handler = new GetProductVideoQueryHandler($repo);

        $subscriber = new ProductsListingHookSubscriber($handler);
        $subscriber->onActionPresentProductListing($params);
    }
}
