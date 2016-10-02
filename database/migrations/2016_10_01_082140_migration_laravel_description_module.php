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

                $table->boolean('datatable_filter')->default(1);
                $table->boolean('datatable_tools')->default(1);
                $table->boolean('datatable_fast_add')->default(1);
                $table->boolean('datatable_group_action')->default(1);
                $table->boolean('datatable_detail')->default(1);
                $table->boolean('description_is_editor')->default(0);
                $table->boolean('config_propagation')->default(0); // ayarlar alt kategorilere yayılsın mı
                $table->integer('photo_width')->default(0); // photo width for aspect ratio
                $table->integer('photo_height')->default(0); // photo height for aspect ratio

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

        if ( ! Schema::hasTable('description_category_thumbnails')) {
            Schema::create('description_category_thumbnails', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('category_id')->unsigned();
                $table->foreign('category_id')->references('id')->on('description_categories')->onDelete('cascade');

                $table->string('slug');
                $table->integer('photo_width')->nullable();
                $table->integer('photo_height')->nullable();

                $table->engine = 'InnoDB';
            });
        }

        if ( ! Schema::hasTable('description_category_columns')) {
            Schema::create('description_category_columns', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('category_id')->unsigned();
                $table->foreign('category_id')->references('id')->on('description_categories')->onDelete('cascade');

                $table->string('name');
                $table->string('type')->default('text');

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

        if ( ! Schema::hasTable('description_description_category_column')) {
            Schema::create('description_description_category_column', function (Blueprint $table) {
                $table->integer('column_id')->unsigned()->index();
                $table->foreign('column_id')->references('id')->on('description_category_columns')->onDelete('cascade');

                $table->integer('description_id')->unsigned()->index();
                $table->foreign('description_id')->references('id')->on('descriptions')->onDelete('cascade');

                $table->string('value');

                $table->engine = 'InnoDB';
            });
        }

        if ( ! Schema::hasTable('description_descriptions')) {
            Schema::create('description_descriptions', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('description_id')->unsigned();
                $table->foreign('description_id')->references('id')->on('descriptions')->onDelete('cascade');

                $table->longText('description');

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
        Schema::drop('description_description_category_column');
        Schema::drop('description_category_columns');
        Schema::drop('description_category_thumbnails');
        Schema::drop('descriptions');
        Schema::drop('description_categories');
    }
}
