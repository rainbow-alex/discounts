<?php

namespace Rainbow\Discounts\DiscountEffects;

use Rainbow\Discounts\Order;
use Rainbow\Discounts\OrderItem;

class EveryNProducts implements DiscountEffect
{
	private int $n;
	private DiscountEffect $effect;

	public function __construct(int $n, DiscountEffect $effect)
	{
		$this->n = $n;
		$this->effect = $effect;
	}

	/**
	 * @param OrderItem[] $selectedItems
	 */
	public function apply(Order $order, array $selectedItems): void
	{
		foreach ($selectedItems as $item)
		{
			for ($i = 0; $i < $item->getQuantity(); $i += $this->n)
			{
				$this->effect->apply($order, [$item]);
			}
		}
	}
}
