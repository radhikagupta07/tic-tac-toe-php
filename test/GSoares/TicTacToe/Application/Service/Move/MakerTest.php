<?php
namespace GSoares\TicTacToe\Application\Service\Move;

use GSoares\TicTacToe\Application\Dto\GameResultDto;
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

    public function setUp() : void
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

    public function tearDown() : void
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
    public function testTieMove() : void
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

        $expectedResponse = $this->fabricateResponse(
            '',
            false,
            false,
            true,
            [],
            []
        );

        $this->assertEquals($expectedResponse, $this->makeMoveByRequest());
    }

    /**
     * @test
     */
    public function testBotMoveWins() : void
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

        $expectedResponse = $this->fabricateResponse(
            'O',
            false,
            true,
            false,
            $winnerPositions,
            []
        );

        $this->assertEquals($expectedResponse, $this->makeMoveByRequest());
    }

    /**
     * @test
     */
    public function testPlayerMoveWins() : void
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

        $expectedResponse = $this->fabricateResponse(
            'X',
            true,
            false,
            false,
            $winnerPositions,
            []
        );

        $this->assertEquals($expectedResponse, $this->makeMoveByRequest());
    }

    /**
     * @test
     */
    public function testNoWinnerMove() : void
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
            ->willReturn($nextMove = [0, 0, 'O']);

        $expectedResponse = $this->fabricateResponse(
            '',
            false,
            false,
            false,
            [],
            $nextMove
        );

        $this->assertEquals($expectedResponse, $this->makeMoveByRequest());
    }

    /**
     * @test
     */
    public function testInvalidBoardWillReturn400Response() : void
    {
        $this->validator
            ->expects($this->once())
            ->method('validateMoveByRequest')
            ->willThrowException(new \Exception('Error'));

        $this->assertEquals(new JsonResponse(['error' => 'Error'], 400), $this->makeMoveByRequest());
    }

    /**
     * @return \stdClass
     */
    private function createRequestObject() : \stdClass
    {
        $dto = new \stdClass();
        $dto->boardState = [];
        $dto->playerUnit = 'X';

        return $dto;
    }

    /**
     * @return JsonResponse
     */
    private function makeMoveByRequest() : JsonResponse
    {
        return $this->maker->makeMoveByRequest(new Request());
    }

    /**
     * @param string $winner
     * @param bool $playerWins
     * @param bool $botWins
     * @param bool $tieGame
     * @param $winnerPositions
     * @param $nextMove
     * @return JsonResponse
     */
    private function fabricateResponse(
        string $winner,
        bool $playerWins,
        bool $botWins,
        bool $tieGame,
        array $winnerPositions,
        array $nextMove
    ) : JsonResponse {
        $responseDto = new GameResultDto();
        $responseDto->winner = $winner;
        $responseDto->playerWins = $playerWins;
        $responseDto->botWins = $botWins;
        $responseDto->tiedGame = $tieGame;
        $responseDto->winnerPositions = $winnerPositions;
        $responseDto->nextMove = $nextMove;

        return new JsonResponse($responseDto);
    }
}
