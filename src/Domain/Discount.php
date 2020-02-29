<?php

declare(strict_types=1);

namespace Rainbow\Discounts\Domain;

use Money\Money;

class Discount
{
	private Money $value;

	public function __construct(Money $value)
	{
		$this->value = $value;
	}

	public function getValue(): Money
	{
		return $this->value;
	}
}
