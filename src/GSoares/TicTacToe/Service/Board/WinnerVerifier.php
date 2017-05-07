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

        $winnerPositions = $this->getWinnerPositions();

        $playersPositions = $this->groupPlayerPositions($boardState);

        foreach ($winnerPositions as $winnerPosition) {
            foreach ($playersPositions as $unit => $positions) {
                $commonPositions = [];

                foreach ($winnerPosition as $winnerSubPosition) {
                    foreach ($positions as $position) {
                        if ($position == $winnerSubPosition) {
                            $commonPositions[] = $winnerSubPosition;
                        }
                    }
                }

                sort($winnerPosition);
                sort($commonPositions);

                if ($winnerPosition == $commonPositions) {
                    return array_merge($winnerPosition, [$unit]);
                }
            }
        }

        return [];
    }

    /**
     * @param array $boardState
     * @return array
     */
    private function groupPlayerPositions(array $boardState)
    {
        $playersPositions = ['X' => [], 'O' => []];

        foreach ($boardState as $yPosition => $line) {
            foreach ($line as $xPosition => $value) {
                if (!empty($value)) {
                    $playersPositions[$value][] = [$xPosition, $yPosition];
                }
            }
        }

        return $playersPositions;
    }

    /**
     * @return array
     */
    private function getWinnerPositions()
    {
        $winnerPositions = [
            [
                [2, 0],
                [1, 1],
                [0, 2]
            ],
            [
                [0, 0],
                [1, 1],
                [2, 2]
            ]
        ];

        for ($row = 0; $row < 3; $row++) {
            $winnerPositions[] = [
                [$row, 0],
                [$row, 1],
                [$row, 2]
            ];

            $winnerPositions[] = [
                [0, $row],
                [1, $row],
                [2, $row]
            ];
        }

        return $winnerPositions;
    }
}
