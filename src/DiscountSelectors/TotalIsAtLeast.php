<?php

declare(strict_types=1);

namespace Rainbow\Discounts\DiscountSelectors;

use Money\Money;
use Rainbow\Discounts\Order;
use Rainbow\Discounts\OrderItem;

class TotalIsAtLeast implements DiscountSelector
{
	private Money $min;
	private DiscountSelector $selector;

	public function __construct(Money $min, DiscountSelector $selector)
	{
		$this->min = $min;
		$this->selector = $selector;
	}

	public function selectItems(Order $order)
	{
		foreach ($this->selector->selectItems($order) as $selection)
		{
			// TODO
			if (!\array_udiff($order->getItems(), $selection, fn (OrderItem $a, OrderItem $b) => $a === $b ? 0 : -1))
			{
				$total = $order->getTotal();
			}
			else
			{
				$total = new Money(0, $order->getCurrency());
				foreach ($selection as $item)
				{
					$total += $item->getTotal();
				}
			}

			if ($total >= $this->min)
			{
				yield $selection;
			}
		}
	}
}
