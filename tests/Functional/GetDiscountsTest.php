<?php

declare(strict_types=1);

namespace Rainbow\Discounts\Tests\Functional;

use Rainbow\Discounts\Api\Framework\AppKernel;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GetDiscountsTest extends WebTestCase
{
	protected static function createKernel(array $options = [])
	{
		return new AppKernel("test", true);
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
	}
}
