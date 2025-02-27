<?php

declare(strict_types=1);

namespace PrestaShop\Module\ProductVideoLoops\CQRS\Command;

use PrestaShop\PrestaShop\Core\Domain\Product\ValueObject\ProductId;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * This command represents an action to add a product video.
 * The command itself does not execute the action; it only carries 
 * the required data (product ID and the uploaded file) to its handler
 *
 * @see AddProductVideoCommandHandler
 */
final class AddProductVideoCommand
{
    /**
     * @var ProductId
     */
    private $productId;

    /**
     * @var UploadedFile
     */
    private $uploadedFile;

    /**
     * @param int $productId
     * @param UploadedFile $file
     */
    public function __construct(int $productId, UploadedFile $file)
    {
        $this->productId = new ProductId($productId);
        $this->uploadedFile = $file;
    }

    /**
     * @return ProductId
     */
    public function getProductId(): ProductId
    {
        return $this->productId;
    }

    /**
     * @return UploadedFile
     */
    public function getUploadedFile(): UploadedFile
    {
        return $this->uploadedFile;
    }
}
