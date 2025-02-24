<?php

declare(strict_types=1);

namespace PrestaShop\Module\ProductVideoLoops\Service;

use PrestaShop\Module\ProductVideoLoops\Service\VideoUploader;
use PrestaShop\Module\ProductVideoLoops\Service\VideoDeleter;
use PrestaShop\Module\ProductVideoLoops\Repository\ProductVideoRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class VideoManagementService
{
    private $videoUploader;
    private $videoDeleter;
    private $productVideoRepository;

    public function __construct(
        VideoUploader $videoUploader,
        VideoDeleter $videoDeleter,
        ProductVideoRepository $productVideoRepository
    ) {
        $this->videoUploader = $videoUploader;
        $this->videoDeleter = $videoDeleter;
        $this->productVideoRepository = $productVideoRepository;
    }

    public function addVideoToProduct(int $productId, UploadedFile $file): void
    {
        $filename = $this->videoUploader->upload($file);

        $this->productVideoRepository->saveVideoInfoToDb($productId, $filename);
    }

    public function deleteProductVideo(int $productId): void
    {
        $this->videoDeleter->delete($productId);

        $this->productVideoRepository->deleteVideoInfoFromDb($productId);
    }
}
