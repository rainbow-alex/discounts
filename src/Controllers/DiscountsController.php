<?php

declare(strict_types=1);

namespace Rainbow\Discounts\Controllers;

use Symfony\Component\HttpFoundation\JsonResponse;

class DiscountsController
{
	public function getDiscounts(): JsonResponse
	{
		return new JsonResponse([
			"message" => "Hello, world!",
		]);
	}
}
