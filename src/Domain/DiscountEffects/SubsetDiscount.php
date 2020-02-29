<?php

declare(strict_types=1);

namespace Rainbow\Discounts\Domain\DiscountEffects;

use Rainbow\Discounts\Domain\Discount;
use Rainbow\Discounts\Domain\Order;
use Rainbow\Discounts\Domain\OrderSubset\OrderSubset;

/**
 * Apply a discount to the whole subset.
 */
class SubsetDiscount implements DiscountEffect
{
	private string $discountFactor;

	public function __construct(string $discountFactor)
	{
		$this->discountFactor = $discountFactor;
	}

	public function apply(Order $order, OrderSubset $subset): void
	{
		$order->addDiscount(
			new Discount($subset->getTotal()->multiply($this->discountFactor))
		);
	}
}
