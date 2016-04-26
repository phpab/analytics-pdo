<?php

/**
 * This file is part of phpab/analytics-pdo. (https://github.com/phpab/analytics-pdo)
 *
 * @link https://github.com/phpab/analytics-pdo for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://github.com/phpab/analytics-pdo/blob/master/LICENSE MIT
 */

namespace PhpAb\Analytics;

/**
 * This class solves the problem when PhpUnit tries to serialize
 * a PDO instance
 */
class MockPDO extends \PDO
{
    public function __construct()
    {

    }
}
