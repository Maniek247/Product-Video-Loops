<?php

declare(strict_types=1);

namespace PrestaShop\Module\ProductVideoLoops\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use PrestaShop\Module\ProductVideoLoops\Repository\ProductVideoRepository;
use PrestaShop\Module\ProductVideoLoops\Service\VideoUploader;

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
        // 1. Upload pliku - dostaniemy finalną nazwę
        $filename = $this->videoUploader->upload($file);

        // 2. Zapis w bazie
        $this->productVideoRepository->saveVideoInfoToDb($productId, $filename);
    }
}
