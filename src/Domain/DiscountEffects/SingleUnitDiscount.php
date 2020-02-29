<?php

declare(strict_types=1);

namespace Rainbow\Discounts\Domain\DiscountEffects;

use Rainbow\Discounts\Domain\Discount;
use Rainbow\Discounts\Domain\Order;
use Rainbow\Discounts\Domain\OrderSubset\OrderSubset;

/**
 * Apply a discount on a single unit for each item.
 */
class SingleUnitDiscount implements DiscountEffect
{
	private string $discountFactor;

	public function __construct(string $discountFactor)
	{
		$this->discountFactor = $discountFactor;
	}

	public function apply(Order $order, OrderSubset $subset): void
	{
		foreach ($subset->getItems() as $item)
		{
			$item->addDiscount(
				new Discount($item->getUnitPrice()->multiply($this->discountFactor))
			);
		}
	}
}
