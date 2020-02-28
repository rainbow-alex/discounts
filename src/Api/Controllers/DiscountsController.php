<?php

declare(strict_types=1);

namespace Rainbow\Discounts\Api\Controllers;

use Rainbow\Discounts\Api\OrderParser;
use Rainbow\Discounts\Api\OrderSerializer;
use Rainbow\Discounts\DiscountService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class DiscountsController
{
	private DiscountService $discountService;
	private OrderParser $orderParser;
	private OrderSerializer $orderSerializer;

	public function __construct(
		DiscountService $discountService,
		OrderParser $orderParser,
		OrderSerializer $orderSerializer
	)
	{
		$this->discountService = $discountService;
		$this->orderParser = $orderParser;
		$this->orderSerializer = $orderSerializer;
	}

	public function getDiscounts(Request $request): JsonResponse
	{
		$payload = self::getJsonBody($request);
		$order = $this->orderParser->parse($payload);

		$this->discountService->applyDiscounts($order);

		return new JsonResponse([
			"message" => "Hello, world!",
			"order" => $this->orderSerializer->serialize($order),
		]);
	}

	/**
	 * @return mixed
	 */
	private static function getJsonBody(Request $request)
	{
		/** @phpstan-var string $content */
		$content = $request->getContent();

		try
		{
			return \json_decode($content, true, 512, \JSON_THROW_ON_ERROR);
		}
		catch (\JsonException $e)
		{
			throw new HttpException(400, "Invalid JSON: " . $e->getMessage());
		}
	}
}
