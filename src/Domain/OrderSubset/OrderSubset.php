<?php

declare(strict_types=1);

namespace Rainbow\Discounts\Domain\OrderSubset;

use Money\Money;
use Rainbow\Discounts\Domain\OrderItem;

interface OrderSubset
{
	/**
	 * @return OrderItem[]
	 */
	public function getItems(): array;

	public function getTotal(): Money;
}
