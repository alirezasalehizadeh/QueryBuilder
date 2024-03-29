# QueryBuilder

QueryBuilder is a Simple and quick PHP query builder for interaction with database tables using PDO.

## Features

- Simple, light and quick 
- Friendly syntax
- So useful for pure PHP projects



##  Requirements

PHP >= 8


## Getting Started

Just set your database connection to `$connection` variable in `run()` method.

```php
public static function run(): array
    {
        $connection = pdo_connection
        ...
     }
```

And now, ready to go!
##### NOTE: You can check the `dev` branch for more information

## CRUD

##### SELECT

```php
QueryBuilder::table('table_name')->select('name', 'age')->run();
```
##### INSERT

```php
QueryBuilder::table('table_name')->insert(
  [

    'name' => 'alex',
    'age' => 20

  ])
->run();
```

##### UPDATE

```php
QueryBuilder::table('table_name')->update(['name' => 'john', 'age' => 21])->run();
```
##### DELETE

```php
QueryBuilder::table('table_name')->where(['id', '=', 1])->delete()->run();
```


## OTHER EXAMPLES

##### JOIN

```php
QueryBuilder::table('table_name')->join('second_table_name', ['table_name.id', '=', 'second_table_name.person_id'], 'LEFT')->all()->run();
```

##### ORDER BY

```php
QueryBuilder::table('table_name')->all()->orderBy(['table_name.id'], 'DESC')->run();
```
##### GROUP BY

```php
QueryBuilder::table('table_name')->all()->groupBy(['table_name.id'])->run();
```

##### ALL

```php
QueryBuilder::table('table_name')->all()->run();
```


##### FIND

```php
QueryBuilder::table('table_name')->find(1)->run();
```


##### AND

```php
QueryBuilder::table('table_name')->all()->where(['id', '=', 1])->and(['name', '=', 'foo'])->run();
```

##### OR

```php
QueryBuilder::table('table_name')->all()->where(['id', '=', 1])->or(['name', '=', 'foo'])->run();
```


##### LIMIT, OFFSET

```php
QueryBuilder::table('table_name')->select('name')->where(['name', '=', 'foo'])->limit(1)->run();
QueryBuilder::table('table_name')->select('name')->where(['name', '=', 'foo'])->limit(1, 3)->run(); // Limit with offset
```

##### QUERY
```php
QueryBuilder::setQuery("SELECT * FROM table_name")->run();     //  build your custom query
```

##### MAX, MIN, COUNT
```php
//  Max
QueryBuilder::table('table_name')->select(QueryBuilder::max('user_id', 'id'), 'name')->run();

//  Min
QueryBuilder::table('table_name')->select(QueryBuilder::min('user_id', 'id'), 'name')->run();

//  Count
QueryBuilder::table('table_name')->select(QueryBuilder::count('user_id', 'id'), 'name')->run();

```

##### BETWEEN_OR_NOTBETWEEN
```php
QueryBuilder::table('table_name')->all()->betweenOrNotBetween('id', 1, 3)->run();
QueryBuilder::table('table_name')->all()->betweenOrNotBetween('id', 1, 3, true)->run(); // True if you want NOT BETWEEN operator
```


##### IN_OR_NOTIN
```php
QueryBuilder::table('table_name')->all()->inOrNotIn('id', [1, 3])->run();
QueryBuilder::table('table_name')->all()->inOrNotIn('id', [1, 3], true)->run(); // True if you want NOT IN operator
```
## Contributing
Open issue or send pull request for contributing.


## License

[MIT](LICENSE).
