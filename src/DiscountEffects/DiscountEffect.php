<?php

namespace Rainbow\Discounts\DiscountEffects;

use Rainbow\Discounts\Order;
use Rainbow\Discounts\OrderItem;

interface DiscountEffect
{
	/**
	 * @param OrderItem[] $selectedItems
	 */
	public function apply(Order $order, array $selectedItems): void;
}
