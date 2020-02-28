<?php

declare(strict_types=1);

namespace Rainbow\Discounts\Api\Framework;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class FormatJsonResponseListener implements EventSubscriberInterface
{
	/**
	 * @return array<string, string>
	 */
	public static function getSubscribedEvents(): array
	{
		return [
			KernelEvents::RESPONSE => "onResponse",
		];
	}

	public function onResponse(ResponseEvent $event): void
	{
		$response = $event->getResponse();
		if ($response instanceof JsonResponse)
		{
			$response->setEncodingOptions(\JSON_PRETTY_PRINT);
		}
	}
}
