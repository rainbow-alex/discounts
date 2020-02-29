<?php

declare(strict_types=1);

namespace Rainbow\Discounts\Domain\OrderSubsetSelectors;

use Money\Money;
use Rainbow\Discounts\Domain\Order;
use Rainbow\Discounts\Domain\OrderSubset\OrderSubset;

class TotalIsGreaterThan implements OrderSubsetSelector
{
	private Money $min;
	private OrderSubsetSelector $selector;

	public function __construct(Money $min, OrderSubsetSelector $selector)
	{
		$this->min = $min;
		$this->selector = $selector;
	}

	public function select(Order $order): ?OrderSubset
	{
		$subset = $this->selector->select($order);
		if ($subset && $subset->getTotal()->greaterThan($this->min))
		{
			return $subset;
		}
		else
		{
			return null;
		}
	}
}
