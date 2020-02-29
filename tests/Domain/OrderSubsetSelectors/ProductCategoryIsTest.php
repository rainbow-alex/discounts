<?php

declare(strict_types=1);

namespace Rainbow\Discounts\Tests\Domain\OrderSubsetSelectors;

use Money\Currency;
use Money\Money;
use PHPUnit\Framework\TestCase;
use Rainbow\Discounts\Domain\OrderSubsetSelectors\ProductCategoryIs;
use Rainbow\Discounts\Domain\Order;
use Rainbow\Discounts\Domain\OrderItem;
use Rainbow\Discounts\Domain\Product;

class ProductCategoryIsTest extends TestCase
{
	public function testSelector()
	{
		$order = $this->createOrder();

		$subset = (new ProductCategoryIs("1"))->select($order);

		self::assertNotNull($subset);
		self::assertCount(2, $subset->getItems());
		foreach ($subset->getItems() as $item)
		{
			self::assertEquals(1, $item->getProduct()->getCategoryId());
		}
	}

	public function testNoMatch()
	{
		$order = $this->createOrder();

		$subset = (new ProductCategoryIs("3"))->select($order);

		self::assertNull($subset);
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
