<?php

declare(strict_types=1);

namespace Rainbow\Discounts\Application\Framework;

use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Bundle\WebProfilerBundle\WebProfilerBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\RouteCollectionBuilder;

class DiscountsApiKernel extends Kernel
{
	use MicroKernelTrait;

	public function registerBundles()
	{
		$bundles = [
			new FrameworkBundle(),
		];

		if ($this->environment === "dev")
		{
			$bundles[] = new WebProfilerBundle();
			$bundles[] = new TwigBundle();
		}

		return $bundles;
	}

	protected function configureContainer(ContainerBuilder $c, LoaderInterface $loader): void
	{
		$loader->load(__DIR__ . "/../../../config/*.yaml", "glob");
		$loader->load(__DIR__ . "/../../../config/" . $this->environment . "/*.yaml", "glob");
	}

	protected function configureRoutes(RouteCollectionBuilder $routes): void
	{
		$routes->import(__DIR__ . "/../../../config/routes/routes.yaml");

		$envRoutes = __DIR__ . "/../../../config/routes/routes_" . $this->environment . ".yaml";
		if (\file_exists($envRoutes))
		{
			$routes->import($envRoutes);
		}
	}
}
