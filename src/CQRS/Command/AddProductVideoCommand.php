<?php

declare(strict_types=1);

namespace PrestaShop\Module\ProductVideoLoops\CQRS\Command;

use PrestaShop\Module\ProductVideoLoops\CQRS\CommandBuilder\ProductVideoCommandsBuilder;
use PrestaShop\PrestaShop\Core\Domain\Product\ValueObject\ProductId;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Product form is quite big so we have multiple command handlers that saves the fields and performs other required actions
 * This means you can also add your command handler to handle some custom fields added by your module.
 * To do that you will need to create your commandsBuilder which will build commands from product form data
 *
 * @see ProductVideoCommandsBuilder
 *
 * It is example command, you can call it whatever you need depending on use case.
 * Command is used to pass the information and call related handler, it doesnt actually do anything by itself.
 * The name of command should reflect the actual use case and should be handled by a handler
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
     * @var string
     */
    private $customerField = '';

    /**
     * @param int $productId
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
