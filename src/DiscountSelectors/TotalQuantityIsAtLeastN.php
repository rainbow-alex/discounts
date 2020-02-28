<?php

namespace Rainbow\Discounts\DiscountSelectors;

use Rainbow\Discounts\Order;

class TotalQuantityIsAtLeastN implements DiscountSelector
{
	private int $n;
	private DiscountSelector $selector;

	public function __construct(int $n, DiscountSelector $selector)
	{
		$this->selector = $selector;
		$this->n = $n;
	}

	public function selectItems(Order $order)
	{
		foreach ($this->selector->selectItems($order) as $selection)
		{
			$totalQuantity = 0;
			foreach ($selection as $item)
			{
				$totalQuantity += $item->getQuantity();
			}
			if ($totalQuantity >= $this->n)
			{
				yield $selection;
			}
		}
	}
}
