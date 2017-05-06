<?php

namespace GSoares\TicTacToe\Service\Board;

use GSoares\TicTacToe\Service\Move\Validator;

/**
 * @author Gabriel Felipe Soares <gabrielfs7@gmail.com>
 */
class WinnerVerifier
{

    /**
     * @var Validator
     */
    private $validator;

    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Returns the winner position if it exists.
     * Example: [[2, 0], [1,1], [0,2], 'O'] - Diagonal win by player 'O'
     *
     * @param array $boardState
     * @param $playerUnit
     * @return array
     */
    public function verifyPosition(array $boardState, $playerUnit)
    {
        $this->validator->validate($boardState, $playerUnit);

        return [];

        //FIXME return [[2, 0], [1,1], [0,2], 'O']; //FIXME TODO mocked and incomplete
    }
}
