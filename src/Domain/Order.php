<?php

declare(strict_types=1);

namespace Rainbow\Discounts\Domain;

use Money\Currency;
use Money\Money;

class Order
{
	private string $id;
	private string $customerId;
	/** @var OrderItem[] */
	private array $items;
	/** @var Discount[] */
	private array $orderDiscounts = [];

	/**
	 * @param OrderItem[] $items
	 */
	public function __construct(string $id, string $customerId, array $items = [])
	{
		$this->id = $id;
		$this->customerId = $customerId;
		$this->items = $items;
	}

	public function getCurrency(): Currency
	{
		return new Currency("EUR");
	}

	public function getId(): string
	{
		return $this->id;
	}

	public function getCustomerId(): string
	{
		return $this->customerId;
	}

	/**
	 * @return OrderItem[]
	 */
	public function getItems(): array
	{
		return $this->items;
	}

	/**
	 * @return Discount[]
	 */
	public function getOrderDiscounts(): array
	{
		return $this->orderDiscounts;
	}

	public function addDiscount(Discount $discount): void
	{
		$this->orderDiscounts[] = $discount;
	}

	public function getTotal(): Money
	{
		$total = new Money(0, $this->getCurrency());
		foreach ($this->items as $item)
		{
			$total = $total->add($item->getTotal());
		}
		foreach ($this->orderDiscounts as $discount)
		{
			$total = $total->subtract($discount->getValue());
		}
		return $total;
	}
}
