<?php

namespace GSoares\TicTacToe\Application\Service\Move;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
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
    public function makeMoveByRequest(Request $request)
    {
        try {
            $data = $this->validator->validateMoveByRequest($request);

            $winnerData = $this->winnerVerifier
                ->verifyPosition($data->boardState, $data->playerUnit);

            $nextMovie = $this->maker
                ->makeMove((array) $data->boardState, $data->playerUnit);

            $responseDto = new \stdClass();
            $responseDto->winner = isset($winnerData[3]) ? $winnerData[3] : null;
            $responseDto->playerWins = $responseDto->winner == $data->playerUnit;
            $responseDto->botWins = $responseDto->winner && $responseDto->winner != $data->playerUnit;
            $responseDto->tiedGame = !$nextMovie && !$responseDto->winner;
            $responseDto->winnerPositions = array_slice($winnerData, 0, 3);
            $responseDto->nextMove = $nextMovie;

            return new JsonResponse($responseDto);
        } catch (\Exception $e) {
            return new JsonResponse(
                [
                    'error' => $e->getMessage()
                ],
                400
            );
        }
    }
}
