<?php

namespace GSoares\TicTacToe\Application\Controller;

use Symfony\Component\HttpFoundation\Response;

/**
 * @author Gabriel Felipe Soares <gabrielfs7@gmail.com>
 */
class QualityController extends AbstractController
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function codeStandardsAction() : Response
    {
        return $this->renderResponse(
            'base/iframe.html.twig',
            [
                'title' => 'Code Standard',
                'url' => '/phpcs/summary.html'
            ]
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function messControlAction() : Response
    {
        return $this->renderResponse(
            'base/iframe.html.twig',
            [
                'title' => 'Mess Control',
                'url' => '/phpmd/index.html'
            ]
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function docAction() : Response
    {
        return $this->renderResponse(
            'base/fullpage-iframe.html.twig',
            [
                'title' => 'Documentation',
                'url' => '/phpdoc/index.html'
            ]
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function testAction() : Response
    {
        return $this->renderResponse(
            'base/iframe.html.twig',
            [
                'title' => 'Documentation',
                'url' => '/phpunit/testdox.html'
            ]
        );
    }
}
