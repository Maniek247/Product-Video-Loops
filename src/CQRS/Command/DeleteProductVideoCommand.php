<?php

declare(strict_types=1);

namespace PrestaShop\Module\ProductVideoLoops\CQRS\Command;

final class DeleteProductVideoCommand
{
    private $productId;

    public function __construct(int $productId)
    {
        $this->productId = $productId;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }
}
