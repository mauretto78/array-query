# Array Query

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/mauretto78/array-query/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/mauretto78/array-query/?branch=master)
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

// to add an element to your array. Do this BEFORE make a query on the array
$element = [
   'id' => 4,
   'title' => 'Patricia Lebsack',
   'email' => 'Julianne.OConner@kory.org',
   'rate' => 2,
   'company' => [
       'name' => 'Robel-Corkery',
       'catchPhrase' => 'Multi-tiered zero tolerance productivity',
       'bs' => 'transition cutting-edge web services'
   ]
];
$qb->addElement($element, 4);

// to remove an element from array by his key. Do this BEFORE make a query on the array
$qb->removeElement(3);

```

## Data consistency

QueryBuilder checks for your data consistency. If an inconsistency is detected a `NotConsistentDataException` will be raised:

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
        ],
        'extra-field' => 'this is an extra field'
    ],
]

// NotConsistentDataException will be raised
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

// get first result
$first = $qb->getFirstResult();

// get last result
$last = $qb->getLastResult();

// get a result by index
$thirdResult = $qb->getResult(3);
```

### Avaliable criteria operators

* `=` (default operator, can be omitted)
* `>`
* `<`
* `<=`
* `>=`
* `!=`
* `ARRAY_MATCH`
* `CONTAINS` (case insensitive)
* `ENDS_WITH`
* `EQUALS_DATE`
* `GT_DATE`
* `GTE_DATE`
* `IN_ARRAY`
* `IN_ARRAY_INVERSED`
* `LT_DATE`
* `LTE_DATE`
* `STARTS_WITH`

### Avaliable sorting operators

* `ASC` (default operator, can be omitted)
* `DESC`
* `DATE_ASC`
* `DATE_DESC`

## Joins

You can join arrays. Please consider this full example:

```php
use ArrayQuery\QueryBuilder;

$users = [
    [
        'id' => 1,
        'name' => 'Mauro Cassani',
        'id_category' => 3,
        'email' => 'assistenza@easy-grafica.com'
    ],[
        'id' => 2,
        'name' => 'Mario Rossi',
        'id_category' => 3,
        'email' => 'mario.rossi@gmail.com'
    ],[
        'id' => 3,
        'name' => 'Maria Bianchi',
        'id_category' => 1,
        'email' => 'maria.bianchi@gmail.com'
    ]
];
$category = [
    'id' => 3,
    'name' => 'Web Developer'
];

$qb = QueryBuilder::create($users)
    ->join($category, 'category', 'id_category', 'id')
    ->addCriterion('category.id', 3);

foreach ($qb->getResults() as $element){
    // ...
}
```

## Limit and Offset 

You can add criteria and specify limit and offset for your query results:

```php
use ArrayQuery\QueryBuilder;

$qb = QueryBuilder::create($array);
$qb
    ->addCriterion('title', ['Leanne'], 'IN_ARRAY')
    ->addCriterion('rate', '3', '>')
    ->sortedBy('title')
    ->limit(0, 10);

foreach ($qb->getResults() as $element){
    // ...
}
```

## Working with dates

You can perform queries based on datetime fields. You can use `DATE_ASC` or `DATE_DESC` operator to sort results by date. You must specify **date format** if your format is not `YYYY-mm-dd`:

```php
use ArrayQuery\QueryBuilder;

$qb = QueryBuilder::create($array);
$qb
    ->addCriterion('registration_date', '01/05/2017', 'GT_DATE', 'd/m/Y')
    ->addCriterion('rate', '3', '>')
    ->sortedBy('registration_date', 'DATE_DESC', 'd/m/Y')
    ->limit(0, 10);

foreach ($qb->getResults() as $element){
    // ...
}
```

## Aliases

You can use aliases by using the `as` keyword as a delimiter. Do the following:

```php
use ArrayQuery\QueryBuilder;

$qb = QueryBuilder::create($array);
$qb
    ->addCriterion('name as n', 'Ervin Howell')
    ->addCriterion('username as user', 'Antonette')
    ->addCriterion('address.street as street', 'Victor Plains');

foreach ($qb->getResults() as $element){
    // ...
    // now you have
    // $element['n']
    // $element['user']
    // $element['street']
}
```

## Shuffled results

You can shuffle query results by using `getShuffledResults` method:

```php
use ArrayQuery\QueryBuilder;

$qb = QueryBuilder::create($array);

foreach ($qb->getShuffledResults() as $element){
    // ...
}
```

## More examples

Please refer to [QueryBuilderTest](https://github.com/mauretto78/array-query/blob/master/tests/QueryBuilderTest.php) for more examples.

## Support

If you found an issue or had an idea please refer [to this section](https://github.com/mauretto78/array-query/issues).

## Authors

* **Mauro Cassani** - [github](https://github.com/mauretto78)

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details
