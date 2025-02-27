<?php

declare(strict_types=1);

namespace PrestaShop\Module\ProductVideoLoops\CQRS\Command;

final class DeleteProductVideoCommand
{
    /**
     * @var int
     */
    private $productId;

    /**
     * @param int $productId
     */
    public function __construct(int $productId)
    {
        $this->productId = $productId;
    }
    
    /**
     * @return int
     */
    public function getProductId(): int
    {
        return $this->productId;
    }
}
