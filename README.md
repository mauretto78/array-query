# Array Query

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/b2f343d6-2459-4b6f-b2c9-c33a05a482d1/mini.png)](https://insight.sensiolabs.com/projects/b2f343d6-2459-4b6f-b2c9-c33a05a482d1)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/a17231a0420548e182ec58516cd1b562)](https://www.codacy.com/app/mauretto78/array-query?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=mauretto78/array-query&amp;utm_campaign=Badge_Grade)
[![Coverage Status](https://coveralls.io/repos/github/mauretto78/array-query/badge.svg?branch=master)](https://coveralls.io/github/mauretto78/array-query?branch=master)
[![license](https://img.shields.io/github/license/mauretto78/array-query.svg)]()
[![Packagist](https://img.shields.io/packagist/v/mauretto78/array-query.svg)]()

**Array Query** allows you to perform queries on multidimensional arrays.

## Use Cases

This library is suitable for you if you need to perform some queries on:

* static php arrays
* in-memory stored arrays
* parsed json (or yaml) files

## Basic Usage

To instantiate the QueryBuilder do the following:

```php
use ArrayQuery\QueryBuilder;

$array = [
    [
        'id' => 1,
        'title' => 'Leanne Graham',
        'email' => 'Sincere@april.biz',
        'rate' => 5,
        'company' => [
            'name' => 'Romaguera-Jacobson',
            'catchPhrase' => 'Face to face bifurcated interface',
            'bs' => 'e-enable strategic applications'
        ]
    ],
    [
        'id' => 2,
        'title' => 'Ervin Howell',
        'email' => 'Shanna@melissa.tv',
        'rate' => 3,
        'company' => [
            'name' => 'Robel-Corkery',
            'catchPhrase' => 'Multi-tiered zero tolerance productivity',
            'bs' => 'transition cutting-edge web services'
        ]
    ],
    [
        'id' => 3,
        'title' => 'Clementine Bauch',
        'email' => 'Nathan@yesenia.net',
        'rate' => 4,
        'company' => [
            'name' => 'Keebler LLC',
            'catchPhrase' => 'User-centric fault-tolerant solution',
            'bs' => 'revolutionize end-to-end systems'
        ]
    ],
    // ..
]

QueryBuilder::create($array);

```

## Quering, sorting and get results

You can perform queries on your array. You can concatenate criteria:

```php
use ArrayQuery\QueryBuilder;

// ..

$qb = QueryBuilder::create($array);
$qb
    ->addCriterion('title', 'Leanne', 'CONTAINS')
    ->addCriterion('rate', '3', '>')
    ->sortedBy('title', 'DESC');

// you can search by nested keys    
$qb->addCriterion('company.name', 'Romaguera-Jacobson');

// get results    
foreach ($qb->getResults() as $element){
    // ...
}
```

### Avaliable criteria operators

* `=` (default operator, can be omitted)
* `>`
* `<`
* `<=`
* `>=`
* `!=`
* `ARRAY`
* `ARRAY_INVERSED`
* `CONTAINS` (case insensitive)

### Avaliable sorting operators

* `ASC` (default operator, can be omitted)
* `DESC`

## Limit and Offset

You can specify limit and offset for your query results:

```php
use ArrayQuery\QueryBuilder;

$qb = QueryBuilder::create($array);
$qb
    ->addCriterion('title', ['Leanne'], 'ARRAY')
    ->addCriterion('rate', '3', '>')
    ->sortedBy('title')
    ->limit(0, 10);

foreach ($qb->getResults() as $element){
    // ...
}
```

## Support

If you found an issue or had an idea please refer [to this section](https://github.com/mauretto78/array-query/issues).

## Authors

* **Mauro Cassani** - [github](https://github.com/mauretto78)

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details
