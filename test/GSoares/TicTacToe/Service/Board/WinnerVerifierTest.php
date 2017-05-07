<?php
namespace GSoares\TicTacToe\Service\Board;

use GSoares\TicTacToe\Service\Move\Validator;
use PHPUnit\Framework\TestCase;

/**
 * @author Gabriel Felipe Soares <gabrielfs7@gmail.com>
 */
class WinnerVerifierTest extends TestCase
{

    /**
     * @var WinnerVerifier
     */
    private $winnerVerifier;

    /**
     * @var Validator|\PHPUnit_Framework_MockObject_MockObject
     */
    private $validator;

    public function setUp()
    {
        $this->validator = $this->getMockBuilder('GSoares\TicTacToe\Service\Move\Validator')
            ->getMock();

        $this->winnerVerifier = new WinnerVerifier($this->validator);

        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->willReturn(null);

        parent::setUp();
    }

    public function tearDown()
    {
        $this->winnerVerifier = null;
        $this->validator = null;

        parent::tearDown();
    }

    /**
     * @param $player
     * @param array $boardState
     * @param array $expectedWinnerPositions
     * @dataProvider winnerBoardsProvider
     * @test
     */
    public function testWinnerBoardWillReturnCorrectPosition(
        $player,
        array $boardState,
        array $expectedWinnerPositions
    ) {
        $winnerPositions = $this->winnerVerifier
            ->verifyPosition($boardState, $player);

        $this->assertEquals($expectedWinnerPositions, $winnerPositions);
    }

    /**
     * @return array
     */
    public function winnerBoardsProvider()
    {
        return [
            [
                # player
                'X',
                # board state
                [
                    ['X', 'O', 'O'],
                    ['X', 'O', 'O'],
                    ['O', 'X', 'X'],
                ],
                # Winner positions
                [
                    [0, 2],
                    [1, 1],
                    [2, 0],
                    'O'
                ]
            ],
            [
                # player
                'X',
                # board state
                [
                    ['O', 'X', 'O'],
                    ['X', 'O', 'O'],
                    ['X', 'X', 'X'],
                ],
                # Winner positions
                [
                    [0, 2],
                    [1, 2],
                    [2, 2],
                    'X'
                ]
            ],
            [
                # player
                'X',
                # board state
                [
                    ['O', '', ''],
                    ['', 'O', ''],
                    ['X', 'X', 'X'],
                ],
                # Winner positions
                [
                    [0, 2],
                    [1, 2],
                    [2, 2],
                    'X'
                ]
            ],
            [
                # player
                'X',
                # board state
                [
                    ['O', 'X', 'O'],
                    ['X', 'X', 'O'],
                    ['O', 'X', 'X'],
                ],
                # Winner positions
                [
                    [1, 0],
                    [1, 1],
                    [1, 2],
                    'X'
                ]
            ]
        ];
    }

    /**
     * @param array $boardState
     * @dataProvider noWinnersBoardsProvider
     * @test
     */
    public function testNoWinnerBoardWillReturnEmptyArray(array $boardState)
    {
        $winnerPositions = $this->winnerVerifier
            ->verifyPosition($boardState, 'X');

        $this->assertEquals([], $winnerPositions);
    }

    /**
     * @return array
     */
    public function noWinnersBoardsProvider()
    {
        return [
            # board state
            [
                [
                    ['X', 'O', 'X'],
                    ['X', 'O', 'O'],
                    ['O', 'X', 'X']
                ]
            ],
            # board state
            [
                [
                    ['', '', ''],
                    ['', '', ''],
                    ['', '', '']
                ]
            ],
            # board state
            [
                [
                    ['X', 'O', 'X'],
                    ['', '', 'O'],
                    ['O', 'X', '']
                ]
            ]
        ];
    }
}
