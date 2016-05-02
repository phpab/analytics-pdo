<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PhpAb\Storage\Cookie;
use PhpAb\Participation\Manager;
use PhpAb\Analytics\DataCollector\Generic;
use PhpAb\Event\Dispatcher;
use PhpAb\Participation\Filter\Percentage;
use PhpAb\Variant\Chooser\RandomChooser;
use PhpAb\Engine\Engine;
use PhpAb\Test\Test;
use PhpAb\Variant\SimpleVariant;
use PhpAb\Variant\CallbackVariant;

$storage = new Cookie('phpab');
$manager = new Manager($storage);

$analyticsData = new Generic();

$dispatcher = new Dispatcher();
$dispatcher->addSubscriber($analyticsData);

$filter = new Percentage(50);
$chooser = new RandomChooser();

$engine = new Engine($manager, $dispatcher, $filter, $chooser);

$test = new Test('foo_test');
$test->addVariant(new SimpleVariant('_control'));
$test->addVariant(new CallbackVariant('v1', function () {
    echo 'v1';
}));
$test->addVariant(new CallbackVariant('v2', function () {
    echo 'v2';
}));
$test->addVariant(new CallbackVariant('v3', function () {
    echo 'v3';
}));

// Add some tests
$engine->addTest($test);

$engine->start();

// Here starts PDO interaction
$pdo = new PDO('sqlite:./phpab.db');

$options = [
    'runTable' => 'Run',
    'testIdentifierField' => 'testIdentifier',
    'variantIdentifierField' => 'variantIdentifier',
    'userIdentifierField' => 'userIdentifier',
    'scenarioIdentifierField' => 'scenarioIdentifier',
    'runIdentifierField' => 'runIdentifier',
    'createdAtField' => 'createdAt'
];

// Inject PDO instance together with Analytics Data
$analytics = new \PhpAb\Analytics\PDO(
    $analyticsData->getTestsData(),
    $pdo,
    $options
);

// Store it providing a user identifier and a scenario
// typically a URL or a controller name

$analytics->store('1.2.3.4-abc', 'homepage.php');
