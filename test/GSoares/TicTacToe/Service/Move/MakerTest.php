<?php
namespace GSoares\TicTacToe\Service\Move;

use GSoares\TicTacToe\Service\Board\WinnerVerifier;
use PHPUnit\Framework\TestCase;

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
     * @var Validator|\PHPUnit_Framework_MockObject_MockObject
     */
    private $validator;

    /**
     * @var WinnerVerifier|\PHPUnit_Framework_MockObject_MockObject
     */
    private $winnerVerifier;

    public function setUp() : void
    {
        $this->validator = $this->getMockBuilder('GSoares\TicTacToe\Service\Move\Validator')
            ->getMock();

        $this->winnerVerifier = $this->getMockBuilder('GSoares\TicTacToe\Service\Board\WinnerVerifier')
            ->disableOriginalConstructor()
            ->getMock();

        $this->maker = new Maker($this->validator, $this->winnerVerifier);

        $this->winnerVerifier
            ->expects($this->any())
            ->method('doNotValidateUnitCount')
            ->willReturnSelf();

        $this->winnerVerifier
            ->expects($this->any())
            ->method('validateUnitCount')
            ->willReturnSelf();

        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->willReturn(null);

        parent::setUp();
    }

    public function tearDown() : void
    {
        $this->maker = null;
        $this->validator = null;
        $this->winnerVerifier = null;

        parent::tearDown();
    }

    /**
     * @param string $player
     * @param array $boardState
     * @param array $expectedPositions
     * @dataProvider validNextMovePositionsProvider
     * @test
     */
    public function testMakeMoveWillReturnValidNextPosition(
        string $player,
        array $boardState,
        array $expectedPositions
    ) : void {
        $this->winnerVerifier
            ->expects($this->any())
            ->method('verifyPosition')
            ->willReturn([]);

        $nextMove = $this->maker
            ->makeMove($boardState, $player);

        $this->assertContains($nextMove, $expectedPositions);
    }

    /**
     * @return array
     */
    public function validNextMovePositionsProvider() : array
    {
        return [
            [
                # player
                'X',
                # board state
                [
                    ['X', 'O', ''],
                    ['X', 'O', 'O'],
                    ['O',  'X', 'X'],
                ],
                # Possible next positions
                [
                    [2, 0, 'O'],
                ]
            ],
            [
                # player
                'X',
                # board state
                [
                    ['X', 'O', ''],
                    ['X', 'O', 'O'],
                    ['',  '',  ''],
                ],
                # Possible next positions
                [
                    [2, 0, 'O'],
                    [0, 2, 'O'],
                    [1, 2, 'O'],
                    [2, 2, 'O']
                ]

            ]
        ];
    }

    /**
     * @param string $player
     * @param string $bot
     * @param array $boardState
     * @param array $expectedPositions
     * @dataProvider botMoveWillPredictWinnerMoveProvider
     * @test
     */
    public function testBotMoveWillPredictWinnerMove(
        string $player,
        string $bot,
        array $boardState,
        array $expectedPositions
    ) : void {
        $this->winnerVerifier
            ->expects($this->atLeast(1))
            ->method('verifyPosition')
            ->willReturnCallback($this->simulateWinnerPositionVerifier($bot, $expectedPositions));

        $nextMove = $this->maker
            ->makeMove($boardState, $player);

        $this->assertContains($nextMove, $expectedPositions);
    }

    /**
     * @return array
     */
    public function botMoveWillPredictWinnerMoveProvider() : array
    {
        return [
            [
                # player
                'X',
                # bot
                'O',
                # board state
                [
                    ['X', 'O', ''],
                    ['X', '', ''],
                    ['',  '', ''],
                ],
                # Possible next positions
                [
                    [0, 2],
                ]
            ],
            [
                # player
                'X',
                # bot
                'O',
                # board state
                [
                    ['X', 'O', ''],
                    ['X', 'O', ''],
                    ['',  '', ''],
                ],
                # Possible next positions
                [
                    [1, 2],
                ]
            ]
        ];
    }

    /**
     * @param string $player
     * @param array $boardState
     * @param array $expectedPositions
     * @dataProvider playerMoveWillPredictWinnerMoveProvider
     * @test
     */
    public function testPlayerMoveWillPredictWinnerMove(
        string $player,
        array $boardState,
        array $expectedPositions
    ) : void {
        $this->winnerVerifier
            ->expects($this->atLeast(1))
            ->method('verifyPosition')
            ->willReturnCallback($this->simulateWinnerPositionVerifier($player, $expectedPositions));

        $nextMove = $this->maker
            ->makeMove($boardState, $player);

        $this->assertContains($nextMove, $expectedPositions);
    }

    /**
     * @return array
     */
    public function playerMoveWillPredictWinnerMoveProvider()
    {
        return [
            [
                # player
                'X',
                # board state
                [
                    ['X', 'O', ''],
                    ['X', '', ''],
                    ['',  '', ''],
                ],
                # Possible next positions
                [
                    [0, 2],
                ]
            ],
            [
                # player
                'X',
                # board state
                [
                    ['X', 'O', ''],
                    ['X', 'O', ''],
                    ['',  '', ''],
                ],
                # Possible next positions
                [
                    [1, 2],
                ]
            ]
        ];
    }

    /**
     * @param string $referenceUnit
     * @param array $expectedPositions
     * @return callable
     */
    private function simulateWinnerPositionVerifier(string $referenceUnit, array $expectedPositions) : callable
    {
        return function ($simulatedBoardState, $unit) use ($referenceUnit, $expectedPositions) {
            if ($unit == $referenceUnit) {
                foreach ($expectedPositions as $expectedPosition) {
                    $positionX = $expectedPosition[0];
                    $positionY = $expectedPosition[1];

                    if (!empty($simulatedBoardState[$positionY][$positionX])) {
                        return current($expectedPositions);
                    }
                }
            }

            return [];
        };
    }
}
