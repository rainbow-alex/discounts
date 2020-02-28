<?php

declare(strict_types=1);

namespace Rainbow\Discounts;

class Product
{
	private string $id;
	private string $categoryId;

	public function __construct(string $id, string $categoryId)
	{
		$this->id = $id;
		$this->categoryId = $categoryId;
	}

	public function getId(): string
	{
		return $this->id;
	}

	public function getCategoryId(): string
	{
		return $this->categoryId;
	}
}
