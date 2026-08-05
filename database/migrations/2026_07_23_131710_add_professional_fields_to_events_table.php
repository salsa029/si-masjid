<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        // Langkah 1: Ganti nama kolom "schedule" menjadi "start_at"
        Schema::table('events', function (Blueprint $table) {
            $table->renameColumn('schedule', 'start_at');
        });

        // Langkah 2: Tambahkan seluruh kolom baru
        Schema::table('events', function (Blueprint $table) {
            $table->dateTime('end_at')->nullable()->after('start_at');
            $table->string('slug')->nullable()->unique()->after('title');
            $table->foreignId('category_id')->nullable()->after('slug')->constrained('event_categories')->nullOnDelete();
            $table->string('excerpt')->nullable()->after('description');
            $table->string('speaker_name')->nullable()->after('location');
            $table->decimal('latitude', 10, 7)->nullable()->after('speaker_name');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->string('poster')->nullable()->after('thumbnail');
            $table->boolean('is_featured')->default(false)->after('status');
            $table->unsignedInteger('views_count')->default(0)->after('is_featured');
        });

        // Langkah 3: Backfill "end_at" — asumsikan kegiatan lama berlangsung 2 jam.
        // Dihitung di PHP (bukan DATE_ADD SQL) supaya migration ini tetap
        // jalan di semua driver database, termasuk SQLite saat testing.
        foreach (DB::table('events')->whereNull('end_at')->get() as $event) {
            DB::table('events')->where('id', $event->id)->update([
                'end_at' => Carbon::parse($event->start_at)->addHours(2),
            ]);
        }

        // Langkah 4: Backfill "slug" dari judul kegiatan yang sudah ada
        foreach (DB::table('events')->whereNull('slug')->get() as $event) {
            $baseSlug = Str::slug($event->title);
            $slug = $baseSlug;
            $counter = 2;

            while (DB::table('events')->where('slug', $slug)->exists()) {
                $slug = "{$baseSlug}-{$counter}";
                $counter++;
            }

            DB::table('events')->where('id', $event->id)->update(['slug' => $slug]);
        }
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
            $table->dropColumn([
                'end_at',
                'slug',
                'excerpt',
                'speaker_name',
                'latitude',
                'longitude',
                'poster',
                'is_featured',
                'views_count',
            ]);
        });

        Schema::table('events', function (Blueprint $table) {
            $table->renameColumn('start_at', 'schedule');
        });
    }
};
