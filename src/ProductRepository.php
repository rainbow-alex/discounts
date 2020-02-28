<?php

declare(strict_types=1);

namespace Rainbow\Discounts;

interface ProductRepository
{
	public function find(string $id): ?Product;
}
