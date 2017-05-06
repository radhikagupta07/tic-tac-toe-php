<?php

namespace GSoares\TicTacToe\Application\Controller;

use Symfony\Component\HttpFoundation\Request;

/**
 * @author Gabriel Felipe Soares <gabrielfs7@gmail.com>
 */
class MoveController extends AbstractController
{

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function moveAction(Request $request)
    {
        return $this->container
            ->get('app.move.maker')
            ->makeMoveByRequest($request);
    }
}
