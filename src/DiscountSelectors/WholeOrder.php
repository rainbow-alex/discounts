<?php

declare(strict_types=1);

namespace Rainbow\Discounts\DiscountSelectors;

use Rainbow\Discounts\Order;

class WholeOrder implements DiscountSelector
{
	public function selectItems(Order $order)
	{
		yield $order->getItems();
	}
}
