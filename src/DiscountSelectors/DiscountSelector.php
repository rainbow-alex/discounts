<?php

namespace Rainbow\Discounts\DiscountSelectors;

use Rainbow\Discounts\Order;
use Rainbow\Discounts\OrderItem;

interface DiscountSelector
{
	/**
	 * @return iterable|OrderItem[][]
	 */
	public function selectItems(Order $order);
}
