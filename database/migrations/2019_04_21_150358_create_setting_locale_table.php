<?php

use Illuminate\Database\Schema\Blueprint;
use Sun\Locale\Migrations\LocaleMigration;

class CreateSettingLocaleTable extends LocaleMigration
{
    protected function getTableName(): string
    {
        return 'setting';
    }

    protected function getTablePrimaryKeyName(): string
    {
        return 'key';
    }

    protected function getLocaleTableFields(Blueprint $table)
    {
        $table->text('value');
    }

    protected function addForeignField(Blueprint $table, string $keyName)
    {
        $table->string($keyName, 255);
    }
}
