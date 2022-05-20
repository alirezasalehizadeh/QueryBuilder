# QueryBuilder

QueryBuilder is a Simple and quick PHP query builder for interaction with database tables using PDO.

## Features

- Simple, light and quick 
- Friendly syntax
- So useful for pure PHP projects



##  Requirements

PHP >= 8

Composer >= 2


## Usage

- Fill `db.php` options
- Create `index.php` file and write your query in it
- Run `php -S localhost:8080`
##### Test :
- Run `composer install`
- In `vendor/bin` folder run `php phpunit ../../test/QueryBuilderTest.php`


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

])->run();
```

##### UPDATE

```php
QueryBuilder::table('table_name')->update('age', 21)->run();
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


##### LIMIT

```php
QueryBuilder::table('table_name')->select('name')->where(['name', '=', 'foo'])->limit(1)->run();
```

##### QUERY
```php
QueryBuilder::setQuery("SELECT * FROM table_name")->run();     //  build your custom query
```
##### MAX, MIN, COUNT, RAND
```php
//  Max
QueryBuilder::table('table_name')->select(QueryBuilder::max('user_id', 'id'), 'name')->run();

//  Min
QueryBuilder::table('table_name')->select(QueryBuilder::min('user_id', 'id'), 'name')->run();

//  Count
QueryBuilder::table('table_name')->select(QueryBuilder::count('user_id', 'id'), 'name')->run();

//  Random
QueryBuilder::random(1)->run();
```

## Contributing
Send your pull requests for contributing.


## License

[MIT](LICENSE).
