<?php

declare(strict_types=1);

namespace Rainbow\Discounts\Tests\Domain\DiscountEffects;

use Money\Currency;
use Money\Money;
use PHPUnit\Framework\TestCase;
use Rainbow\Discounts\Domain\DiscountEffects\SubsetDiscount;
use Rainbow\Discounts\Domain\Order;
use Rainbow\Discounts\Domain\OrderItem;
use Rainbow\Discounts\Domain\OrderSubset\ItemsSubset;
use Rainbow\Discounts\Domain\OrderSubset\WholeOrder;
use Rainbow\Discounts\Domain\Product;

class SubsetDiscountTest extends TestCase
{
	public function testItemsDiscount()
	{
		$order = $this->createOrder();

		$subset = new ItemsSubset([$order->getItems()[0], $order->getItems()[2]]);
		$effect = new SubsetDiscount("0.5");

		$effect->apply($order, $subset);

		self::assertCount(1, $order->getOrderDiscounts());
		self::assertEquals(10_00, $order->getTotal()->getAmount());
		foreach ($order->getItems() as $item)
		{
			self::assertCount(0, $item->getItemDiscounts());
		}
	}

	public function testWholeOrderDiscount()
	{
		$order = $this->createOrder();

		$subset = new WholeOrder($order);
		$effect = new SubsetDiscount("0.5");

		$effect->apply($order, $subset);
		self::assertCount(1, $order->getOrderDiscounts());
		self::assertEquals(7_50, $order->getTotal()->getAmount());

		$effect->apply($order, $subset);
		self::assertCount(2, $order->getOrderDiscounts());
		self::assertEquals(3_75, $order->getTotal()->getAmount()); // takes into account the previous discount
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
					new Money(1_00, $eur)
				),
				new OrderItem(
					"P2",
					new Product("P2", "1"),
					5,
					new Money(1_00, $eur)
				),
				new OrderItem(
					"P3",
					new Product("P3", "2"),
					5,
					new Money(1_00, $eur)
				),
			]
		);
	}
}
