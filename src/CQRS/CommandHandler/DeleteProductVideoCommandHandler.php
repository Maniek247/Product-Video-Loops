<?php

declare(strict_types=1);

namespace PrestaShop\Module\ProductVideoLoops\CQRS\CommandHandler;

use PrestaShop\Module\ProductVideoLoops\CQRS\Command\DeleteProductVideoCommand;
use PrestaShop\Module\ProductVideoLoops\Service\VideoManagementService;

final class DeleteProductVideoCommandHandler
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
     *
     * @param DeleteProductVideoCommand $command
     * 
     * @return void
     */
    public function handle(DeleteProductVideoCommand $command): void
    {
        $productId = $command->getProductId();
        $this->videoManagementService->deleteProductVideo($productId);
    }
}
