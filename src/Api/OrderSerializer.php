<?php

declare(strict_types=1);

namespace Rainbow\Discounts\Api;

use Money\Formatter\DecimalMoneyFormatter;
use Rainbow\Discounts\Discount;
use Rainbow\Discounts\Order;
use Rainbow\Discounts\OrderItem;

class OrderSerializer
{
	private DecimalMoneyFormatter $moneyFormatter;

	public function __construct(DecimalMoneyFormatter $moneyFormatter)
	{
		$this->moneyFormatter = $moneyFormatter;
	}

	/**
	 * @return array<string, mixed>
	 */
	public function serialize(Order $order): array
	{
		return [
			"id" => $order->getId(),
			"customer-id" => $order->getCustomerId(),
			"items" => \array_map(fn (OrderItem $i) => $this->serializeItem($i), $order->getItems()),
			"order-discounts" => \array_map(fn (Discount $d) => $this->serializeDiscount($d), $order->getOrderDiscounts()),
			"total" => $this->moneyFormatter->format($order->getTotal()),
		];
	}

	/**
	 * @return array<string, mixed>
	 */
	private function serializeItem(OrderItem $item): array
	{
		return [
			"product-id" => $item->getProductId(),
			"quantity" => (string) $item->getQuantity(),
			"unit-price" => $this->moneyFormatter->format($item->getUnitPrice()),
			"discounts" => \array_map(fn (Discount $d) => $this->serializeDiscount($d), $item->getItemDiscounts()),
			"total" => $this->moneyFormatter->format($item->getTotal()),
		];
	}

	/**
	 * @return array<string, mixed>
	 */
	private function serializeDiscount(Discount $discount): array
	{
		return [
			"value" => $this->moneyFormatter->format($discount->getValue()),
		];
	}
}
