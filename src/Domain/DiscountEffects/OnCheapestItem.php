<?php

declare(strict_types=1);

namespace Rainbow\Discounts\Domain\DiscountEffects;

use Rainbow\Discounts\Domain\Order;
use Rainbow\Discounts\Domain\OrderItem;
use Rainbow\Discounts\Domain\OrderSubset\ItemsSubset;
use Rainbow\Discounts\Domain\OrderSubset\OrderSubset;

/**
 * Applies another effect, but only to the cheapest item.
 */
class OnCheapestItem implements DiscountEffect
{
	private DiscountEffect $effect;

	public function __construct(DiscountEffect $effect)
	{
		$this->effect = $effect;
	}

	public function apply(Order $order, OrderSubset $subset): void
	{
		/** @var OrderItem|null $cheapest */
		$cheapest = null;
		foreach ($subset->getItems() as $item)
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
		$this->effect->apply($order, new ItemsSubset([$cheapest]));
	}
}
