<?php

declare(strict_types=1);

namespace Rainbow\Discounts\DiscountEffects;

use Rainbow\Discounts\Order;
use Rainbow\Discounts\OrderItem;

class CheapestItem implements DiscountEffect
{
	private DiscountEffect $effect;

	public function __construct(DiscountEffect $effect)
	{
		$this->effect = $effect;
	}

	/**
	 * @param OrderItem[] $selectedItems
	 */
	public function apply(Order $order, array $selectedItems): void
	{
		/** @var OrderItem|null $cheapest */
		$cheapest = null;
		foreach ($selectedItems as $item)
		{
			if (!$cheapest || $item->getUnitPrice()->lessThan($cheapest->getUnitPrice()))
			{
				$cheapest = $item;
			}
		}
		if (!$cheapest)
		{
			throw new \LogicException("Used " . __CLASS__ . " with a discount selector that returns an empty set");
		}
		$this->effect->apply($order, [$cheapest]);
	}
}
