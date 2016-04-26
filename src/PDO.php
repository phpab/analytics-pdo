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

/**
 * Stores PhpAb participation results using PDO DBAL.
 *
 * @package PhpAb
 */
class PDO
{

    /**
     * @var \PDO PDO Instance
     */
    private $pdo;

    /**
     * @var array Test identifiers and variation identifiers
     */
    private $participations;

    /**
     * @var array Options for PDO insert statements
     */
    private $options;

    /**
     * Initializes a new instance of this class.
     *
     * @param array $participations An array containing tests' chosen variations
     * @param \PDO $pdo PDO instance
     * @param array $options Target table definition
     */
    public function __construct(array $participations, \PDO $pdo, array $options = [])
    {
        $this->participations = $participations;
        $this->pdo = $pdo;
        $this->options = $this->processOptions($options);
    }

    /**
     * Merges default options with userland preferences
     *
     * @param array $userlandOptions Target table definition
     *
     * @return array
     */
    private function processOptions(array $userlandOptions)
    {
        $defaultOptions = [
            'runTable' => 'Run',
            'testIdentifierField' => 'testIdentifier',
            'variationIdentifierField' => 'variationIdentifier',
            'userIdentifierField' => 'userIdentifier',
            'scenarioIdentifierField' => 'scenarioIdentifier',
            'runIdentifierField' => 'runIdentifier',
            'createdAtField' => 'createdAt'
        ];

        return array_merge($defaultOptions, $userlandOptions);
    }

    /**
     * Persists tests participation using the provided PDO instance
     *
     * @param string $userIdentifier Web user unique identification
     * @param string $scenarioIdentifier Scenario where tests has been executed (ie. url)
     *
     * @return boolean
     */
    public function store($userIdentifier, $scenarioIdentifier)
    {
        if (empty($this->participations)) {
            return false;
        }

        $uniqueRunIdentifier = uniqid('', true);

        foreach ($this->participations as $testIdentifier => $variationIdentifier) {
            $sql = 'INSERT INTO ' . $this->options['runTable'] . ' '
                . '(' . $this->options['testIdentifierField'] . ', '
                . $this->options['variationIdentifierField'] . ', '
                . $this->options['userIdentifierField'] . ', '
                . $this->options['scenarioIdentifierField'] . ', '
                . $this->options['runIdentifierField'] . ', '
                . $this->options['createdAtField'] . ')'
                . ' VALUES '
                . '(:testIdentifier, :variationIdentifier, :userIdentifier, '
                . ':scenarioIdentifier, :runIdentifier, :createdAt)';

            $currentTimeStamp = date('Y-m-d H:i:s');

            $statement = $this->pdo->prepare($sql);

            if (!$statement instanceof \PDOStatement) {
                throw new Exception\PDOPrepareException('Could not prepare PDO statemente. Check configuration.');
            }

            try {
                $statement->bindParam(':testIdentifier', $testIdentifier);
                $statement->bindParam(':variationIdentifier', $variationIdentifier);
                $statement->bindParam(':userIdentifier', $userIdentifier);
                $statement->bindParam(':scenarioIdentifier', $scenarioIdentifier);
                $statement->bindParam(':runIdentifier', $uniqueRunIdentifier);
                $statement->bindParam(':createdAt', $currentTimeStamp);
                $statement->execute();
            } catch (Exception $exc) {
                throw $exc;
            }
        }

        return true;
    }
}
