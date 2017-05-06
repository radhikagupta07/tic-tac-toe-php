<?php

namespace GSoares\TicTacToe\Application\Service\Response;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Gabriel Felipe Soares <gabrielfs7@gmail.com>
 */
interface LoaderInterface extends ContainerAwareInterface
{

    /**
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function loadResponse(Request $request);
}
