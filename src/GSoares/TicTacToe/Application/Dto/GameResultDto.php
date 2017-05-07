<?php

namespace GSoares\TicTacToe\Application\Dto;

/**
 * @author Gabriel Felipe Soares <gabrielfs7@gmail.com>
 */
class GameResultDto
{

    /**
     * @var string
     */
    public $winner;

    /**
     * @var boolean
     */
    public $playerWins;

    /**
     * @var boolean
     */
    public $botWins;

    /**
     * @var boolean
     */
    public $tiedGame;

    /**
     * @var array
     */
    public $winnerPositions;

    /**
     * @var array
     */
    public $nextMove;
}
