<?php

declare(strict_types=1);

namespace Rainbow\Discounts\Infrastructure;

use Money\Currency;
use Money\Money;
use Rainbow\Discounts\Domain\DiscountEffects\EveryNUnits;
use Rainbow\Discounts\Domain\DiscountEffects\OnCheapestItem;
use Rainbow\Discounts\Domain\DiscountEffects\SingleUnitDiscount;
use Rainbow\Discounts\Domain\DiscountEffects\SubsetDiscount;
use Rainbow\Discounts\Domain\DiscountRule;
use Rainbow\Discounts\Domain\DiscountRuleRepository;
use Rainbow\Discounts\Domain\OrderSubsetSelectors\OnWholeOrderSubset;
use Rainbow\Discounts\Domain\OrderSubsetSelectors\ProductCategoryIs;
use Rainbow\Discounts\Domain\OrderSubsetSelectors\TotalIsGreaterThan;
use Rainbow\Discounts\Domain\OrderSubsetSelectors\TotalQuantityIsAtLeastN;

class HardcodedDiscountRuleRepository implements DiscountRuleRepository
{
	public function getAll(): array
	{
		return [
			// A customer who has already bought for over € 1000, gets a discount of 10% on the whole order.
			new DiscountRule(
				-99,
				new TotalIsGreaterThan(new Money(1000_00, new Currency("EUR")), new OnWholeOrderSubset()),
				new SubsetDiscount("0.1")
			),
			// For every product of category "Switches" (id 2), when you buy five, you get a sixth for free.
			new DiscountRule(
				0,
				new ProductCategoryIs("2"),
				new EveryNUnits(6, new SingleUnitDiscount("1.0"))
			),
			// If you buy two or more products of category "Tools" (id 1), you get a 20% discount on the cheapest product.
			new DiscountRule(
				0,
				new TotalQuantityIsAtLeastN(2, new ProductCategoryIs("1")),
				new OnCheapestItem(new SingleUnitDiscount("0.2"))
			),
		];
	}
}
