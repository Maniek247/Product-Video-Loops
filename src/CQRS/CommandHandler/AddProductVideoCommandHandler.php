<?php

declare(strict_types=1);

namespace PrestaShop\Module\ProductVideoLoops\CQRS\CommandHandler;

use PrestaShop\Module\ProductVideoLoops\CQRS\Command\AddProductVideoCommand;
use PrestaShop\Module\ProductVideoLoops\Service\VideoManagementService;

/**
 * Handles @see AddProductVideoCommand
 */
final class AddProductVideoCommandHandler
{
    /**
     * @var VideoManagementService
     */
    private $videoManagementService;

    /**
     * @param VideoManagementService $videoManagementService
     */
    public function __construct(VideoManagementService $videoManagementService)
    {
        $this->videoManagementService = $videoManagementService;
    }

    /**
     * This method will be triggered when related command is dispatched
     * Note - product form data handler create() method is a little unique
     *
     * @param AddProductVideoCommand $command
     *
     * @see ProductFormDataHandler::create()
     *
     * It will create the product with couple required fields and then call the Add method,
     * so you don't actually need to hook on ProductFormDataHandler::create() method
     */
    public function handle(AddProductVideoCommand $command): void
    {
        $productId = $command->getProductId()->getValue();
        $uploadedFile = $command->getUploadedFile();
        $this->videoManagementService->addVideoToProduct($productId, $uploadedFile);
    }
}
