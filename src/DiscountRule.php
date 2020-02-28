<?php

declare(strict_types=1);

namespace Rainbow\Discounts;

use Rainbow\Discounts\DiscountEffects\DiscountEffect;
use Rainbow\Discounts\DiscountSelectors\DiscountSelector;

class DiscountRule
{
	private int $priority;
	private DiscountSelector $selector;
	private DiscountEffect $effect;

	public function __construct(int $priority, DiscountSelector $selector, DiscountEffect $effect)
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
		$selections = $this->selector->selectItems($order);
		foreach ($selections as $selection)
		{
			$this->effect->apply($order, $selection);
		}
	}
}
