<?php

namespace GSoares\TicTacToe\Application\Factory\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\Routing\Matcher\UrlMatcher;

/**
 * @author Gabriel Felipe Soares <gabrielfs7@gmail.com>
 */
interface UrlMatcherFactoryInterface
{

    /**
     * @param Request $request
     * @return UrlMatcher
     */
    public function create(Request $request);
}
