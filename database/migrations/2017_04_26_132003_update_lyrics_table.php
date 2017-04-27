<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateLyricsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lyrics', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->index();
            $table->boolean('published')->default(false);
            $table->integer('viewed')->default(0);
            $table->integer('liked')->default(0);
            $table->unique(['title', 'artist', 'link_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lyrics', function (Blueprint $table) {
            $table->dropIndex('lyrics_user_id_index');
            $table->dropUnique(['title', 'artist', 'link_id', 'user_id']);
            $table->dropColumn(['user_id', 'published', 'viewed', 'liked']);
        });
    }
}
