<?php

/**
 * This file is part of phpab/analytics-pdo. (https://github.com/phpab/analytics-pdo)
 *
 * @link https://github.com/phpab/analytics-pdo for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://github.com/phpab/analytics-pdo/blob/master/LICENSE MIT
 */

namespace PhpAb\Analytics;

use PhpAb\Analytics\Exception;
use Mockery as m;

class PDOTest extends \PHPUnit_Framework_TestCase
{
    private $mockedPDO;
    private $mockedStatement;

    public function setUp()
    {
        parent::setUp();
        $this->mockedPDO = m::mock('\PDO');
        $this->mockedStatement = m::mock('\PDOStatement');
    }

    public function testStore()
    {
        // Arrange
        $this->mockedStatement->shouldReceive('bindParam')
            ->andReturn(true);
        $this->mockedStatement->shouldReceive('execute')
            ->andReturn(true);
        $this->mockedPDO->shouldReceive('prepare')
            ->andReturn($this->mockedStatement);

        $analytics = new \PhpAb\Analytics\PDO(
            [
            'bernard' => 'black',
            'walter' => 'white'
            ],
            $this->mockedPDO
        );

        // Act
        $result = $analytics->store('1.2.3.4-abc', 'homepage.php');

        // Assert
        $this->assertSame(true, $result);
    }

    public function testEmptyParticipationStore()
    {
        // Arrange
        $analytics = new \PhpAb\Analytics\PDO([], $this->mockedPDO);

        // Act
        $result = $analytics->store('1.2.3.4-abc', 'homepage.php');

        // Assert
        $this->assertFalse($result);
    }

    /**
     * @expectedException PhpAb\Analytics\Exception\PDOPrepareException
     */
    public function testPrepareException()
    {
        // Arrange
        $this->mockedPDO->shouldReceive('prepare')
            ->andReturn(false);

        $analytics = new PDO(
            [
            'bernard' => 'black',
            'walter' => 'white'
            ],
            $this->mockedPDO
        );

        // Act
        $analytics->store('1.2.3.4-abc', 'homepage.php');

        // Assert
        // ...
    }

    /**
     * @expectedException \Exception
     */
    public function testExecuteException()
    {
        // Arrange
        $this->mockedStatement->shouldReceive('bindParam')
            ->andReturn(true);
        $this->mockedStatement->shouldReceive('execute')
            ->andThrow(new \Exception);
        $this->mockedPDO->shouldReceive('prepare')
            ->andReturn($this->mockedStatement);
        
        $analytics = new PDO(
            [
            'bernard' => 'black',
            'walter' => 'white'
            ],
            $this->mockedPDO
        );

        // Act
        $analytics->store('1.2.3.4-abc', 'homepage.php');

        // Assert
        // ...
    }
}
