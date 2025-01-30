<?php

declare(strict_types=1);

namespace PrestaShop\Module\ProductVideoLoops\CQRS\CommandBuilder;

use PrestaShop\PrestaShop\Core\Domain\Product\ValueObject\ProductId;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use PrestaShop\PrestaShop\Core\Domain\Shop\ValueObject\ShopConstraint;
use PrestaShop\Module\ProductVideoLoops\CQRS\Command\AddProductVideoCommand;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\CommandBuilder\Product\ProductCommandsBuilderInterface;

/**
 * This class is responsible for building cqrs commands from product form data.
 * Once you tag this service as "core.product_command_builder" (check module services.yml)
 * the core ProductCommandsBuilder will start using this builder
 *
 * Don't forget you can also handle your custom fields like any other identifiable object
 * by using following product form hooks instead of CQRS commands builder:
 *  - actionAfterUpdateProductFormHandler
 *  - actionBeforeUpdateProductFormHandler
 */
final class ProductVideoCommandsBuilder implements ProductCommandsBuilderInterface
{
    public function buildCommands(ProductId $productId, array $formData, ShopConstraint $singleShopConstraint): array
    {        
        $command = null;
        if (isset($formData['description']['video']) && $formData['description']['video'] instanceof UploadedFile) {
            $uploadedFile = $formData['description']['video'];
            $command = new AddProductVideoCommand($productId->getValue(), $uploadedFile);
        }
        
        return null !== $command ? [$command] : []; 
    }
}
