<?php

namespace Rainbow\Discounts\Domain\DiscountEffects;

use Rainbow\Discounts\Domain\Order;
use Rainbow\Discounts\Domain\OrderSubset\ItemsSubset;
use Rainbow\Discounts\Domain\OrderSubset\OrderSubset;

/**
 * Applies another effect to every nth unit of each item.
 */
class EveryNUnits implements DiscountEffect
{
	private int $n;
	private DiscountEffect $effect;

	public function __construct(int $n, DiscountEffect $effect)
	{
		$this->n = $n;
		$this->effect = $effect;
	}

	public function apply(Order $order, OrderSubset $subset): void
	{
		foreach ($subset->getItems() as $item)
		{
			for ($i = 0; $i < \floor($item->getQuantity() / $this->n); $i++)
			{
				$this->effect->apply($order, new ItemsSubset([$item]));
			}
		}
	}
}
