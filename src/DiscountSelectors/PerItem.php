<?php

namespace Rainbow\Discounts\DiscountSelectors;

use Rainbow\Discounts\Order;

class PerItem implements DiscountSelector
{
	private DiscountSelector $selector;

	public function __construct(DiscountSelector $selector)
	{
		$this->selector = $selector;
	}

	public function selectItems(Order $order)
	{
		foreach ($this->selector->selectItems($order) as $items)
		{
			foreach ($items as $item)
			{
				yield [$item];
			}
		}
	}
}
