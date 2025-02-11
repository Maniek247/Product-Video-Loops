<?php

declare(strict_types=1);

namespace PrestaShop\Module\ProductVideoLoops\CQRS\QueryHandler;

use PrestaShop\Module\ProductVideoLoops\CQRS\Query\GetProductVideoQuery;
use PrestaShop\Module\ProductVideoLoops\Repository\ProductVideoRepository;
use PrestaShop\Module\ProductVideoLoops\Entity\ProductVideo;

final class GetProductVideoQueryHandler
{
    /**
     * @var ProductVideoRepository
     */
    private $productVideoRepository;

    public function __construct(ProductVideoRepository $productVideoRepository)
    {
        $this->productVideoRepository = $productVideoRepository;
    }

    /**
     * Handles the query to retrieve the product's video.
     *
     * @param GetProductVideoQuery $query
     * @return ProductVideo|null
     */
    public function handle(GetProductVideoQuery $query): ?ProductVideo
    {
        return $this->productVideoRepository->getProductVideo($query->getProductId());
    }
}
