<?php

declare(strict_types=1);

namespace Rainbow\Discounts\Application;

use Money\Currency;
use Money\Parser\DecimalMoneyParser;
use Rainbow\Discounts\Application\Exception\ParseException;
use Rainbow\Discounts\Domain\Order;
use Rainbow\Discounts\Domain\OrderItem;
use Rainbow\Discounts\Domain\ProductRepository;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class OrderParser
{
	private ValidatorInterface $validator;
	private ProductRepository $productRepository;
	private DecimalMoneyParser $moneyParser;

	public function __construct(
		ValidatorInterface $validator,
		ProductRepository $productRepository,
		DecimalMoneyParser $moneyParser
	)
	{
		$this->validator = $validator;
		$this->productRepository = $productRepository;
		$this->moneyParser = $moneyParser;
	}

	/**
	 * @param array<string, mixed> $payload
	 */
	public function parse(array $payload): Order
	{
		$violations = $this->validator->validate($payload, new Collection([
			"fields" => [
				"id" => new Required([new Type("string")]),
				"customer-id" => new Required([new Type("string")]),
				"items" => new All(new Collection([
					"fields" => [
						"product-id" => new Required([new Type("string")]),
						"quantity" => new Required([new Type("string"), new Type("numeric")]),
						"unit-price" => new Required([new Type("string"), new Type("numeric")]),
						"total" => new Required([new Type("string"), new Type("numeric")]),
					],
				])),
				"total" => new Required([new Type("string"), new Type("numeric")])
			],
		]));

		if (\count($violations))
		{
			/** @var ConstraintViolationInterface $violation */
			$violation = $violations[0];
			throw new ParseException($violation->getPropertyPath() . ": " . $violation->getMessage());
		}

		return new Order(
			$payload["id"],
			$payload["customer-id"],
			\array_map(fn ($item) => $this->parseItem($item), $payload["items"])
		);
	}

	/**
	 * @param array<string, mixed> $payload
	 */
	private function parseItem(array $payload): OrderItem
	{
		return new OrderItem(
			$payload["product-id"],
			$this->productRepository->find($payload["product-id"]),
			(int) $payload["quantity"],
			$this->moneyParser->parse($payload["unit-price"], new Currency("EUR"))
		);
	}
}
