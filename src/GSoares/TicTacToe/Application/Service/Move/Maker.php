<?php

namespace GSoares\TicTacToe\Application\Service\Move;

use GSoares\TicTacToe\Application\Dto\GameResultDto;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use \GSoares\TicTacToe\Service\Move\Maker as MakerService;
use \GSoares\TicTacToe\Service\Board\WinnerVerifier;

/**
 * @author Gabriel Felipe Soares <gabrielfs7@gmail.com>
 */
class Maker
{

    /**
     * @var MakerService
     */
    private $maker;

    /**
     * @var Validator
     */
    private $validator;

    /**
     * @var WinnerVerifier
     */
    private $winnerVerifier;

    /**
     * @param MakerService $maker
     * @param WinnerVerifier $winnerVerifier
     * @param Validator $validator
     */
    public function __construct(MakerService $maker, WinnerVerifier $winnerVerifier, Validator $validator)
    {
        $this->maker = $maker;
        $this->winnerVerifier = $winnerVerifier;
        $this->validator = $validator;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function makeMoveByRequest(Request $request) : JsonResponse
    {
        try {
            $requestData = $this->validator
                ->validateMoveByRequest($request);

            $boardState = (array) $requestData->boardState;
            $playerUnit = $requestData->playerUnit;

            $winnerPosition = $this->winnerVerifier
                ->verifyPosition($boardState, $playerUnit);

            $nextMove = $this->maker
                ->makeMove($boardState, $playerUnit);

            return new JsonResponse(
                $this->fabricateGameResult($winnerPosition, $nextMove, $playerUnit)
            );
        } catch (\Exception $e) {
            return new JsonResponse(
                [
                    'error' => $e->getMessage()
                ],
                400
            );
        }
    }

    /**
     * @param array $winnerPosition
     * @param array $nextMove
     * @param $playerUnit
     * @return GameResultDto
     */
    private function fabricateGameResult(array $winnerPosition, array $nextMove, string $playerUnit) : GameResultDto
    {
        $responseDto = new GameResultDto();
        $responseDto->winner = isset($winnerPosition[3]) ? $winnerPosition[3] : '';
        $responseDto->playerWins = $responseDto->winner == $playerUnit;
        $responseDto->botWins = $responseDto->winner && $responseDto->winner != $playerUnit;
        $responseDto->tiedGame = !count($nextMove) && !$responseDto->winner;
        $responseDto->winnerPositions = array_slice($winnerPosition, 0, 3);
        $responseDto->nextMove = $nextMove;

        return $responseDto;
    }
}
