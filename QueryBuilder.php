<?php
namespace App\QueryBuilder;

use App\DB;
use PDOStatement;

class QueryBuilder {

    
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
    private static string $query;
    
    
    /**
     * Join string
     *
     * @var string
     */
    private static string $join = '';
    
    
    /**
     * AND operator
     *
     * @var string
     */
    private static string $and = '';
    
    
    /**
     * OR operator
     *
     * @var string
     */
    private static string $or = '';

    
    /**
     * Select statement
     *
     * @var string
     */
    private static string $select;
    
    
    /**
     * ORDER statement
     *
     * @var string
     */
    private static string $orderBy = '';
    
    
    /**
     * Limit clause
     *
     * @var string
     */
    private static string $limit = '';
    
    
    /**
     * Where condition
     *
     * @var string
     */
    private static string $where = '';
   

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
    public static function query(string $query): self
    {
        self::$query = $query;
        return new QueryBuilder;
    }
    
    
    /**
     * Build select statement
     *
     * @param array $columns
     * 
     * @return self
     */
    public static function select(array $columns = ['*']): self
    {
        self::$select = implode(",", $columns);
        $query = "SELECT %s FROM `%s`";
        self::$query = sprintf($query, self::$select, self::$table);
        return new QueryBuilder;
    }
    

    /**
     * Build insert statement
     *
     * @param array $columns
     * 
     * @return self
     */
    public static function insert(array $columns): self 
    {
        $column = array_keys($columns);
        $value = array_values($columns);
        $query = "INSERT INTO `%s` (`%s`) VALUES ('%s')";
        self::$query = sprintf($query, self::$table, implode("`,`", $column), implode("','", $value));
        return new QueryBuilder;
    }


    /**
     * Build update statement
     *
     * @param string $column
     * @param string $value
     * 
     * @return self
     */
    public static function update(string $column, string $value): self
    {
        $query = "UPDATE `%s` SET `%s` = '%s'";
        self::$query = sprintf($query, self::$table, $column, $value);
        return new QueryBuilder;
    }


    /**
     * Build delete statement
     *
     * @return self
     */
    public static function delete(): self
    {
        $query = "DELETE FROM `%s`";
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

        $query = "WHERE %s %s '%s'";
        self::$where = sprintf($query, $column, $operator, $value);
        return new QueryBuilder;
    }


    /**
     * Prepare the query
     * 
     * NOTE : Replace your db connection instead of DB::connection()
     *
     * @param string $query
     * 
     * @return PDOStatement|false
     */
    public static function prepare(string $query): PDOStatement|false
    {
        return DB::connection()->prepare($query);
    }


    /**
     * Build join statement
     *
     * @param string $table
     * @param array $conditions
     * @param string $type
     * 
     * @return self
     */
    public static function join(string $table, array $conditions, string $type = "INNER"): self    
    {
        $query = " %s JOIN `%s` ON %s";
        self::$join .= sprintf($query, $type, $table, implode(" ", $conditions));
        return new QueryBuilder;
    }
    
    
    /**
     * Build and operator
     *
     * @param array $conditions
     * 
     * @return self
     */
    public static function and(array $condition): self    
    {
        $query = " AND %s";
        self::$and .= sprintf($query, implode(" ", $condition));
        return new QueryBuilder;
    }


    /**
     * Build or operator
     *
     * @param array $conditions
     * 
     * @return self
     */
    public static function or(array $condition): self    
    {
        $query = " OR %s";
        self::$or .= sprintf($query, implode(" ", $condition));
        return new QueryBuilder;
    }
    
    
    /**
     * Build limit clause
     *
     * @param integer $number
     * 
     * @return self
     */
    public static function limit(int $number): self    
    {
        $query = " LIMIT %d";
        self::$limit = sprintf($query, $number);
        return new QueryBuilder;
    }
    
    
    /**
     * Build order statement
     *
     * @param array $column
     * @param string $sort
     * 
     * @return self
     */
    public static function orderBy(array $column, string $sort = 'ASC'): self    
    {
        $query = " ORDER BY %s %s";
        self::$orderBy = sprintf($query, implode(",", $column), $sort);
        return new QueryBuilder;
    }


    /**
     * Fetch all rows
     *
     * @return self
     */
    public static function all(): self    
    {
        $query = "SELECT * FROM `%s`";
        self::$query = sprintf($query, self::$table);
        return new QueryBuilder;
    }


    /**
     * Find row with id
     *
     * @param integer $id
     * 
     * @return self
     */
    public static function find(int $id): self    
    {
        $query = "SELECT * FROM `%s` WHERE `id` = %d";
        self::$query = sprintf($query, self::$table, $id);
        return new QueryBuilder;
    }


    /**
     * Run the query
     *
     * @return array|false
     */
    public static function run(): array|false
    {
        self::$query = implode(" ", [self::$query, self::$join, self::$where, self::$and, self::$or, self::$orderBy, self::$limit]);
        $statement = self::prepare(trim(self::$query));
        $statement->execute();
        self::clearVariables();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    
    /**
     * Clear static variables
     *
     * @return void
     */
    private static function clearVariables(): void
    {
        self::$join = self::$where = self::$and = self::$or = self::$orderBy = self::$limit = ''; 
    }
    

    static function __callStatic($name, $arguments):static
    {
        return (new static)->$name(...$arguments);
    }

}
