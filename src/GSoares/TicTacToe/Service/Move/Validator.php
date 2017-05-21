<?php

namespace GSoares\TicTacToe\Service\Move;

use GSoares\TicTacToe\Exception\InvalidBoardException;
use GSoares\TicTacToe\Exception\InvalidPlayerUnitException;

/**
 * @author Gabriel Felipe Soares <gabrielfs7@gmail.com>
 */
class Validator
{

    /**
     * @var boolean
     */
    private $validateUnitCount = true;

    /**
     * @return Validator
     */
    public function validateUnitCount() : self
    {
        $this->validateUnitCount = true;

        return $this;
    }

    /**
     * @return Validator
     */
    public function doNotValidateUnitCount() : self
    {
        $this->validateUnitCount = false;

        return $this;
    }

    /**
     * @param array $boardState
     * @param string $playerUnit
     */
    public function validate(array $boardState, string $playerUnit) : void
    {
        $this->validatePlayerUnit($playerUnit);

        if (count($boardState) !== 3) {
            throw new InvalidBoardException('You must provide a 3 lines board');
        }

        $totalX = 0;
        $totalO = 0;

        array_walk(
            $boardState,
            function ($line) use (&$totalX, &$totalO) {
                if (count($line) !== 3) {
                    throw new InvalidBoardException('You must provide a 3 positions line');
                }

                foreach ($line as $positionValue) {
                    $this->validatePositionValue($positionValue);

                    if ($positionValue == 'X') {
                        $totalX++;
                    }

                    if ($positionValue == 'O') {
                        $totalO++;
                    }
                }
            }
        );

        if (abs($totalX - $totalO) > 1 && $this->validateUnitCount) {
            throw new InvalidBoardException(
                "Invalid unit count equality $totalX , $totalO " . abs($totalX - $totalO)
            );
        }
    }

    /**
     * @param string $playerUnit
     */
    private function validatePlayerUnit(string $playerUnit) : void
    {
        if (!is_string($playerUnit) || !in_array($playerUnit, ['X', 'O'])) {
            throw new InvalidPlayerUnitException('Invalid playerUnit. Allowed O or X');
        }
    }

    /**
     * @param string $positionValue
     */
    private function validatePositionValue(string $positionValue) : void
    {
        if (!is_string($positionValue) || !in_array($positionValue, ['X', 'O', ''])) {
            throw new InvalidBoardException('There is some invalid board position value');
        }
    }
}
