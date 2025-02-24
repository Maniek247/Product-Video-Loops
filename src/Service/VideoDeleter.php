<?php

declare(strict_types=1);

namespace PrestaShop\Module\ProductVideoLoops\Service;

use PrestaShop\Module\ProductVideoLoops\Repository\ProductVideoRepository;
use PrestaShopException;

class VideoDeleter
{
    private $productVideoRepository;

    private $linkBuilderService;

    public function __construct(ProductVideoRepository $productVideoRepository, LinkBuilderService $linkBuilderService)
    {
        $this->productVideoRepository = $productVideoRepository;
        $this->linkBuilderService = $linkBuilderService;
    }

    /**
     * Delete a video file.
     *
     * @throws PrestaShopException If video does not exist
     */
    public function delete(int $productId): void
    {
        $video = $this->productVideoRepository->getProductVideo($productId);
        if (!$video) {
            return;
        }

        $folderPath = $this->linkBuilderService->buildVideoFolderPath();
        $fullPath = $folderPath . $video->filename;
        if (file_exists($fullPath)) {
            @unlink($fullPath);
        }
    }
}
