<?php

declare(strict_types=1);

namespace Rainbow\Discounts\Domain\OrderSubset;

use Money\Money;
use Rainbow\Discounts\Domain\Order;

/**
 * Note that this is not the same as an ItemsSubset of all items in an order.
 * This also takes into account order-level discounts for getTotal()
 */
class WholeOrder implements OrderSubset
{
	private Order $order;

	public function __construct(Order $order)
	{
		$this->order = $order;
	}

	public function getItems(): array
	{
		return $this->order->getItems();
	}

	public function getTotal(): Money
	{
		return $this->order->getTotal();
	}
}
