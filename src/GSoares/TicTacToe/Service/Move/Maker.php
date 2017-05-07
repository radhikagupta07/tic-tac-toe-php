<?php

namespace GSoares\TicTacToe\Service\Move;

/**
 * @author Gabriel Felipe Soares <gabrielfs7@gmail.com>
 */
class Maker implements MoveInterface
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
     * Makes a move using the $boardState
     * $boardState contains 2 dimensional array of the game field
     * X represents one team, O - the other team, empty string means field is not yet taken.
     * example:
     * [
     *     ['X', 'O', ''],
     *     ['X', 'O', 'O'],
     *     ['',  '',  ''],
     * ]
     * Returns an array, containing x and y coordinates for next move, and the unit that now occupies it.
     * Example: [2, 0, 'O'] - upper right corner - O player
     *
     * @param array $boardState Current board state
     * @param string $playerUnit Player unit representation
     * @return array
     */
    public function makeMove($boardState, $playerUnit = 'X')
    {
        $this->validator
            ->validate($boardState, $playerUnit);

        return $this->getNextPossibleMove($boardState, $this->getBotByPlayer($playerUnit));
    }

    /**
     * @param array $boardState
     * @param $bot
     * @return array
     */
    private function getNextPossibleMove(array $boardState, $bot)
    {
        $possibleMoves = [];

        foreach ($boardState as $line => $row) {
            foreach ($row as $key => $column) {
                if (empty($column)) {
                    $possibleMoves[] = [$key, $line, $bot];
                }
            }
        }

        if (count($possibleMoves)) {
            return $possibleMoves[array_rand($possibleMoves)];
        }

        return null;
    }

    /**
     * @param $playerUnit
     * @return string
     */
    private function getBotByPlayer($playerUnit)
    {
        return $playerUnit == 'X' ? 'O' : 'X';
    }
}
