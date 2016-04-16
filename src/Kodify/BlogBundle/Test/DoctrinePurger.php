<?php

namespace Kodify\BlogBundle\Test;

use Doctrine\DBAL\Connection;

class DoctrinePurger
{
    public static function purge(Connection $conn)
    {
        $conn->exec(self::purgeDatabaseSql($conn));
    }

    private static function purgeDatabaseSql(Connection $conn)
    {
        return self::ignoreForeignKeyChecksInSql(self::truncateTablesSql(self::tables($conn)));
    }

    private static function tables(Connection $conn)
    {
        return array_map(
            'reset',
            $conn->query('SHOW FULL TABLES WHERE Table_Type = "BASE TABLE"')->fetchAll()
        );
    }

    private static function truncateTablesSql($tables)
    {
        return implode(' ', self::truncateTablesSqls($tables));
    }

    private static function truncateTablesSqls($tables)
    {
        return array_map([__CLASS__, 'truncateTableSql'], $tables);
    }

    public static function truncateTableSql($table)
    {
        return sprintf('TRUNCATE TABLE `%s`;', $table);
    }

    private static function ignoreForeignKeyChecksInSql($sql)
    {
        return sprintf('SET FOREIGN_KEY_CHECKS = 0; %s SET FOREIGN_KEY_CHECKS = 1;', $sql);
    }
}
