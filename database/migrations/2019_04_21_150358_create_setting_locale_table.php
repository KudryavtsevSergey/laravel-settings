<?php

use Illuminate\Database\Schema\Blueprint;
use Sun\Locale\Migrations\LocaleMigration;

class CreateSettingLocaleTable extends LocaleMigration
{
    protected function getTableName(): string
    {
        return config('settings.table');
    }

    protected function getTablePrimaryKeyName(): string
    {
        return 'key';
    }

    protected function getLocaleTableFields(Blueprint $table)
    {
        $table->text('value')->nullable();
    }

    protected function addForeignField(Blueprint $table, string $keyName)
    {
        $table->string($keyName, 255);
    }
}
