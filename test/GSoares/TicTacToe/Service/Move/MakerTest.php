<?php
namespace GSoares\TicTacToe\Service\Move;

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

    public function setUp()
    {
        $this->validator = $this->getMockBuilder('GSoares\TicTacToe\Service\Move\Validator')
            ->getMock();

        $this->maker = new Maker($this->validator);

        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->willReturn(null);

        parent::setUp();
    }

    public function tearDown()
    {
        $this->maker = null;
        $this->validator = null;

        parent::tearDown();
    }

    /**
     * @param $player
     * @param $boardState
     * @param $expectedPositions
     * @dataProvider validNextMovePositionsProvider
     * @test
     */
    public function testMakeMoveWillReturnValidNextPosition($player, array $boardState, array $expectedPositions)
    {
        $nextMove = $this->maker->makeMove($boardState, $player);

        $this->assertContains($nextMove, $expectedPositions);
    }

    public function validNextMovePositionsProvider()
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
}
