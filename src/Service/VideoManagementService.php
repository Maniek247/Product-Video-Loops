<?php

declare(strict_types=1);

namespace PrestaShop\Module\ProductVideoLoops\Service;

use PrestaShop\Module\ProductVideoLoops\Service\VideoUploader;
use PrestaShop\Module\ProductVideoLoops\Service\VideoDeleter;
use PrestaShop\Module\ProductVideoLoops\Repository\ProductVideoRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class VideoManagementService
{
    /**
     * @var VideoUploader
     */
    private $videoUploader;

    /**
     * @var VideoDeleter
     */
    private $videoDeleter;

    /**
     * @var ProductVideoRepository
     */
    private $productVideoRepository;

    /**
     * @param VideoUploader $videoUploader
     * @param VideoDeleter $videoDeleter
     * @param ProductVideoRepository $productVideoRepository
     */
    public function __construct(
        VideoUploader $videoUploader,
        VideoDeleter $videoDeleter,
        ProductVideoRepository $productVideoRepository
    ) {
        $this->videoUploader = $videoUploader;
        $this->videoDeleter = $videoDeleter;
        $this->productVideoRepository = $productVideoRepository;
    }

    /**
     * Adds the video's database record and uploads the file to the filesystem
     *
     * @param int $productId
     * @param UploadedFile $file The video file to be uploaded
     *
     * @return void
     */
    public function addVideoToProduct(int $productId, UploadedFile $file): void
    {
        $filename = $this->videoUploader->upload($file);

        $this->productVideoRepository->saveVideoInfoToDb($productId, $filename);
    }

    /**
     * Removes the video's database record and deletes the file from the filesystem
     *
     * @param int $productId The ID of the product
     *
     * @return void
     */
    public function deleteProductVideo(int $productId): void
    {
        $this->videoDeleter->delete($productId);

        $this->productVideoRepository->deleteVideoInfoFromDb($productId);
    }
}
