# QueryBuilder

QueryBuilder is a Simple and quick PHP query builder for interaction with database tables using PDO.

## Getting Started

Before any action, include your database connection into prepare() method.

```php
public static function prepare(string $query): PDOStatement|false
    {
        return database_connection->prepare($query);
    }
```

Now, ready to go!

## CRUD

##### SELECT

```php
QueryBuilder::table('table_name')->select()->run();  //  select all
QueryBuilder::table('table_name')->select(['name'])->run();
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
QueryBuilder::table('table_name')->join('second_table_name', ['table_name.id', '=', 'second_table_name.person_id'], 'LEFT')->select()->run();
```

##### ORDER BY

```php
QueryBuilder::table('table_name')->select()->orderBy(['table_name.id'], 'DESC')->run();
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
QueryBuilder::table('table_name')->select()->where(['id', '=', 1])->and(['name', '=', 'foo'])->run();
```

##### OR

```php
QueryBuilder::table('table_name')->select()->where(['id', '=', 1])->or(['name', '=', 'foo'])->run();
```


##### LIMIT

```php
QueryBuilder::table('table_name')->select(['name'])->where(['name', '=', 'foo'])->limit(1)->run();
```

## Contributing
Send your pull requests for contributing.


## License

[MIT](LICENSE).
