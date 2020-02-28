<?php

declare(strict_types=1);

namespace Rainbow\Discounts;

use Money\Money;

class OrderItem
{
	private string $productId;
	private ?Product $product;
	private int $quantity;
	private Money $unitPrice;
	private Money $baseTotal;
	/** @var Discount[] */
	private array $itemDiscounts = [];

	public function __construct(string $productId, ?Product $product, int $quantity, Money $unitPrice)
	{
		$this->productId = $productId;
		assert(!$product || $productId === $product->getId());
		$this->product = $product;
		$this->quantity = $quantity;
		$this->unitPrice = $unitPrice;
		$this->baseTotal = $unitPrice->multiply($quantity);
	}

	public function getProductId(): string
	{
		return $this->productId;
	}

	public function getProduct(): ?Product
	{
		return $this->product;
	}

	public function getCategoryId(): ?string
	{
		return $this->product ? $this->product->getCategoryId() : null;
	}

	public function getQuantity(): int
	{
		return $this->quantity;
	}

	public function getUnitPrice(): Money
	{
		return $this->unitPrice;
	}

	/**
	 * @return Discount[]
	 */
	public function getItemDiscounts(): array
	{
		return $this->itemDiscounts;
	}

	public function addDiscount(Discount $discount): void
	{
		$this->itemDiscounts[] = $discount;
	}

	public function getTotal(): Money
	{
		$total = $this->baseTotal;
		foreach ($this->itemDiscounts as $discount)
		{
			$total = $total->subtract($discount->getValue());
		}
		return $total;
	}
}
