<?php
namespace GSoares\TicTacToe\Service\Move;

use PHPUnit\Framework\TestCase;

/**
 * @author Gabriel Felipe Soares <gabrielfs7@gmail.com>
 */
class ValidatorTest extends TestCase
{

    /**
     * @var Validator
     */
    private $validator;

    public function setUp() : void
    {
        $this->validator = new Validator();

        parent::setUp();
    }

    public function tearDown() : void
    {
        $this->validator = null;

        parent::tearDown();
    }

    /**
     * @expectedException \GSoares\TicTacToe\Exception\InvalidPlayerUnitException
     * @expectedExceptionMessage Invalid playerUnit. Allowed O or X
     * @test
     */
    public function testInvalidPlayerUnitMustThrowException() : void
    {
        $this->validator->validate([], 'Z');
    }

    /**
     * @param array $boardState
     * @expectedException \GSoares\TicTacToe\Exception\InvalidBoardException
     * @expectedExceptionMessage You must provide a 3 lines board
     * @dataProvider invalidBoardLineCountProvider
     * @test
     */
    public function testInvalidBoardLineCountMustThrowException(array $boardState) : void
    {
        $this->validator->validate($boardState, 'X');
    }

    /**
     * @return array
     */
    public function invalidBoardLineCountProvider() : array
    {
        return [
            [
                [
                    ['X', 'O', ''],
                    ['X', 'O', 'O']
                ]
            ],
            [
                [
                    ['X', 'O', ''],
                    ['X', 'O', 'O'],
                    ['X', 'O', ''],
                    ['X', 'O', 'O']
                ]
            ]
        ];
    }

    /**
     * @param $boardState
     * @dataProvider invalidBoardNumberOfPositionsProvider
     * @expectedException \GSoares\TicTacToe\Exception\InvalidBoardException
     * @expectedExceptionMessage You must provide a 3 positions line
     * @test
     */
    public function testInvalidBoardNumberOfPositionsMustThrowException(array $boardState) : void
    {
        $this->validator->validate($boardState, 'X');
    }

    /**
     * @return array
     */
    public function invalidBoardNumberOfPositionsProvider() : array
    {
        return [
            [
                [
                    ['X', 'O', ''],
                    ['X', 'O', 'O'],
                    ['O',  'X']
                ]
            ],
            [
                [
                    ['X', 'O', ''],
                    ['X', 'O', 'O'],
                    ['O',  'X', 'O', 'X']
                ]
            ]
        ];
    }

    /**
     * @param $boardState
     * @dataProvider invalidBoardPositionValueProvider
     * @expectedException \GSoares\TicTacToe\Exception\InvalidBoardException
     * @expectedExceptionMessage There is some invalid board position value
     * @test
     */
    public function testInvalidBoardPositionValueMustThrowException(array $boardState) : void
    {
        $this->validator->validate($boardState, 'X');
    }

    /**
     * @return array
     */
    public function invalidBoardPositionValueProvider() : array
    {
        return [
            [
                [
                    ['X', 'O', '    '],
                    ['X', 'O', 'O'],
                    ['O',  'X', 'X']
                ]
            ],
            [
                [
                    ['X', 'O', ''],
                    ['X', 'O', 'O'],
                    ['O',  'X', 'Z']
                ]
            ]
        ];
    }

    /**
     * @param $boardState
     * @dataProvider invalidBoardUnitCountProvider
     * @expectedException \GSoares\TicTacToe\Exception\InvalidBoardException
     * @expectedExceptionMessage Invalid unit count equality
     * @test
     */
    public function testInvalidBoardUnitCountMustThrowException(array $boardState) : void
    {
        $this->validator->validate($boardState, 'X');
    }

    /**
     * @return array
     */
    public function invalidBoardUnitCountProvider() : array
    {
        return [
            [
                [
                    ['O', 'O', 'O'],
                    ['O', 'X', 'O'],
                    ['O',  'O', 'X']
                ]
            ],
            [
                [
                    ['', '', ''],
                    ['', 'X', 'O'],
                    ['',  'X', 'X']
                ]
            ],
            [
                [
                    ['', '', ''],
                    ['', 'O', 'O'],
                    ['',  '', '']
                ]
            ],
        ];
    }
}
