<?php

/**
 * This file is part of phpab/analytics-pdo. (https://github.com/phpab/analytics-pdo)
 *
 * @link https://github.com/phpab/analytics-pdo for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://github.com/phpab/analytics-pdo/blob/master/LICENSE MIT
 */

namespace PhpAb\Analytics;

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

        $this->mockedPDO = $this->getMockBuilder('\PDO')
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
}
