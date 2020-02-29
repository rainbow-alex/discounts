<?php

declare(strict_types=1);

namespace Rainbow\Discounts\Tests\Functional;

use Rainbow\Discounts\Application\Framework\DiscountsApiKernel;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class GetDiscountsTest extends WebTestCase
{
	protected static function createKernel(array $options = [])
	{
		return new DiscountsApiKernel("test", true);
	}

	public function testHappyPath(): void
	{
		$client = static::createClient();
		$client->request("POST", "/get-discounts", [], [], [], <<<JSON
			{
				"id": "1",
				"customer-id": "1",
				"items": [
					{
						"product-id": "B102",
						"quantity": "1000",
						"unit-price": "4.99",
						"total": "4990.00"
					}
				],
				"total": "4990.00"
			}
		JSON
		);

		$response = $client->getResponse();
		$this->assertEquals(200, $response->getStatusCode());
		$json = self::assertJsonResponse($response);
		$this->assertCount(1, $json["order-discounts"]);
		$this->assertEquals("3745.49", $json["total"]);
	}

	public function testInvalidJson(): void
	{
		$client = static::createClient();
		$client->request("POST", "/get-discounts", [], [], [], "Invalid json");

		$response = $client->getResponse();
		$this->assertEquals(400, $response->getStatusCode());
		$json = self::assertJsonResponse($response);
		self::assertEquals("Invalid JSON: Syntax error", $json["error"]);
	}

	public function testMissingFields(): void
	{
		$client = static::createClient();
		$client->request("POST", "/get-discounts", [], [], [], <<<JSON
			{
				"customer-id": "1",
				"items": [],
				"total": "0.00"
			}
		JSON
		);

		$response = $client->getResponse();
		$this->assertEquals(422, $response->getStatusCode());
		$json = self::assertJsonResponse($response);
		self::assertEquals("[id]: This field is missing.", $json["error"]);
	}

	private static function assertJsonResponse(Response $response)
	{
		self::assertEquals("application/json", $response->headers->get("Content-Type"));
		return \json_decode($response->getContent(), true, 512, \JSON_THROW_ON_ERROR);
	}
}
