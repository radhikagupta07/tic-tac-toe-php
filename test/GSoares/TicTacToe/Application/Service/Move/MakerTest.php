<?php
namespace GSoares\TicTacToe\Application\Service\Move;

use GSoares\TicTacToe\Service\Board\WinnerVerifier;
use GSoares\TicTacToe\Service\Move\Maker as MakerService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Gabriel Felipe Soares <gabrielfs7@gmail.com>
 */
class MakerTest extends TestCase
{

    /**
     * @var Maker
     */
    private $maker;

    /**
     * @var MakerService|\PHPUnit_Framework_MockObject_MockObject
     */
    private $makerService;

    /**
     * @var Validator|\PHPUnit_Framework_MockObject_MockObject
     */
    private $validator;

    /**
     * @var WinnerVerifier|\PHPUnit_Framework_MockObject_MockObject
     */
    private $winnerVerifier;

    public function setUp()
    {
        $this->validator = $this->getMockBuilder('GSoares\TicTacToe\Application\Service\Move\Validator')
            ->getMock();

        $this->winnerVerifier = $this->getMockBuilder('GSoares\TicTacToe\Service\Board\WinnerVerifier')
            ->disableOriginalConstructor()
            ->getMock();

        $this->makerService = $this->getMockBuilder('GSoares\TicTacToe\Service\Move\Maker')
            ->disableOriginalConstructor()
            ->getMock();

        $this->maker = new Maker($this->makerService, $this->winnerVerifier, $this->validator);

        parent::setUp();
    }

    public function tearDown()
    {
        $this->maker = null;
        $this->validator = null;
        $this->makerService = null;
        $this->winnerVerifier = null;

        parent::tearDown();
    }

    /**
     * @test
     */
    public function testTieMove()
    {
        $this->validator
            ->expects($this->once())
            ->method('validateMoveByRequest')
            ->willReturn($this->createRequestObject());

        $this->winnerVerifier
            ->expects($this->once())
            ->method('verifyPosition')
            ->with([], 'X')
            ->willReturn([]);

        $this->makerService
            ->expects($this->once())
            ->method('makeMove')
            ->with([], 'X')
            ->willReturn([]);

        $request = Request::create('', 'POST');

        $response = $this->maker->makeMoveByRequest($request);

        $expectedResponse = new JsonResponse(
            $this->fabricateResponse(null, false, false, true, [], [])
        );

        $this->assertEquals($expectedResponse, $response);
    }

    /**
     * @test
     */
    public function testBotMoveWins()
    {
        $winnerPositions = [[2, 0], [1,1], [0,2]];

        $this->validator
            ->expects($this->once())
            ->method('validateMoveByRequest')
            ->willReturn($this->createRequestObject());

        $this->winnerVerifier
            ->expects($this->once())
            ->method('verifyPosition')
            ->with([], 'X')
            ->willReturn(array_merge($winnerPositions, ['O']));

        $this->makerService
            ->expects($this->once())
            ->method('makeMove')
            ->with([], 'X')
            ->willReturn([]);

        $request = Request::create('', 'POST');

        $response = $this->maker->makeMoveByRequest($request);

        $expectedResponse = new JsonResponse(
            $this->fabricateResponse('O', false, true, false, $winnerPositions, [])
        );

        $this->assertEquals($expectedResponse, $response);
    }

    /**
     * @test
     */
    public function testPlayerMoveWins()
    {
        $winnerPositions = [[2, 0], [1,1], [0,2]];

        $this->validator
            ->expects($this->once())
            ->method('validateMoveByRequest')
            ->willReturn($this->createRequestObject());

        $this->winnerVerifier
            ->expects($this->once())
            ->method('verifyPosition')
            ->with([], 'X')
            ->willReturn(array_merge($winnerPositions, ['X']));

        $this->makerService
            ->expects($this->once())
            ->method('makeMove')
            ->with([], 'X')
            ->willReturn([]);

        $request = Request::create('', 'POST');

        $response = $this->maker->makeMoveByRequest($request);

        $expectedResponse = new JsonResponse(
            $this->fabricateResponse('X', true, false, false, $winnerPositions, [])
        );

        $this->assertEquals($expectedResponse, $response);
    }

    /**
     * @return \stdClass
     */
    private function createRequestObject()
    {
        $dto = new \stdClass();
        $dto->boardState = [];
        $dto->playerUnit = 'X';

        return $dto;
    }

    /**
     * @param $winner
     * @param $playerWins
     * @param $botWins
     * @param $tieGame
     * @param $winnerPositions
     * @param $nextMove
     * @return \stdClass
     */
    private function fabricateResponse($winner, $playerWins, $botWins, $tieGame, $winnerPositions, $nextMove)
    {
        $responseDto = new \stdClass();
        $responseDto->winner = $winner;
        $responseDto->playerWins = $playerWins;
        $responseDto->botWins = $botWins;
        $responseDto->tiedGame = $tieGame;
        $responseDto->winnerPositions = $winnerPositions;
        $responseDto->nextMove = $nextMove;

        return $responseDto;
    }
}
