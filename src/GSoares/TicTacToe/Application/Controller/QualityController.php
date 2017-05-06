<?php

namespace GSoares\TicTacToe\Application\Controller;

/**
 * @author Gabriel Felipe Soares <gabrielfs7@gmail.com>
 */
class QualityController extends AbstractController
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function codeStandardsAction()
    {
        return $this->renderResponse(
            'base/iframe.html.twig',
            [
                'title' => 'Code Standard',
                'url' => '/phpcs/index.php'
            ]
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function messControlAction()
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
    public function docAction()
    {
        return $this->renderResponse(
            'base/iframe.html.twig',
            [
                'title' => 'Documentation',
                'url' => '/phpdoc/index.html'
            ]
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function testAction()
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
