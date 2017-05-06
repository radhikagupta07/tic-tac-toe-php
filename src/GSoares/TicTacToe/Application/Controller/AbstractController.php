<?php

namespace GSoares\TicTacToe\Application\Controller;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Gabriel Felipe Soares <gabrielfs7@gmail.com>
 */
class AbstractController implements ControllerInterface
{
    use ContainerAwareTrait;

    /**
     * @param $templateFile
     * @param array $context
     * @param int $statusCode
     * @return Response
     */
    public function renderResponse($templateFile, array $context = [], $statusCode = 200)
    {
        $content = $this->container
            ->get('twig.environment')
            ->render($templateFile, $context);

        return new Response($content, $statusCode);
    }
}
