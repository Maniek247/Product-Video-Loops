<?php

declare(strict_types=1);

namespace PrestaShop\Module\ProductVideoLoops\Repository;

use PrestaShop\Module\ProductVideoLoops\Entity\ProductVideo;

final class ProductVideoRepository
{
    /**
     * @param int $productId
     * @param string $fileName
     * @throws PrestaShopException
     */
    public function saveVideoInfoToDb(int $productId, string $filename): void
    {
        $videoObj = new ProductVideo($productId);

        if (empty($videoObj->id)) {
            $videoObj->id = $productId;
            $videoObj->force_id = true;
            $videoObj->filename = $filename;
            $videoObj->date_add = date('Y-m-d H:i:s');
            $videoObj->date_upd = date('Y-m-d H:i:s');
            $videoObj->cover = 1;   //temporary hardcoded as true
            $videoObj->position = 1;    //temporary hardcoded as first position
            $videoObj->add();
        } else {
            $videoObj->filename = $filename;
            $videoObj->date_upd = date('Y-m-d H:i:s');
            $videoObj->update();
        }
    }

    public function getProductVideo(int $productId): ?ProductVideo
    {
        $video = new ProductVideo($productId);
        
        if (empty($video->id)) {
            return null;
        }

        return $video;
    }

}