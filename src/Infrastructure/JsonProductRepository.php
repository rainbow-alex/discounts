<?php

declare(strict_types=1);

namespace Rainbow\Discounts\Infrastructure;

use Rainbow\Discounts\Domain\Product;
use Rainbow\Discounts\Domain\ProductRepository;

class JsonProductRepository implements ProductRepository
{
	private string $filename;

	public function __construct(string $filename)
	{
		$this->filename = $filename;
	}

	public function find(string $id): ?Product
	{
		$src = \file_get_contents($this->filename);
		assert(\is_string($src));
		$data = \json_decode($src, true);

		foreach ($data as $productData)
		{
			if ($productData["id"] === $id)
			{
				return new Product(
					$productData["id"],
					$productData["category"]
				);
			}
		}

		return null;
	}
}
