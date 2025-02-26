<?php

declare(strict_types=1);

use PrestaShop\Module\ProductVideoLoops\Install\Installer;
use PrestaShop\Module\ProductVideoLoops\Factory\ProductVideoFactory;
use PrestaShop\Module\ProductVideoLoops\Form\Modifier\ProductFormModifier;
use PrestaShop\Module\ProductVideoLoops\Repository\ProductVideoRepository;
use PrestaShop\Module\ProductVideoLoops\CQRS\QueryHandler\GetProductVideoQueryHandler;
use PrestaShop\Module\ProductVideoLoops\Service\LinkBuilderService;
use PrestaShop\Module\ProductVideoLoops\Subscriber\PresentProductHookSubscriber;
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

        $this->displayName = $this->trans('Product Video Loops', [], 'Modules.Productvideoloops.Productvideoloops');
        $this->description = $this->trans('Set video loop as a product cover. Video loop will be displayed in catalog and product pages.', [], 'Modules.Productvideoloops.Productvideoloops');
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
        $idProduct = !empty($params['id']) ? (int) $params['id'] : null;

        $productFormModifier->modify($idProduct, $params['form_builder']);
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
        $linkBuilder = new LinkBuilderService;
        $subscriber = new PresentProductHookSubscriber($handler, $linkBuilder);
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
        $linkBuilder = new LinkBuilderService;
        $subscriber = new ProductsListingHookSubscriber($handler, $linkBuilder);
        $subscriber->onActionPresentProductListing($params);
    }

    /**
     * Register BO CSS file
     *
     * @param array $params
     */
    public function hookActionAdminControllerSetMedia(): void
    {
        $this->context->controller->addCSS($this->_path . 'views/css/productvideoloops.css', 'all');
    }

    /**
     * Register FO CSS file
     *
     * @param array $params
     */
    public function hookActionFrontControllerSetMedia(): void
    {
        $this->context->controller->addCSS($this->_path . 'views/css/video.css', 'all');
    }
}
