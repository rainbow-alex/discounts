<?php

declare(strict_types=1);

namespace Rainbow\Discounts\Domain\OrderSubset;

use Money\Currency;
use Money\Money;
use Rainbow\Discounts\Domain\OrderItem;

class ItemsSubset implements OrderSubset
{
	/** @var OrderItem[] */
	private array $items;

	/**
	 * @param OrderItem[] $items
	 */
	public function __construct(array $items)
	{
		$this->items = $items;
	}

	public function getItems(): array
	{
		return $this->items;
	}

	public function getTotal(): Money
	{
		$total = new Money(0, new Currency("EUR"));
		foreach ($this->items as $item)
		{
			$total = $total->add($item->getTotal());
		}
		return $total;
	}
}
