<?php

declare(strict_types=1);

namespace PrestaShop\Module\ProductVideoLoops\CQRS\Command;

use PrestaShop\Module\ProductVideoLoops\CQRS\CommandBuilder\CustomProductCommandsBuilder;
use PrestaShop\PrestaShop\Core\Domain\Product\ValueObject\ProductId;

/**
 * Product form is quite big so we have multiple command handlers that saves the fields and performs other required actions
 * This means you can also add your command handler to handle some custom fields added by your module.
 * To do that you will need to create your commandsBuilder which will build commands from product form data
 *
 * @see CustomProductCommandsBuilder
 *
 * It is example command, you can call it whatever you need depending on use case.
 * Command is used to pass the information and call related handler, it doesnt actually do anything by itself.
 * The name of command should reflect the actual use case and should be handled by a handler
 * @see UpdateCustomProductCommandHandler
 */
final class UpdateCustomProductCommand
{
    /**
     * @var ProductId
     */
    private $productId;

    /**
     * @var string
     */
    private $customerField = '';

    /**
     * @param int $productId
     */
    public function __construct(int $productId)
    {
        $this->productId = new ProductId($productId);
    }

    /**
     * @return ProductId
     */
    public function getProductId(): ProductId
    {
        return $this->productId;
    }

    /**
     * @return string
     */
    public function getCustomerField(): string
    {
        return $this->customerField;
    }

    /**
     * @param string $customerField
     */
    public function setCustomerField(string $customerField): self
    {
        $this->customerField = $customerField;

        return $this;
    }
}
