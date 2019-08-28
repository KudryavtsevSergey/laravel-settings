<?php

use Illuminate\Database\Schema\Blueprint;
use Sun\Locale\Migrations\LocaleMigration;
use Sun\Settings\SettingConfig;

class CreateSettingLocaleTable extends LocaleMigration
{
    protected function getTableName(): string
    {
        return SettingConfig::tableName();
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
