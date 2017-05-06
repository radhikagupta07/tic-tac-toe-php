<?php

namespace GSoares\TicTacToe\Application\Factory\Route;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Config\Loader\LoaderInterface as ConfigLoaderInterface;

/**
 * @author Gabriel Felipe Soares <gabrielfs7@gmail.com>
 */
class UrlMatcherFactory implements UrlMatcherFactoryInterface
{

    use ContainerAwareTrait;

    /**
     * @var ConfigLoaderInterface
     */
    private $configLoader;

    /**
     * @var RequestContext
     */
    private $requestContext;

    /**
     * @param ConfigLoaderInterface $configLoader
     * @param RequestContext $requestContext
     */
    public function __construct(
        ConfigLoaderInterface $configLoader,
        RequestContext $requestContext
    ) {
        $this->configLoader = $configLoader;
        $this->requestContext = $requestContext;
    }

    /**
     * @param Request $request
     * @return UrlMatcher
     */
    public function create(Request $request)
    {
        $routeCollection = $this->configLoader
            ->load('routes.xml');

        $this->configureRoutes($routeCollection);

        $context = $this->requestContext
            ->fromRequest($request);

        return new UrlMatcher($routeCollection, $context);
    }

    /**
     * @param RouteCollection $routeCollection
     */
    private function configureRoutes(RouteCollection $routeCollection)
    {
        foreach ($routeCollection as $route) {
            list($controllerId, $action) = explode(':', $route->getDefault('_controller'));

            $route->setDefault('_controller', [$this->container->get($controllerId), $action]);
        }
    }
}
