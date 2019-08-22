<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingLocaleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setting_locale', function (Blueprint $table) {
            $table->string('setting_key', 255);
            $table->string('locale_code', 2);

            $table->foreign('setting_key')
                ->references('key')
                ->on('setting')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->primary(['locale_code', 'setting_key']);

            $table->text('value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('setting_locale', function (Blueprint $table) {
            $table->dropForeign('setting_locale_setting_key_foreign');
        });

        Schema::dropIfExists('setting_locale');
    }
}
