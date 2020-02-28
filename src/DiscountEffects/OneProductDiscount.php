<?php

declare(strict_types=1);

namespace Rainbow\Discounts\DiscountEffects;

use Rainbow\Discounts\Discount;
use Rainbow\Discounts\Order;
use Rainbow\Discounts\OrderItem;

class OneProductDiscount implements DiscountEffect
{
	private string $discountFactor;

	public function __construct(string $discountFactor)
	{
		$this->discountFactor = $discountFactor;
	}

	/**
	 * @param OrderItem[] $selectedItems
	 */
	public function apply(Order $order, array $selectedItems): void
	{
		foreach ($selectedItems as $item)
		{
			$item->addDiscount(
				new Discount($item->getUnitPrice()->multiply($this->discountFactor))
			);
		}
	}
}
