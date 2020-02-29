<?php

declare(strict_types=1);

namespace Rainbow\Discounts\Domain;

interface DiscountRuleRepository
{
	/**
	 * @return DiscountRule[]
	 */
	public function getAll(): array;
}
