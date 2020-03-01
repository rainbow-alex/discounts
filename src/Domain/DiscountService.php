<?php

declare(strict_types=1);

namespace Rainbow\Discounts\Domain;

class DiscountService
{
	private DiscountRuleRepository $ruleRepository;

	public function __construct(DiscountRuleRepository $ruleRepository)
	{
		$this->ruleRepository = $ruleRepository;
	}

	public function applyDiscounts(Order $order): void
	{
		$discountRules = $this->ruleRepository->getAll();

		// sort by priority
		usort($discountRules, fn (DiscountRule $a, DiscountRule $b) => -($a->getPriority() <=> $b->getPriority()));

		foreach ($discountRules as $rule)
		{
			$rule->apply($order);
		}
	}
}
