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

class PDOTest extends \PHPUnit_Framework_TestCase
{
    private $mockedPDO;
    private $mockedStatement;

    public function setUp()
    {
        parent::setUp();

        $this->mockedStatement = $this->getMockBuilder('\PDOStatement')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockedPDO = $this->getMockBuilder('\PhpAb\Analytics\MockPDO')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockedPDO->method('prepare')
            ->willReturn($this->mockedStatement);
    }

    public function testStore()
    {
        // Arrange
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
        $this->mockedPDO = $this->getMockBuilder('\PhpAb\Analytics\MockPDO')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockedPDO->method('prepare')
            ->willReturn(false);

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
     * @group testme
     */
    public function testExecuteException()
    {
        // Arrange
        $this->mockedPDO = $this->getMockBuilder('\PhpAb\Analytics\MockPDO')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockedStatement = $this->getMockBuilder('\PDOStatement')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockedStatement->method('execute')
             ->will($this->throwException(new \Exception));

        $this->mockedPDO->method('prepare')
            ->willReturn($this->mockedStatement);

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
