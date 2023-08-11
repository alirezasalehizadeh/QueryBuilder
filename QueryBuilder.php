<?php

namespace QueryBuilder;

use QueryBuilder\Connection;

class QueryBuilder
{

    /**
     * Table name
     *
     * @var string
     */
    private static string $table;

    /**
     * Query string
     *
     * @var string
     */
    private static string $query = '';

    /**
     * Query options
     *
     * @var array
     */
    private static array $options = [
        'join' => '',
        'where' => '',
        'and' => '',
        'or' => '',
        'betweenOrNotBetween' => '',
        'inOrNotIn' => '',
        'groupBy' => '',
        'orderBy' => '',
        'limit' => '',
    ];

    /**
     * Get query string
     *
     * @return string
     */
    public static function getQuery(): string
    {
        self::$query = trim(preg_replace('/\s+/', ' ', implode('', array_merge([self::$query], self::$options))));
        self::clearVariables();
        return self::$query;
    }

    /**
     * Set the table name
     *
     * @param string $table
     *
     * @return self
     */
    public static function table(string $table): self
    {
        self::$table = $table;
        return new QueryBuilder;
    }

    /**
     * Write your custom query
     *
     * @param string $query
     *
     * @return self
     */
    public static function setQuery(string $query): self
    {
        self::$query = $query;
        return new QueryBuilder;
    }

    /**
     * Build select statement
     *
     * @param $columns
     *
     * @return self
     */
    public static function select(...$columns): self
    {
        $select = implode(',', $columns);
        $query = " SELECT %s FROM `%s` ";
        self::$query = sprintf($query, $select, self::$table);
        return new QueryBuilder;
    }

    /**
     * Build insert statement
     *
     * @param array $column
     *
     * @return self
     */
    public static function insert(array $column): self
    {
        $columns = array_keys($column);
        $values = array_values($column);
        $query = " INSERT INTO `%s` (`%s`) VALUES ('%s') ";
        self::$query = sprintf($query, self::$table, implode("`,`", $columns), implode("','", $values));
        return new QueryBuilder;
    }

    /**
     * Build update statement
     *
     * @param array $updates
     *
     * @return self
     */
    public static function update(array $updates): self
    {
        $update = '';
        foreach ($updates as $column => $value) {
            $update .= "`$column` = '$value',";
        }
        $query = " UPDATE `%s` SET %s ";
        self::$query = sprintf($query, self::$table, rtrim($update, ','));
        return new QueryBuilder;
    }

    /**
     * Build delete statement
     *
     * @return self
     */
    public static function delete(): self
    {
        $query = " DELETE FROM `%s` ";
        self::$query = sprintf($query, self::$table);
        return new QueryBuilder;
    }

    /**
     * Build where statement
     *
     * @param array $condition
     *
     * @return self
     */
    public static function where(array $condition): self
    {
        $column = $condition[0];
        $operator = $condition[1];
        $value = $condition[2];

        $query = " WHERE %s %s '%s' ";
        self::$options['where'] = sprintf($query, $column, $operator, $value);
        return new QueryBuilder;
    }

    /**
     * Minimum function
     *
     * @param string $column
     * @param string $AS
     *
     * @return string
     */
    public static function min(string $column, string $AS): string
    {
        $query = " MIN(%s) AS %s ";
        return sprintf($query, $column, $AS);
    }

    /**
     * Maximum function
     *
     * @param string $column
     * @param string $AS
     *
     * @return string
     */
    public static function max(string $column, string $AS): string
    {
        $query = " MAX(%s) AS %s ";
        return sprintf($query, $column, $AS);
    }

    /**
     * Count function
     *
     * @param string $column
     * @param string $AS
     *
     * @return string
     */
    public static function count(string $column, string $AS): string
    {
        $query = " COUNT(%s) AS %s ";
        return sprintf($query, $column, $AS);
    }

    /**
     * Build join statement
     *
     * @param string $table
     * @param array $condition
     * @param string $type
     *
     * @return self
     */
    public static function join(string $table, array $condition, string $type = "INNER"): self
    {
        $query = " %s JOIN `%s` ON %s ";
        self::$options['join'] .= sprintf($query, $type, $table, implode(" ", $condition));
        return new QueryBuilder;
    }

    /**
     * Build and operator
     *
     * @param array $condition
     *
     * @return self
     */
    public static function and (array $condition): self {
        $column = $condition[0];
        $operator = $condition[1];
        $value = $condition[2];

        $query = " AND %s %s '%s' ";
        self::$options['and'] .= sprintf($query, $column, $operator, $value);
        return new QueryBuilder;
    }

    /**
     * Build or operator
     *
     * @param array $condition
     *
     * @return self
     */
    public static function or (array $condition): self {
        $column = $condition[0];
        $operator = $condition[1];
        $value = $condition[2];

        $query = " OR %s %s '%s' ";
        self::$options['or'] .= sprintf($query, $column, $operator, $value);
        return new QueryBuilder;
    }

    /**
     * Build limit clause
     *
     * @param integer $count
     * @param integer $offset
     *
     * @return self
     */
    public static function limit(int $count, int $offset = null): self
    {
        $query = " LIMIT %d ";
        self::$options['limit'] = sprintf($query, $count);
        if ($offset) {
            $query .= " OFFSET %d";
            self::$options['limit'] = sprintf($query, $count, $offset);
        }
        return new QueryBuilder;
    }

    /**
     * Build orderBy statement
     *
     * @param array $column
     * @param string $sort
     *
     * @return self
     */
    public static function orderBy(array $column, string $sort = 'ASC'): self
    {
        $query = " ORDER BY %s %s ";
        self::$options['orderBy'] = sprintf($query, implode(",", $column), $sort);
        return new QueryBuilder;
    }

    /**
     * Build groupBy statement
     *
     * @param array $columns
     *
     * @return self
     */
    public static function groupBy(array $columns): self
    {
        $query = " GROUP BY %s ";
        self::$options['groupBy'] = sprintf($query, implode(",", $columns));
        return new QueryBuilder;
    }

    /**
     * Fetch all records
     *
     * @return self
     */
    public static function all(): self
    {
        return self::select('*');
    }

    /**
     * Find record by id
     *
     * @param integer $id
     *
     * @return self
     */
    public static function find(int $id): self
    {
        return self::all()->where(['id', '=', $id]);
    }

    /**
     * Build between or notBetween operator
     *
     * @param string $column
     * @param $firstValue
     * @param $secondValue
     * @param boolean $notBetween
     *
     * @return self
     */
    public static function betweenOrNotBetween(string $column, $firstValue, $secondValue, bool $notBetween = false): self
    {
        $query = " WHERE %s %s BETWEEN %s AND %s ";

        $notBetween
        ? self::$options['betweenOrNotBetween'] = sprintf($query, $column, 'NOT', $firstValue, $secondValue)
        : self::$options['betweenOrNotBetween'] = sprintf($query, $column, '', $firstValue, $secondValue);

        return new QueryBuilder;
    }

    /**
     * Build in or notIn operator
     *
     * @param string $column
     * @param array $values
     * @param boolean $notIn
     *
     * @return self
     */
    public static function inOrNotIn(string $column, array $value, bool $notIn = false): self
    {
        $query = " WHERE %s %s IN (%s) ";

        $notIn
        ? self::$options['inOrNotIn'] = sprintf($query, $column, 'NOT', implode(",", $value))
        : self::$options['inOrNotIn'] = sprintf($query, $column, '', implode(",", $value));

        return new QueryBuilder;
    }

    /**
     * Run the query
     *
     * @return array
     */
    public static function run(): array
    {
        $connection = Connection::getInstance();

        $statement = $connection->prepare(self::getQuery());
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Clear static variables
     *
     * @return void
     */
    private static function clearVariables(): void
    {
        foreach (self::$options as $key => $value) {
            self::$options[$key] = '';
        }
    }

    public static function __callStatic($name, $arguments): static
    {
        return (new static )->$name(...$arguments);
    }
}
