<?php

declare(strict_types=1);

namespace Rainbow\Discounts\Domain;

interface ProductRepository
{
	public function find(string $id): ?Product;
}
