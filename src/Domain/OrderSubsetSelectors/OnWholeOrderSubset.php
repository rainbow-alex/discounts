<?php

declare(strict_types=1);

namespace Rainbow\Discounts\Domain\OrderSubsetSelectors;

use Rainbow\Discounts\Domain\Order;
use Rainbow\Discounts\Domain\OrderSubset\OrderSubset;
use Rainbow\Discounts\Domain\OrderSubset\WholeOrder;

class OnWholeOrderSubset implements OrderSubsetSelector
{
	public function select(Order $order): ?OrderSubset
	{
		return new WholeOrder($order);
	}
}
