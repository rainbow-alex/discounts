<?php

declare(strict_types=1);

namespace Rainbow\Discounts;

use Money\Currency;
use Money\Money;
use Rainbow\Discounts\DiscountEffects\CheapestItem;
use Rainbow\Discounts\DiscountEffects\EveryNProducts;
use Rainbow\Discounts\DiscountEffects\OneProductDiscount;
use Rainbow\Discounts\DiscountEffects\SelectionTotalDiscount;
use Rainbow\Discounts\DiscountSelectors\OfProductCategory;
use Rainbow\Discounts\DiscountSelectors\PerItem;
use Rainbow\Discounts\DiscountSelectors\TotalIsAtLeast;
use Rainbow\Discounts\DiscountSelectors\TotalQuantityIsAtLeastN;
use Rainbow\Discounts\DiscountSelectors\WholeOrder;

class DiscountService
{
	public function applyDiscounts(Order $order): void
	{
		$discountRules = [];
		$discountRules[] = new DiscountRule(
			1,
			new PerItem(new OfProductCategory("1")),
			new EveryNProducts(6, new OneProductDiscount("1.0"))
		);
		$discountRules[] = new DiscountRule(
			1,
			new TotalQuantityIsAtLeastN(2, new OfProductCategory("2")),
			new CheapestItem(new OneProductDiscount("0.2"))
		);
		$discountRules[] = new DiscountRule(
			-99,
			new TotalIsAtLeast(new Money(100000, new Currency("EUR")), new WholeOrder()),
			new SelectionTotalDiscount("0.1")
		);

		usort($discountRules, fn (DiscountRule $a, DiscountRule $b) => -($a <=> $b));

		foreach ($discountRules as $rule)
		{
			$rule->apply($order);
		}
	}
}
