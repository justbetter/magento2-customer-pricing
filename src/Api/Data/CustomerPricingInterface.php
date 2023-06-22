<?php

namespace JustBetter\CustomerPricing\Api\Data;

interface CustomerPricingInterface
{
    public function getId(): float;

    public function setId(int  $id): void;

    public function getProductId(): float;

    public function setProductId(int $productId): void;

    public function getCustomerId(): float;

    public function setCustomerId(int $customerId): void;

    public function getQuantity(): float;

    public function setQuantity(float $quantity): void;

    public function getPrice(): float;

    public function setPrice(float $price): void;
}
