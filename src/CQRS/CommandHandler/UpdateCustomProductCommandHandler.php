<?php

declare(strict_types=1);

namespace PrestaShop\Module\ProductVideoLoops\CQRS\CommandHandler;

use PrestaShop\Module\ProductVideoLoops\CQRS\Command\UpdateCustomProductCommand;
use PrestaShop\Module\ProductVideoLoops\Entity\CustomProduct;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataHandler\ProductFormDataHandler;

/**
 * Handles @see UpdateCustomProductCommand
 */
final class UpdateCustomProductCommandHandler
{
    /**
     * This method will be triggered when related command is dispatched
     * (more about cqrs https://devdocs.prestashop.com/8/development/architecture/domain/cqrs/)
     *
     * Note - product form data handler create() method is a little unique
     *
     * @param UpdateCustomProductCommand $command
     *
     * @see ProductFormDataHandler::create()
     *
     * It will create the product with couple required fields and then call the update method,
     * so you don't actually need to hook on ProductFormDataHandler::create() method
     */
    public function handle(UpdateCustomProductCommand $command): void
    {
        // Command handlers should contain as less logic as possible, that should be wrapped in dedicated services instead,
        // but for simplicity of example lets just leave the entity saving logic here
        $productId = $command->getProductId()->getValue();
        $customProduct = new CustomProduct($productId);

        $updatedFields = [];
        if (null !== $command->getCustomerField()) {
            $customProduct->filename = $command->getCustomerField();
            $updatedFields['filename'] = true;
        }

        if (empty($customProduct->id)) {
            // If custom is not found it has not been created yet, so we force its ID to match the product ID
            $customProduct->id = $productId;
            $customProduct->force_id = true;
            $customProduct->add();
        } else {
            // setFieldsToUpdate can be set to explicitly specify fields for update (other fields would not be updated)
            $customProduct->setFieldsToUpdate($updatedFields);
            $customProduct->update();
        }
    }
}
