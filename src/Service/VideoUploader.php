<?php

declare(strict_types=1);

namespace PrestaShop\Module\ProductVideoLoops\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use PrestaShop\Module\ProductVideoLoops\Repository\ProductVideoRepository;
use PrestaShopException;

class VideoUploader
{
    private $productVideoRepository;

    public function __construct(ProductVideoRepository $productVideoRepository)
    {
        $this->productVideoRepository = $productVideoRepository;
    }

    /**
     * @param UploadedFile $uploadedFile
     * @param int $productId
     *
     * @return void
     * @throws PrestaShopException
     */
    public function upload(UploadedFile $uploadedFile): string
    {
        // 1. Wygeneruj nazwę pliku (możesz dodać slug, timestamp, cokolwiek)
        // np. oryginalna_nazwa + ID produktu
        $originalName = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $uploadedFile->guessExtension();
        $safeFileName = uniqid() . '-' . $originalName . '.' . $extension;

        $destination = _PS_CORE_IMG_DIR_ . 'videoloops/';

        if (!is_dir($destination)) {
            @mkdir($destination, 0777, true);
        }

        $uploadedFile->move($destination, $safeFileName);

        return $safeFileName;
        // 4. Zapisz informację w bazie (Twoja tabela `productvideoloops`)
        // $this->productVideoRepository->saveVideoInfoToDb($productId, $safeFileName);
    }
}
