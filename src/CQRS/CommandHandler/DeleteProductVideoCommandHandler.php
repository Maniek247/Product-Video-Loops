<?php

declare(strict_types=1);

namespace PrestaShop\Module\ProductVideoLoops\CQRS\CommandHandler;

use PrestaShop\Module\ProductVideoLoops\CQRS\Command\DeleteProductVideoCommand;
use PrestaShop\Module\ProductVideoLoops\Service\VideoManagementService;

final class DeleteProductVideoCommandHandler
{
    private $videoManagementService;

    public function __construct(VideoManagementService $videoManagementService)
    {
        $this->videoManagementService = $videoManagementService;
    }

    public function handle(DeleteProductVideoCommand $command): void
    {
        $productId = $command->getProductId();
        $this->videoManagementService->deleteProductVideo($productId);
    }
}
