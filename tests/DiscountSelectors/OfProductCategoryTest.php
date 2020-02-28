<?php

declare(strict_types=1);

namespace Rainbow\Discounts\Tests\DiscountSelectors;

use Money\Currency;
use Money\Money;
use PHPUnit\Framework\TestCase;
use Rainbow\Discounts\DiscountSelectors\OfProductCategory;
use Rainbow\Discounts\Order;
use Rainbow\Discounts\OrderItem;
use Rainbow\Discounts\Product;

class OfProductCategoryTest extends TestCase
{
	public function testSelector()
	{
		$order = $this->createOrder();

		$selections = \iterator_to_array((new OfProductCategory("1"))->selectItems($order));

		self::assertCount(1, $selections);
		self::assertCount(2, $selections[0]);
		foreach ($selections[0] as $item)
		{
			self::assertEquals(1, $item->getProduct()->getCategoryId());
		}
	}

	public function testNoMatch()
	{
		$order = $this->createOrder();

		$selections = \iterator_to_array((new OfProductCategory("3"))->selectItems($order));

		self::assertCount(0, $selections);
	}

	private function createOrder(): Order
	{
		$eur = new Currency("EUR");
		return new Order(
			"1",
			"1",
			[
				new OrderItem(
					"P1",
					new Product("P1", "1"),
					5,
					new Money(100, $eur)
				),
				new OrderItem(
					"P2",
					new Product("P2", "1"),
					5,
					new Money(100, $eur)
				),
				new OrderItem(
					"P3",
					new Product("P3", "2"),
					5,
					new Money(100, $eur)
				),
			]
		);
	}
}
