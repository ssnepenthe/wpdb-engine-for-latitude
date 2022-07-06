<?php

namespace WpdbEngineForLatitude\Tests;

use Latitude\QueryBuilder\QueryFactory;
use PHPUnit\Framework\TestCase;
use WpdbEngineForLatitude\WpdbEngine;

use function Latitude\QueryBuilder\alias;
use function Latitude\QueryBuilder\func;
use function Latitude\QueryBuilder\on;
use function WpdbEngineForLatitude\field;

/**
 * @see https://developer.wordpress.org/reference/classes/wpdb/#common-tasks
 */
class CodeReferenceExamplesTest extends TestCase
{
    /** @var QueryFactory */
    protected $queryFactory;

    protected function setUp(): void
    {
        $this->queryFactory = new QueryFactory(new WpdbEngine());

        $GLOBALS['wpdb'] = new class () {
            public function __get($name)
            {
                return "wp_{$name}";
            }
        };
    }

    protected function tearDown(): void
    {
        $this->queryFactory = null;

        unset($GLOBALS['wpdb']);
    }

    public function testSelectVariableQueries()
    {
        global $wpdb;

        $query = $this->queryFactory
            ->select(func('COUNT', '*'))
            ->from($wpdb->users)
            ->compile();

        $this->assertSame('SELECT COUNT(*) FROM `wp_users`', $query->sql());
        $this->assertSame([], $query->params());

        $query = $this->queryFactory
            ->select(func('SUM', 'meta_value'))
            ->from($wpdb->postmeta)
            ->where(field('meta_key')->eq('miles'))
            ->compile();

        $this->assertSame(
            'SELECT SUM(`meta_value`) FROM `wp_postmeta` WHERE `meta_key` = %s',
            $query->sql()
        );
        $this->assertSame(['miles'], $query->params());
    }

    public function testSelectRowQueries()
    {
        global $wpdb;

        $query = $this->queryFactory
            ->select()
            ->from($wpdb->links)
            ->where(field('link_id')->eq(10))
            ->compile();

        $this->assertSame('SELECT * FROM `wp_links` WHERE `link_id` = %d', $query->sql());
        $this->assertSame([10], $query->params());
    }

    public function testSelectColumnQueries()
    {
        global $wpdb;

        $query = $this->queryFactory
            ->select('key3.post_id')
            ->from(alias($wpdb->postmeta, 'key3'))
            ->innerJoin(
                alias($wpdb->postmeta, 'key1'),
                on('key1.post_id', 'key3.post_id')
                    ->and(field('key1.meta_key')->eq('model'))
            )
            ->innerJoin(
                alias($wpdb->postmeta, 'key2'),
                on('key2.post_id', 'key3.post_id')
                    ->and(field('key2.meta_key')->eq('year'))
            )
            ->where(field('key3.meta_key')->eq('manufacturer'))
            ->andWhere(field('key3.meta_value')->eq('Ford'))
            ->orderBy('key1.meta_value')
            ->orderBy('key2.meta_value')
            ->compile();

        $this->assertSame(
            'SELECT `key3`.`post_id` FROM `wp_postmeta` AS `key3` INNER JOIN `wp_postmeta` AS '
            . '`key1` ON `key1`.`post_id` = `key3`.`post_id` AND `key1`.`meta_key` = %s INNER JOIN '
            . '`wp_postmeta` AS `key2` ON `key2`.`post_id` = `key3`.`post_id` AND '
            . '`key2`.`meta_key` = %s WHERE `key3`.`meta_key` = %s AND `key3`.`meta_value` = %s '
            . 'ORDER BY `key1`.`meta_value`, `key2`.`meta_value`',
            $query->sql()
        );
        $this->assertSame(['model', 'year', 'manufacturer', 'Ford'], $query->params());

        $query = $this->queryFactory
            ->select('key1.post_id')
            ->from(alias($wpdb->postmeta, 'key1'))
            ->innerJoin(
                alias($wpdb->postmeta, 'key2'),
                on('key2.post_id', 'key1.post_id')
                    ->and(field('key2.meta_key')->eq('Display_Order'))
            )
            ->where(field('key1.meta_key')->eq('Color'))
            // @todo +(0)
            ->orderBy('key2.meta_value', 'asc')
            ->compile();

        $this->assertSame(
            'SELECT `key1`.`post_id` FROM `wp_postmeta` AS `key1` INNER JOIN `wp_postmeta` AS '
            . '`key2` ON `key2`.`post_id` = `key1`.`post_id` AND `key2`.`meta_key` = %s WHERE '
            . '`key1`.`meta_key` = %s ORDER BY `key2`.`meta_value` ASC',
            $query->sql()
        );
        $this->assertSame(['Display_Order', 'Color'], $query->params());
    }

    public function testSelectGenericResultsQueries()
    {
        global $wpdb;

        $query = $this->queryFactory
            ->select('ID', 'post_title')
            ->from($wpdb->posts)
            ->where(field('post_status')->eq('draft'))
            ->andWhere(field('post_author')->eq(5))
            ->compile();

        $this->assertSame(
            'SELECT `ID`, `post_title` FROM `wp_posts` WHERE `post_status` = %s AND `post_author` '
            . '= %d',
            $query->sql()
        );
        $this->assertSame(['draft', 5], $query->params());

        $query = $this->queryFactory
            ->select()
            ->from($wpdb->posts)
            ->where(field('post_status')->eq('draft'))
            ->andWhere(field('post_author')->eq(5))
            ->compile();

        $this->assertSame(
            'SELECT * FROM `wp_posts` WHERE `post_status` = %s AND `post_author` '
            . '= %d',
            $query->sql()
        );
        $this->assertSame(['draft', 5], $query->params());
    }

    public function testInsertRowQueries()
    {
        // @todo Verify against SQL generated by $wpdb->insert().
        global $wpdb;

        $query = $this->queryFactory
            ->insert('table', [
                'column1' => 'value1',
                'column2' => 123,
            ])
            ->compile();

        $this->assertSame(
            'INSERT INTO `table` (`column1`, `column2`) VALUES (%s, %d)',
            $query->sql()
        );
        $this->assertSame(['value1', 123], $query->params());
    }

    // public function testReplaceRowQueries()
    // {
    //     // @todo These need to be constructed semi-manually, there is not direct support.
    // }

    public function testUpdateRowQueries()
    {
        // @todo Verify against sql generated by $wpdb->replace()
        global $wpdb;

        $query = $this->queryFactory
            ->update('table', [
                'column1' => 'value1',
                // Examples provide a string but format as integer...
                'column2' => 123,
            ])
            ->where(field('ID')->eq(1))
            ->compile();

        $this->assertSame(
            'UPDATE `table` SET `column1` = %s, `column2` = %d WHERE `ID` = %d',
            $query->sql()
        );
        $this->assertSame(['value1', 123, 1], $query->params());
    }

    public function testDeleteRowQueries()
    {
        $query = $this->queryFactory
            ->delete('table')
            ->where(field('ID')->eq(1))
            ->compile();

        $this->assertSame('DELETE FROM `table` WHERE `ID` = %d', $query->sql());
        $this->assertSame([1], $query->params());
    }

    public function testGeneralQueries()
    {
        global $wpdb;

        $query = $this->queryFactory
            ->delete($wpdb->postmeta)
            ->where(field('post_id')->eq(13))
            ->andWhere(field('meta_key')->eq('gargle'))
            ->compile();

        $this->assertSame(
            'DELETE FROM `wp_postmeta` WHERE `post_id` = %d AND `meta_key` = %s',
            $query->sql()
        );
        $this->assertSame([13, 'gargle'], $query->params());

        $query = $this->queryFactory
            ->update($wpdb->posts, [
                'post_parent' => 7,
            ])
            ->where(field('ID')->eq(15))
            ->andWhere(field('post_status')->eq('static'))
            ->compile();

        $this->assertSame(
            'UPDATE `wp_posts` SET `post_parent` = %d WHERE `ID` = %d AND `post_status` = %s',
            $query->sql()
        );
        $this->assertSame([7, 15, 'static'], $query->params());
    }
}
