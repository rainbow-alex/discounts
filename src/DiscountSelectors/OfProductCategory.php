<?php

namespace Rainbow\Discounts\DiscountSelectors;

use Rainbow\Discounts\Order;

class OfProductCategory implements DiscountSelector
{
	private string $categoryId;

	public function __construct(string $categoryId)
	{
		$this->categoryId = $categoryId;
	}

	public function selectItems(Order $order)
	{
		$items = [];
		foreach ($order->getItems() as $item)
		{
			if ($item->getCategoryId() === $this->categoryId)
			{
				$items[] = $item;
			}
		}
		if ($items)
		{
			yield $items;
		}
	}
}
