<?php

declare(strict_types=1);

namespace PrestaShop\Module\ProductVideoLoops\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use PrestaShop\Module\ProductVideoLoops\Repository\ProductVideoRepository;
use Symfony\Component\String\Slugger\AsciiSlugger;
use PrestaShopException;

class VideoUploader
{
    private $productVideoRepository;

    public function __construct(ProductVideoRepository $productVideoRepository)
    {
        $this->productVideoRepository = $productVideoRepository;
    }
    /**
     * Uploads a video file.
     *
     * Validates that the uploaded file is in the allowed format, generates a safe and unique
     * file name based on the original name using a slugger. Then moves the file to destination directory.
     * Attemps to create destination directory if it does not exist.
     *
     * @param UploadedFile $uploadedFile
     * @param AsciiSlugger $slugger Generates URL-friendly file name
     *
     * @return string Unique and safe final file name
     * @throws PrestaShopException If invalid format or destination directory cannot be created
     */
    public function upload(UploadedFile $uploadedFile, AsciiSlugger $slugger): string
    {

        $allowedExtensions = 'mp4';
        $extension = $uploadedFile->guessExtension();
        //TODO: test this exception
        if ($extension != $allowedExtensions) {
            throw new PrestaShopException('Nieprawidłowy format pliku.');
        }

        $originalName = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $safeName = $slugger->slug($originalName);
        $safeName = mb_substr($safeName, 0, 70);
        $extension = $uploadedFile->guessExtension();
        $finalFileName = uniqid() . '-' . $safeName . '.' . $extension;

        $destination = _PS_CORE_IMG_DIR_ . 'videoloops/';

        if (!is_dir($destination)) {
            @mkdir($destination, 0777, true);
        }

        //TODO: test this exception
        if (!is_dir($destination) && !mkdir($destination, 0777, true) && !is_dir($destination)) {
            throw new PrestaShopException('Nie można utworzyć katalogu docelowego.');
        }


        $uploadedFile->move($destination, $finalFileName);

        return $finalFileName;
    }
}
