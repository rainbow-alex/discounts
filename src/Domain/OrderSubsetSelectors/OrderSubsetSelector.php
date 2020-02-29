<?php

namespace Rainbow\Discounts\Domain\OrderSubsetSelectors;

use Rainbow\Discounts\Domain\Order;
use Rainbow\Discounts\Domain\OrderSubset\OrderSubset;

interface OrderSubsetSelector
{
	public function select(Order $order): ?OrderSubset;
}
