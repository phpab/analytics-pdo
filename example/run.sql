/*
 * This is table used in 01-bootstrap.php for sqlite
 */

CREATE TABLE `Run` (
	`testIdentifier`	TEXT NOT NULL,
	`variationIdentifier`	TEXT NOT NULL,
	`userIdentifier`	TEXT NOT NULL,
	`scenarioIdentifier`	TEXT NOT NULL,
	`runIdentifier`	TEXT NOT NULL,
	`createdAt`	TEXT NOT NULL
)
