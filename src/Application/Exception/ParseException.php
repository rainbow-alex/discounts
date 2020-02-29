<?php

declare(strict_types=1);

namespace Rainbow\Discounts\Application\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ParseException extends HttpException
{
	public function __construct(string $message)
	{
		parent::__construct(422, $message);
	}
}
