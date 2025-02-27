<?php

declare(strict_types=1);

namespace PrestaShop\Module\ProductVideoLoops\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use PrestaShop\Module\ProductVideoLoops\Repository\ProductVideoRepository;
use Symfony\Component\String\Slugger\AsciiSlugger;
use PrestaShop\Module\ProductVideoLoops\Service\LinkBuilderService;

class VideoUploader
{
    /**
     * @var ProductVideoRepository
     */
    private $productVideoRepository;

    /**
     * @var AsciiSlugger
     */
    private $slugger;

    /**
     * @var LinkBuilderService
     */
    private $linkBuilderService;

    /**
     * @param ProductVideoRepository $productVideoRepository
     * @param AsciiSlugger $slugger
     * @param LinkBuilderService $linkBuilderService
     */
    public function __construct(ProductVideoRepository $productVideoRepository, 
        AsciiSlugger $slugger, 
        LinkBuilderService $linkBuilderService
    ){
        $this->productVideoRepository = $productVideoRepository;
        $this->slugger = $slugger;
        $this->linkBuilderService = $linkBuilderService;
    }

    /**
     * Uploads a video file to folder
     *
     * This method generates a safe and unique file name based on the original name using a slugger. 
     * Then moves the file to destination directory.
     * If the directory does not exist, it attempts to create it.
     *
     * @param UploadedFile $uploadedFile
     *
     * @return string Unique and safe final file name
     */
    public function upload(UploadedFile $uploadedFile): string
    {

        $originalName = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $safeName = $this->slugger->slug($originalName);
        $safeName = mb_substr((string)$safeName, 0, 70);

        $extension = $uploadedFile->guessExtension();

        $finalFileName = uniqid() . '-' . $safeName . '.' . $extension;

        $folderPath = $this->linkBuilderService->buildVideoFolderPath();
        if (!is_dir($folderPath)) {
            @mkdir($folderPath, 0777, true);
        }

        //TODO: Exception if file can't be moved?
        $uploadedFile->move($folderPath, $finalFileName);

        return $finalFileName;
    }
}
