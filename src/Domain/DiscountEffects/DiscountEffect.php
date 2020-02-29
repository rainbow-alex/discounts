<?php

namespace Rainbow\Discounts\Domain\DiscountEffects;

use Rainbow\Discounts\Domain\Order;
use Rainbow\Discounts\Domain\OrderSubset\OrderSubset;

interface DiscountEffect
{
	public function apply(Order $order, OrderSubset $subset): void;
}
