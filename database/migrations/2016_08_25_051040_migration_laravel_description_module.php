<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MigrationLaravelDescriptionModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ( ! Schema::hasTable('description_categories')) {
            Schema::create('description_categories', function (Blueprint $table) {
                $table->increments('id');

                $table->integer('parent_id')->nullable();
                $table->integer('lft')->nullable();
                $table->integer('rgt')->nullable();
                $table->integer('depth')->nullable();

                // kategoriye bağlı olarak modelde açıklama, fotoğraf ve link olacak mı?
                $table->boolean('has_description')->default(0);
                $table->boolean('has_photo')->default(0);
                $table->boolean('has_link')->default(0);
                // kategoriye bağlı olarak ön yüzde gösterim
                $table->boolean('show_title')->default(1);
                $table->boolean('show_description')->default(1);
                $table->boolean('show_photo')->default(1);
                $table->boolean('show_link')->default(1);
                // çoklu fotoğraf mı? Yoksa sadece tek fotoğraf mı
                $table->boolean('is_multiple_photo')->default(0);

                $table->string('name');
                $table->timestamps();

                $table->engine = 'InnoDB';
            });
        }

        if ( ! Schema::hasTable('descriptions')) {
            Schema::create('descriptions', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('category_id')->unsigned();
                $table->foreign('category_id')->references('id')->on('description_categories')->onDelete('cascade');

                $table->string('title');
                $table->boolean('is_publish')->default(0);
                $table->timestamps();

                $table->engine = 'InnoDB';
            });
        }

        if ( ! Schema::hasTable('description_descriptions')) {
            Schema::create('description_descriptions', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('description_id')->unsigned();
                $table->foreign('description_id')->references('id')->on('descriptions')->onDelete('cascade');

                $table->string('description');

                $table->engine = 'InnoDB';
            });
        }

        if ( ! Schema::hasTable('description_photos')) {
            Schema::create('description_photos', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('description_id')->unsigned();
                $table->foreign('description_id')->references('id')->on('descriptions')->onDelete('cascade');

                $table->string('photo');

                $table->engine = 'InnoDB';
            });
        }

        if ( ! Schema::hasTable('description_links')) {
            Schema::create('description_links', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('description_id')->unsigned();
                $table->foreign('description_id')->references('id')->on('descriptions')->onDelete('cascade');

                $table->string('link');

                $table->engine = 'InnoDB';
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('description_links');
        Schema::drop('description_descriptions');
        Schema::drop('description_photos');
        Schema::drop('descriptions');
        Schema::drop('description_categories');
    }
}
