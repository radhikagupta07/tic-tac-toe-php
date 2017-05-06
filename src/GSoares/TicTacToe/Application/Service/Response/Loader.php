<?php

namespace GSoares\TicTacToe\Application\Service\Response;

use GSoares\TicTacToe\Application\Factory\Route\UrlMatcherFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * @author Gabriel Felipe Soares <gabrielfs7@gmail.com>
 */
class Loader implements LoaderInterface
{

    use ContainerAwareTrait;

    /**
     * @var UrlMatcherFactoryInterface
     */
    private $urlMatcherFactory;

    /**
     * @var ControllerResolverInterface
     */
    private $controllerResolver;

    /**
     * @var ArgumentResolverInterface
     */
    private $argumentResolver;

    public function __construct(
        UrlMatcherFactoryInterface $urlMatcherFactory,
        ControllerResolverInterface $controllerResolver,
        ArgumentResolverInterface $argumentResolver
    ) {
        $this->urlMatcherFactory = $urlMatcherFactory;
        $this->controllerResolver = $controllerResolver;
        $this->argumentResolver = $argumentResolver;
    }


    /**
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function loadResponse(Request $request)
    {
        $matcher = $this->urlMatcherFactory
            ->create($request);

        try {
            $request->attributes
                ->add($matcher->match($request->getPathInfo()));

            $controller = $this->controllerResolver
                ->getController($request);

            $arguments = $this->argumentResolver
                ->getArguments($request, $controller);

            return call_user_func_array($controller, $arguments);
        } catch (ResourceNotFoundException $e) {
            return new Response('Not Found', 404);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
