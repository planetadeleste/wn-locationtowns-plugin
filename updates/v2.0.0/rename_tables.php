<?php namespace PlanetaDelEste\LocationTown\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class RenameTables extends Migration
{
    const TABLE_FROM = 'vojtasvoboda_locationtown_towns';
    const TABLE_TO = 'planetadeleste_locationtown_towns';

    public function up()
    {
        if (Schema::hasTable(self::TABLE_FROM) && !Schema::hasTable(self::TABLE_TO)) {
            Schema::rename(self::TABLE_FROM, self::TABLE_TO);
        }
    }

    public function down()
    {
        if (Schema::hasTable(self::TABLE_TO) && !Schema::hasTable(self::TABLE_FROM)) {
            Schema::rename(self::TABLE_TO, self::TABLE_TO);
        }
    }
}