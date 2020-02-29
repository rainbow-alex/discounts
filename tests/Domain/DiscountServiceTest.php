<?php

declare(strict_types=1);

namespace Rainbow\Discounts\Tests\Domain;

use Money\Currency;
use Money\Money;
use Rainbow\Discounts\Application\Framework\DiscountsApiKernel;
use Rainbow\Discounts\Domain\DiscountService;
use Rainbow\Discounts\Domain\Order;
use Rainbow\Discounts\Domain\OrderItem;
use Rainbow\Discounts\Domain\Product;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DiscountServiceTest extends KernelTestCase
{
	protected static function createKernel(array $options = [])
	{
		return new DiscountsApiKernel("test", true);
	}

	protected function setUp(): void
	{
		parent::setUp();
		self::bootKernel();
	}

	//
	// A customer who has already bought for over € 1000, gets a discount of 10% on the whole order.
	//

	public function testTotalDiscount()
	{
		$order = new Order("1", "1", [
			new OrderItem("P1", null, 1, new Money(1000_01, new Currency("EUR"))),
		]);

		$discountService = self::$container->get(DiscountService::class);
		$discountService->applyDiscounts($order);

		self::assertCount(1, $order->getOrderDiscounts());
		self::assertEquals(900_01, $order->getTotal()->getAmount());
	}

	public function testTotalDiscountDoesntApplyAfterOtherDiscounts()
	{
		$order = new Order("1", "1", [
			new OrderItem("S1", new Product("S1", "2"), 101, new Money(10_00, new Currency("EUR"))),
		]);

		// order sure looks like the >1000 rule applies...
		self::assertEquals(1010_00, $order->getTotal()->getAmount());

		$discountService = self::$container->get(DiscountService::class);
		$discountService->applyDiscounts($order);

		// but actually a different rule with higher priority has taken effect first
		self::assertNotEmpty($order->getItems()[0]->getItemDiscounts());
		self::assertEquals(850_00, $order->getTotal()->getAmount());
		// now the >1000 rule doesn't apply any more!
		self::assertCount(0, $order->getOrderDiscounts());
	}

	//
	// For every product of category "Switches" (id 2), when you buy five, you get a sixth for free.
	//
	public function testSwitchesDiscount()
	{
		$order = new Order("1", "1", [
			new OrderItem("P1", new Product("P1", "2"), 6, new Money(1_00, new Currency("EUR"))),
		]);

		$discountService = self::$container->get(DiscountService::class);
		$discountService->applyDiscounts($order);

		$item = $order->getItems()[0];
		self::assertCount(1, $item->getItemDiscounts());
		self::assertEquals(5_00, $item->getTotal()->getAmount());
	}

	public function testSwitchesDiscountRepeats()
	{
		$order = new Order("1", "1", [
			new OrderItem("P1", new Product("P1", "2"), 12, new Money(1_00, new Currency("EUR"))),
		]);

		$discountService = self::$container->get(DiscountService::class);
		$discountService->applyDiscounts($order);

		$item = $order->getItems()[0];
		self::assertCount(2, $item->getItemDiscounts());
		self::assertEquals(10_00, $item->getTotal()->getAmount());
	}

	public function testSwitchesDiscountWithoutEnoughUnits()
	{
		$order = new Order("1", "1", [
			new OrderItem("P1", new Product("P1", "2"), 5, new Money(1_00, new Currency("EUR"))),
		]);

		$discountService = self::$container->get(DiscountService::class);
		$discountService->applyDiscounts($order);

		$item = $order->getItems()[0];
		self::assertCount(0, $item->getItemDiscounts());
		self::assertEquals(5_00, $item->getTotal()->getAmount());
	}

	//
	// If you buy two or more products of category "Tools" (id 1), you get a 20% discount on the cheapest product.
	//

	public function testToolsDiscount()
	{
		$order = new Order("1", "1", [
			new OrderItem("P1", new Product("P1", "1"), 2, new Money(10_00, new Currency("EUR"))),
		]);

		$discountService = self::$container->get(DiscountService::class);
		$discountService->applyDiscounts($order);

		$item = $order->getItems()[0];
		self::assertCount(1, $item->getItemDiscounts());
		self::assertEquals(18_00, $item->getTotal()->getAmount());
	}

	public function testToolsDiscountCheapest()
	{
		$order = new Order("1", "1", [
			new OrderItem("P1", new Product("P1", "1"), 1, new Money(10_00, new Currency("EUR"))),
			new OrderItem("P2", new Product("P2", "1"), 1, new Money(5_00, new Currency("EUR"))),
		]);

		$discountService = self::$container->get(DiscountService::class);
		$discountService->applyDiscounts($order);

		self::assertCount(0, $order->getItems()[0]->getItemDiscounts());
		self::assertCount(1, $order->getItems()[1]->getItemDiscounts());
		self::assertEquals(14_00, $order->getTotal()->getAmount());
	}

	public function testToolsDiscountWithoutEnoughUnits()
	{
		$order = new Order("1", "1", [
			new OrderItem("P1", new Product("P1", "1"), 1, new Money(10_00, new Currency("EUR"))),
		]);

		$discountService = self::$container->get(DiscountService::class);
		$discountService->applyDiscounts($order);

		self::assertCount(0, $order->getItems()[0]->getItemDiscounts());
		self::assertEquals(10_00, $order->getTotal()->getAmount());
	}

	public function testToolsDiscountWithExtraUnits()
	{
		$order = new Order("1", "1", [
			new OrderItem("P1", new Product("P1", "1"), 50, new Money(10_00, new Currency("EUR"))),
		]);

		$discountService = self::$container->get(DiscountService::class);
		$discountService->applyDiscounts($order);

		self::assertCount(1, $order->getItems()[0]->getItemDiscounts());
		self::assertEquals(498_00, $order->getTotal()->getAmount());
	}
}
