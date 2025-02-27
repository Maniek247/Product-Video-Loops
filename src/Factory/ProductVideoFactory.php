<?php

declare(strict_types=1);

namespace PrestaShop\Module\ProductVideoLoops\Factory;

use PrestaShop\Module\ProductVideoLoops\Entity\ProductVideo;

class ProductVideoFactory
{
    /**
     * Creates ProductVideo based on specified product ID
     *
     * @param int $productId
     *
     * @return ProductVideo Created ProductVideo entity
     */
    public function createProductVideo(int $productId): ProductVideo
    {
        return new ProductVideo($productId);
    }    
}
