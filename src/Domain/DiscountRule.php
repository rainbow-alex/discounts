<?php

declare(strict_types=1);

namespace Rainbow\Discounts\Domain;

use Rainbow\Discounts\Domain\DiscountEffects\DiscountEffect;
use Rainbow\Discounts\Domain\OrderSubsetSelectors\OrderSubsetSelector;

class DiscountRule
{
	private int $priority;
	private OrderSubsetSelector $selector;
	private DiscountEffect $effect;

	public function __construct(int $priority, OrderSubsetSelector $selector, DiscountEffect $effect)
	{
		$this->priority = $priority;
		$this->selector = $selector;
		$this->effect = $effect;
	}

	public function getPriority(): int
	{
		return $this->priority;
	}

	public function apply(Order $order): void
	{
		$subset = $this->selector->select($order);
		if ($subset)
		{
			$this->effect->apply($order, $subset);
		}
	}
}
