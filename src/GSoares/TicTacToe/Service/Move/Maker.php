<?php

namespace GSoares\TicTacToe\Service\Move;

use GSoares\TicTacToe\Service\Board\WinnerVerifier;

/**
 * @author Gabriel Felipe Soares <gabrielfs7@gmail.com>
 */
class Maker implements MoveInterface
{

    /**
     * @var Validator
     */
    private $validator;

    /**
     * @var WinnerVerifier
     */
    private $winnerVerifier;

    public function __construct(Validator $validator, WinnerVerifier $winnerVerifier)
    {
        $this->validator = $validator;
        $this->winnerVerifier = $winnerVerifier;
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
     * @param $botUnit
     * @return array
     */
    private function getNextPossibleMove(array $boardState, $botUnit)
    {
        $possibleMoves = $this->getPossibleMoves($boardState);

        if (!count($possibleMoves)) {
            return null;
        }

        if ($winnerNextMove = $this->predictWinnerNextMove($boardState, $possibleMoves, $botUnit)) {
            return $winnerNextMove;
        }

        return array_merge($possibleMoves[array_rand($possibleMoves)], [$botUnit]);
    }

    /**
     * @param array $boardState
     * @param array $possibleMoves
     * @param $botUnit
     * @return array
     */
    private function predictWinnerNextMove(array $boardState, array $possibleMoves, $botUnit)
    {
        $playerUnit = $botUnit == 'X' ? 'O' : 'X';
        $playerWinsMove = null;

        foreach ($possibleMoves as $possibleMove) {
            $possibleMoveX = $possibleMove[0];
            $possibleMoveY = $possibleMove[1];

            if ($winnerMove = $this->getWinnerWinnerMove($boardState, $possibleMoveY, $possibleMoveX, $botUnit)) {
                return $winnerMove;
            }

            if ($winnerMove = $this->getWinnerWinnerMove($boardState, $possibleMoveY, $possibleMoveX, $playerUnit)) {
                $playerWinsMove = $winnerMove;
            }
        }

        return $playerWinsMove;
    }

    /**
     * @param array $boardState
     * @param $possibleMoveY
     * @param $possibleMoveX
     * @param $unit
     * @return array
     */
    private function getWinnerWinnerMove(array $boardState, $possibleMoveY, $possibleMoveX, $unit)
    {
        $simulatedBoardState = $boardState;
        $simulatedBoardState[$possibleMoveY][$possibleMoveX] = $unit;

        $winnerPosition = $this->winnerVerifier
            ->doNotValidateUnitCount()
            ->verifyPosition($simulatedBoardState, $unit);

        $this->winnerVerifier
            ->validateUnitCount();

        if (count($winnerPosition)) {
            return [$possibleMoveX, $possibleMoveY];
        }
    }

    /**
     * @param array $boardState
     * @return array
     */
    private function getPossibleMoves(array $boardState)
    {
        $possibleMoves = [];

        foreach ($boardState as $line => $row) {
            foreach ($row as $key => $column) {
                if (empty($column)) {
                    $possibleMoves[] = [$key, $line];
                }
            }
        }

        return $possibleMoves;
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
