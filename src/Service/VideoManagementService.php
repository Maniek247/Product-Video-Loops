<?php

declare(strict_types=1);

namespace PrestaShop\Module\ProductVideoLoops\Service;

use PrestaShop\Module\ProductVideoLoops\Service\VideoUploader;
use PrestaShop\Module\ProductVideoLoops\Repository\ProductVideoRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class VideoManagementService
{
    private $videoUploader;
    private $productVideoRepository;

    public function __construct(
        VideoUploader $videoUploader,
        ProductVideoRepository $productVideoRepository
    ) {
        $this->videoUploader = $videoUploader;
        $this->productVideoRepository = $productVideoRepository;
    }

    public function addVideoToProduct(int $productId, UploadedFile $file): void
    {
        $filename = $this->videoUploader->upload($file);

        $this->productVideoRepository->saveVideoInfoToDb($productId, $filename);
    }
}
