/**
 * This file is part of phpab/analytics-pdo. (https://github.com/phpab/analytics-pdo)
 *
 * @link https://github.com/phpab/analytics-pdo for the canonical source repository
 * @copyright Copyright (c) 2015-2016 phpab. (https://github.com/phpab/)
 * @license https://github.com/phpab/analytics-pdo/blob/master/LICENSE MIT
 *
 * This is table used in 01-bootstrap.php for sqlite
 */

CREATE TABLE `Run` (
	`testIdentifier`	TEXT NOT NULL,
	`variantIdentifier`	TEXT NOT NULL,
	`userIdentifier`	TEXT NOT NULL,
	`scenarioIdentifier`	TEXT NOT NULL,
	`runIdentifier`	TEXT NOT NULL,
	`createdAt`	TEXT NOT NULL
)
