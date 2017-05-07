<?php

namespace GSoares\TicTacToe\Application\Service\Move;

use GSoares\TicTacToe\Exception\InvalidApiRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;

/**
 * @author Gabriel Felipe Soares <gabrielfs7@gmail.com>
 */
class Validator
{

    /**
     * @param Request $request
     * @return \stdClass
     */
    public function validateMoveByRequest(Request $request)
    {
        $data = json_decode((string) $request->getContent(false));

        if (json_last_error()) {
            throw new InvalidApiRequestException(json_last_error_msg());
        }

        if (!isset($data->boardState) || !isset($data->playerUnit)) {
            throw new InvalidApiRequestException("boardState and playerUnit must me provided");
        }

        return $data;
    }
}
