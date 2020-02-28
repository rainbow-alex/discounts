<?php

declare(strict_types=1);

namespace Rainbow\Discounts\DiscountEffects;

use Money\Money;
use Rainbow\Discounts\Discount;
use Rainbow\Discounts\Order;
use Rainbow\Discounts\OrderItem;

// TODO renames
class SelectionTotalDiscount implements DiscountEffect
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
		$total = new Money(0, $order->getCurrency());
		foreach ($selectedItems as $item)
		{
			$total = $total->add($item->getTotal());
		}
		$order->addDiscount(
			new Discount($total->multiply($this->discountFactor))
		);
	}
}
