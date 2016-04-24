# Analytics-PDO

Store [PhpAb](https://github.com/phpab/phpab) tests participations using [PDO DBAL](http://php.net/manual/en/book.pdo.php).


## Install

Via Composer

``` bash
$ composer require phpab/analytics-pdo
```
Make sure you have installed the PDO driver you intend to use too.

## Usage

This example assumes you have a sqlite3 database created in the same folder where this script is located.
The sqlite3 definition can be found [here](example/run.sql)

``` php
<?php

require_once __DIR__ . '/../vendor/autoload.php';

$storage = new \PhpAb\Storage\Cookie('phpab');
$manager = new \PhpAb\Participation\Manager($storage);

$analyticsData = new \PhpAb\Analytics\DB\DataCollector;

$dispatcher = new \PhpAb\Event\Dispatcher();
$dispatcher->addSubscriber($analyticsData);

$filter = new \PhpAb\Participation\PercentageFilter(50);
$chooser = new \PhpAb\Variant\RandomChooser();

$engine = new PhpAb\Engine\Engine($manager, $dispatcher, $filter, $chooser);

$test = new \PhpAb\Test\Test('foo_test');
$test->addVariant(new \PhpAb\Variant\SimpleVariant('_control'));
$test->addVariant(new \PhpAb\Variant\CallbackVariant('v1', function () {
    echo 'v1';
}));
$test->addVariant(new \PhpAb\Variant\CallbackVariant('v2', function () {
    echo 'v2';
}));
$test->addVariant(new \PhpAb\Variant\CallbackVariant('v3', function () {
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
    'variationIdentifierField' => 'variationIdentifier',
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

```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please open an issue in the issue tracker. We realize
this is not ideal but it's the fastest way to get the issue solved.

## Credits

- [Walter Tamboer](https://github.com/waltertamboer)
- [Patrick Heller](https://github.com/psren)
- [Mariano F.co Benítez Mulet](https://github.com/pachico)
- [All Contributors](https://github.com/phpab/phpab/graphs/contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.