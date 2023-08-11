<?php

namespace QueryBuilder\Test;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertIsArray;
use PHPUnit\Framework\TestCase;
use QueryBuilder\QueryBuilder;

class QueryBuilderTest extends TestCase
{

    /** @test */
    function select_test()
    {
        QueryBuilder::table('users')->select('name', 'age');
        assertEquals("SELECT name,age FROM `users`", QueryBuilder::getQuery());
    }

    /** @test */
    function select_all_test()
    {
        QueryBuilder::table('users')->all();
        assertEquals("SELECT * FROM `users`", QueryBuilder::getQuery());
    }

    /** @test */
    function insert_test()
    {
        QueryBuilder::table('users')->insert(['name' => 'foo']);
        assertEquals("INSERT INTO `users` (`name`) VALUES ('foo')", QueryBuilder::getQuery());
    }

    /** @test */
    function update_test()
    {
        QueryBuilder::table('users')->update(['age' => '20', 'name' => 'bar']);
        assertEquals("UPDATE `users` SET `age` = '20',`name` = 'bar'", QueryBuilder::getQuery());
    }

    /** @test */
    function delete_test()
    {
        QueryBuilder::table('users')->delete();
        assertEquals("DELETE FROM `users`", QueryBuilder::getQuery());
    }

    /** @test */
    function join_test()
    {
        QueryBuilder::table('users')->join('posts', ['posts.user_id', '=', 'users.id'])->all();
        assertEquals("SELECT * FROM `users` INNER JOIN `posts` ON posts.user_id = users.id", QueryBuilder::getQuery());
    }

    /** @test */
    function custom_query_test()
    {
        QueryBuilder::setQuery("SELECT * FROM `users`");
        assertEquals("SELECT * FROM `users`", QueryBuilder::getQuery());
    }

    /** @test */
    function and_test()
    {
        QueryBuilder::table('users')->all()->where(['id', '=', 1])->and(['name', '=', 'foo']);
        assertEquals("SELECT * FROM `users` WHERE id = '1' AND name = 'foo'", QueryBuilder::getQuery());
    }

    /** @test */
    function or_test()
    {
        QueryBuilder::table('users')->all()->where(['id', '=', 1])->or(['name', '=', 'foo']);
        assertEquals("SELECT * FROM `users` WHERE id = '1' OR name = 'foo'", QueryBuilder::getQuery());
    }

    /** @test */
    function find_test()
    {
        QueryBuilder::table('users')->find(1);
        assertEquals("SELECT * FROM `users` WHERE id = '1'", QueryBuilder::getQuery());
    }

    /** @test */
    function limit_test()
    {
        QueryBuilder::table('users')->select('name')->where(['name', '=', 'foo'])->limit(1);
        assertEquals("SELECT name FROM `users` WHERE name = 'foo' LIMIT 1", QueryBuilder::getQuery());

        // With offset
        QueryBuilder::table('users')->select('name')->where(['name', '=', 'foo'])->limit(4, 2);
        assertEquals("SELECT name FROM `users` WHERE name = 'foo' LIMIT 4 OFFSET 2", QueryBuilder::getQuery());
    }

    /** @test */
    function order_by_test()
    {
        QueryBuilder::table('users')->all()->orderBy(['users.id'], 'DESC');
        assertEquals("SELECT * FROM `users` ORDER BY users.id DESC", QueryBuilder::getQuery());
    }

    /** @test */
    function group_by_test()
    {
        QueryBuilder::table('users')->all()->groupBy(['users.id']);
        assertEquals("SELECT * FROM `users` GROUP BY users.id", QueryBuilder::getQuery());
    }

    /** @test */
    function min_test()
    {
        QueryBuilder::table('users')->select(QueryBuilder::min('id', 'user_min'));
        assertEquals("SELECT MIN(id) AS user_min FROM `users`", QueryBuilder::getQuery());
    }

    /** @test */
    function max_test()
    {
        QueryBuilder::table('users')->select(QueryBuilder::max('id', 'user_max'));
        assertEquals("SELECT MAX(id) AS user_max FROM `users`", QueryBuilder::getQuery());
    }

    /** @test */
    function count_test()
    {
        QueryBuilder::table('users')->select(QueryBuilder::count('id', 'user_count'));
        assertEquals("SELECT COUNT(id) AS user_count FROM `users`", QueryBuilder::getQuery());
    }

    /** @test */
    function betweenOrNotBetwween_test()
    {
        QueryBuilder::table('users')->all()->betweenOrNotBetween('id', 1, 3);
        assertEquals("SELECT * FROM `users` WHERE id BETWEEN 1 AND 3", QueryBuilder::getQuery());

        // Notbetween
        QueryBuilder::table('users')->all()->betweenOrNotBetween('id', 1, 3, true);
        assertEquals("SELECT * FROM `users` WHERE id NOT BETWEEN 1 AND 3", QueryBuilder::getQuery());
    }

    /** @test */
    function inOrNot_test()
    {
        QueryBuilder::table('users')->all()->inOrNotIn('id', [1, 3]);
        assertEquals("SELECT * FROM `users` WHERE id IN (1,3)", QueryBuilder::getQuery());

        // Notin
        QueryBuilder::table('users')->all()->inOrNotIn('id', [1, 3], true);
        assertEquals("SELECT * FROM `users` WHERE id NOT IN (1,3)", QueryBuilder::getQuery());
    }

    /** @test */
    function run_test()
    {
        assertIsArray(
            QueryBuilder::table('users')->all()->run()
        );
    }
}
