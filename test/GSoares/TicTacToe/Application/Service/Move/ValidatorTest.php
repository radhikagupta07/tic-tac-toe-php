<?php
namespace GSoares\TicTacToe\Application\Service\Move;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Gabriel Felipe Soares <gabrielfs7@gmail.com>
 */
class ValidatorTest extends TestCase
{

    /**
     * @var Validator|\PHPUnit_Framework_MockObject_MockObject
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
     * @test
     * @expectedException \GSoares\TicTacToe\Exception\InvalidApiRequestException
     * @expectedMessage Syntax Error
     */
    public function testInvalidJsonMustThrowException() : void
    {
        $this->validator->validateMoveByRequest(new Request());
    }

    /**
     * @test
     * @expectedException \GSoares\TicTacToe\Exception\InvalidApiRequestException
     * @expectedMessage boardState and playerUnit must me provided
     */
    public function testMissingRequiredJsonMustThrowException() : void
    {
        $this->validator->validateMoveByRequest(
            new Request(
                [],
                [],
                [],
                [],
                [],
                [],
                '{}'
            )
        );
    }

    /**
     * @test
     */
    public function testReturnDtoIfIsAValidJson() : void
    {
        $expectedDto = new \stdClass();
        $expectedDto->boardState = [];
        $expectedDto->playerUnit = 'X';

        $request = new Request(
            [],
            [],
            [],
            [],
            [],
            [],
            '{ "boardState" : [], "playerUnit" : "X" }'
        );

        $this->assertEquals($expectedDto, $this->validator->validateMoveByRequest($request));
    }
}
