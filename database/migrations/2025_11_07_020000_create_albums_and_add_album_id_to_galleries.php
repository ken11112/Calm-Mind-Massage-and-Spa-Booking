<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up()
    {
        // create albums table
        if (!Schema::hasTable('albums')) {
            Schema::create('albums', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->nullable()->unique();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        // add album_id to galleries
        if (Schema::hasTable('galleries') && !Schema::hasColumn('galleries', 'album_id')) {
            Schema::table('galleries', function (Blueprint $table) {
                $table->unsignedBigInteger('album_id')->nullable()->after('title');
            });

            // backfill albums from existing galleries.album values
            $distinct = DB::table('galleries')
                ->select('album')
                ->distinct()
                ->pluck('album')
                ->filter(function ($v) {
                    return !is_null($v) && trim($v) !== '';
                })
                ->values();

            // ensure a default 'General' album exists if needed
            $hasGeneral = DB::table('galleries')->whereNull('album')->orWhere('album', '')->exists();
            $mapping = [];
            if ($hasGeneral) {
                $generalId = DB::table('albums')->insertGetId([
                    'name' => 'General',
                    'slug' => Str::slug('General'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $mapping['General'] = $generalId;
            }

            foreach ($distinct as $name) {
                $slug = Str::slug($name ?: 'album');
                // avoid duplicate slugs
                $exists = DB::table('albums')->where('slug', $slug)->first();
                if ($exists) {
                    $id = $exists->id;
                } else {
                    $id = DB::table('albums')->insertGetId([
                        'name' => $name,
                        'slug' => $slug,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
                $mapping[$name] = $id;
            }

            // update galleries rows
            foreach ($mapping as $name => $id) {
                if ($name === 'General') {
                    DB::table('galleries')->whereNull('album')->orWhere('album', '')->update(['album_id' => $id]);
                } else {
                    DB::table('galleries')->where('album', $name)->update(['album_id' => $id]);
                }
            }

            // drop the old album text column
            if (Schema::hasColumn('galleries', 'album')) {
                Schema::table('galleries', function (Blueprint $table) {
                    $table->dropColumn('album');
                });
            }
        }
    }

    public function down()
    {
        // add album column back (nullable)
        if (Schema::hasTable('galleries') && !Schema::hasColumn('galleries', 'album')) {
            Schema::table('galleries', function (Blueprint $table) {
                $table->string('album')->nullable()->after('title');
            });

            // try to copy values back from albums
            $albums = DB::table('albums')->pluck('id', 'name');
            foreach ($albums as $name => $id) {
                DB::table('galleries')->where('album_id', $id)->update(['album' => $name]);
            }
        }

        if (Schema::hasTable('galleries') && Schema::hasColumn('galleries', 'album_id')) {
            Schema::table('galleries', function (Blueprint $table) {
                $table->dropColumn('album_id');
            });
        }

        if (Schema::hasTable('albums')) {
            Schema::dropIfExists('albums');
        }
    }
};
