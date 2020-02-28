<?php

declare(strict_types=1);

namespace Rainbow\Discounts\Api\Framework;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionListener implements EventSubscriberInterface
{
	/**
	 * @return array<string, string>
	 */
	public static function getSubscribedEvents(): array
	{
		return [
			KernelEvents::EXCEPTION => "onException",
		];
	}

	public function onException(ExceptionEvent $event): void
	{
		$exception = $event->getThrowable();

		if ($exception instanceof HttpExceptionInterface)
		{
			$event->setResponse(new JsonResponse([
				"status" => $exception->getStatusCode(),
				"error" => $exception->getMessage(),
			], $exception->getStatusCode()));
		}
	}
}
