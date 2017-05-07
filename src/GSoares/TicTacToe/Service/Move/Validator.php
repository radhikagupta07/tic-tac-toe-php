<?php

namespace GSoares\TicTacToe\Service\Move;

/**
 * @author Gabriel Felipe Soares <gabrielfs7@gmail.com>
 */
class Validator
{

    /**
     * @param $boardState
     * @param $playerUnit
     */
    public function validate($boardState, $playerUnit)
    {
        if (!in_array($playerUnit, ['X', 'O'])) {
            throw new \InvalidArgumentException('Invalid playerUnit. Allowed O or X');
        }

        if (count($boardState) !== 3) {
            throw new \InvalidArgumentException('You must provide a 3 lines board');
        }

        array_walk(
            $boardState,
            function ($line) {
                if (count($line) !== 3) {
                    throw new \InvalidArgumentException('You must provide a 3 positions line');
                }

                foreach ($line as $column) {
                    if (!in_array($column, ['X', 'O', ''])) {
                        throw new \InvalidArgumentException('Invalid value ' . strval($column));
                    }
                }
            }
        );
    }
}
