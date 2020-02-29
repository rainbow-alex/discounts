<?php

namespace Rainbow\Discounts\Domain\OrderSubsetSelectors;

use Rainbow\Discounts\Domain\Order;
use Rainbow\Discounts\Domain\OrderSubset\ItemsSubset;
use Rainbow\Discounts\Domain\OrderSubset\OrderSubset;

class ProductCategoryIs implements OrderSubsetSelector
{
	private string $categoryId;

	public function __construct(string $categoryId)
	{
		$this->categoryId = $categoryId;
	}

	public function select(Order $order): ?OrderSubset
	{
		$items = [];
		foreach ($order->getItems() as $item)
		{
			if ($item->getCategoryId() === $this->categoryId)
			{
				$items[] = $item;
			}
		}
		return $items ? new ItemsSubset($items) : null;
	}
}
