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

    public function setUp()
    {
        $this->validator = new Validator();

        parent::setUp();
    }

    public function tearDown()
    {
        $this->validator = null;

        parent::tearDown();
    }

    /**
     * @param $boardState
     * @dataProvider invalidBoardStateProvider
     * @expectedException \InvalidArgumentException
     * @test
     */
    public function testInvalidBoardStateMustThrowException(array $boardState)
    {
        $this->validator->validate($boardState, 'X');
    }

    public function invalidBoardStateProvider()
    {
        return [
            [
                ['X', 'O', null],
                ['X', 'O', 'O'],
                ['O',  'X', 'X'],
            ],
            [
                ['X', 'O', ''],
                ['X', 'O', 'O'],
                ['O',  'X', new \stdClass()],
            ],
            [
                ['X', 'O', ''],
                ['X', 'O', 'O'],
                ['O',  'X'],
            ],
            [[]],
            [[null]],
        ];
    }
}
