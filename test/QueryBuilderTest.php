<?php
include_once "../../QueryBuilder.php";

use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertIsArray;

class QueryBuilderTest extends TestCase{
    
    /** @test */
    function select_test(){
        QueryBuilder::table('orders')->select('name', 'age');
        assertEquals("SELECT name,age FROM `orders`", QueryBuilder::getQuery());
    }
    

    /** @test */
    function select_all_test(){
        QueryBuilder::table('orders')->all();
        assertEquals("SELECT * FROM `orders`", QueryBuilder::getQuery());
    }
    

    /** @test */
    function insert_test(){
        QueryBuilder::table('orders')->insert(['title'=>'foo']);
        assertEquals("INSERT INTO `orders` (`title`) VALUES ('foo')", QueryBuilder::getQuery());
    }
    
    
    /** @test */
    function update_test(){
        QueryBuilder::table('orders')->update('title', 'bar');
        assertEquals("UPDATE `orders` SET `title` = 'bar'", QueryBuilder::getQuery());
    }
    
    
    /** @test */
    function delete_test(){
        QueryBuilder::table('orders')->delete();
        assertEquals("DELETE FROM `orders`", QueryBuilder::getQuery());
    }
    
    
    /** @test */
    function join_test(){
        QueryBuilder::table('orders')->join('orders_date', ['orders.date_id', '=', 'orders_date.id'])->all();
        assertEquals("SELECT * FROM `orders`INNER JOIN `orders_date` ON orders.date_id = orders_date.id", QueryBuilder::getQuery());
    }
    
    
    /** @test */
    function custom_query_test(){
        QueryBuilder::setQuery("SELECT * FROM `orders`");
        assertEquals("SELECT * FROM `orders`", QueryBuilder::getQuery());
    }
    
    
    /** @test */
    function and_test(){
        QueryBuilder::table('orders')->all()->where(['id', '=', 1])->and(['name', '=', 'foo']);
        assertEquals("SELECT * FROM `orders`WHERE id = '1'AND name = 'foo'", QueryBuilder::getQuery());
    }
    
    
    /** @test */
    function or_test(){
        QueryBuilder::table('orders')->all()->where(['id', '=', 1])->or(['name', '=', 'foo']);
        assertEquals("SELECT * FROM `orders`WHERE id = '1'OR name = 'foo'", QueryBuilder::getQuery());
    }
    

    /** @test */
    function find_test(){
        QueryBuilder::table('orders')->find(1);
        assertEquals("SELECT * FROM `orders` WHERE `id` = 1", QueryBuilder::getQuery());
    }
    

    /** @test */
    function limit_test(){
        QueryBuilder::table('orders')->select('name')->where(['name', '=', 'foo'])->limit(1);
        assertEquals("SELECT name FROM `orders`WHERE name = 'foo'LIMIT 1", QueryBuilder::getQuery());
    }
    

    /** @test */
    function order_by_test(){
        QueryBuilder::table('orders')->all()->orderBy(['orders.id'], 'DESC');
        assertEquals("SELECT * FROM `orders`ORDER BY orders.id DESC", QueryBuilder::getQuery());
    }
    
    
    /** @test */
    function run_test(){
        $data = QueryBuilder::table('orders')->all()->run();
        assertIsArray($data);
    }
}

?>