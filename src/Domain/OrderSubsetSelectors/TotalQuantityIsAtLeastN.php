<?php

namespace Rainbow\Discounts\Domain\OrderSubsetSelectors;

use Rainbow\Discounts\Domain\Order;
use Rainbow\Discounts\Domain\OrderSubset\OrderSubset;

class TotalQuantityIsAtLeastN implements OrderSubsetSelector
{
	private int $n;
	private OrderSubsetSelector $selector;

	public function __construct(int $n, OrderSubsetSelector $selector)
	{
		$this->selector = $selector;
		$this->n = $n;
	}

	public function select(Order $order): ?OrderSubset
	{
		$subset = $this->selector->select($order);
		if (!$subset)
		{
			return null;
		}

		$totalQuantity = 0;
		foreach ($subset->getItems() as $item)
		{
			$totalQuantity += $item->getQuantity();
		}
		if ($totalQuantity >= $this->n)
		{
			return $subset;
		}
		else
		{
			return null;
		}
	}
}
