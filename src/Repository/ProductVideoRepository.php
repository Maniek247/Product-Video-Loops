<?php

declare(strict_types=1);

namespace PrestaShop\Module\ProductVideoLoops\Repository;

use PrestaShop\Module\ProductVideoLoops\Entity\ProductVideo;
use PrestaShop\Module\ProductVideoLoops\Factory\ProductVideoFactory;

final class ProductVideoRepository
{
    /**
     * @var ProductVideoFactory
     */
    private $videoFactory;

    /**
     * @param ProductVideoFactory $videoFactory
     */
    public function __construct(ProductVideoFactory $videoFactory)
    {
        $this->videoFactory = $videoFactory;
    }

    /**
     * Saves (creates or updates) the product video information in the database
     *
     * @param int $productId
     * @param string $filename
     *
     * @return void
     */
    public function saveVideoInfoToDb(int $productId, string $filename): void
    {
        $videoObj = $this->videoFactory->createProductVideo($productId);

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

    /**
     * Retrieves the ProductVideo entity for a given product ID
     *
     * @param int $productId
     *
     * @return ProductVideo|null The ProductVideo entity if found, null otherwise
     */
    public function getProductVideo(int $productId): ?ProductVideo
    {
        $video = $this->videoFactory->createProductVideo($productId);
        
        if (empty($video->id)) {
            return null;
        }

        return $video;
    }

    /**
     * Deletes video information from database for a given product ID
     *
     * @param int $productId
     *
     * @return void
     */
    public function deleteVideoInfoFromDb(int $productId): void
    {
        $video = $this->videoFactory->createProductVideo($productId);
        if (!empty($video->id)) {
            $video->delete();
        }
    }
}
