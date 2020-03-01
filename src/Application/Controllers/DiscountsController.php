<?php

declare(strict_types=1);

namespace Rainbow\Discounts\Application\Controllers;

use Rainbow\Discounts\Application\OrderParser;
use Rainbow\Discounts\Application\OrderSerializer;
use Rainbow\Discounts\Domain\DiscountService;
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

	public function applyDiscounts(Request $request): JsonResponse
	{
		$payload = self::getJsonBody($request);
		$order = $this->orderParser->parse($payload);

		$this->discountService->applyDiscounts($order);

		return new JsonResponse(
			$this->orderSerializer->serialize($order)
		);
	}

	/**
	 * @return mixed
	 */
	private static function getJsonBody(Request $request)
	{
		$content = $request->getContent();
		assert(\is_string($content));

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
