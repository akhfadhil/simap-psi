<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dapils', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->timestamps();
        });

        Schema::create('kecamatans', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->foreignId('dapil_id')->nullable()->constrained('dapils')->nullOnDelete();
            $table->timestamps();

            $table->index('dapil_id');
        });

        Schema::create('desas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kecamatan_id')->constrained('kecamatans')->cascadeOnDelete();
            $table->string('nama');
            $table->timestamps();

            $table->index(['kecamatan_id', 'nama']);
        });

        Schema::create('tps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('desa_id')->constrained('desas')->cascadeOnDelete();
            $table->string('nama');
            $table->timestamps();

            $table->index(['desa_id', 'nama']);
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('role');
            $table->string('name');
            $table->string('password');
            $table->string('phone')->nullable();
            $table->foreignId('kecamatan_id')->nullable()->constrained('kecamatans')->nullOnDelete();
            $table->foreignId('desa_id')->nullable()->constrained('desas')->nullOnDelete();
            $table->foreignId('tps_id')->nullable()->constrained('tps')->nullOnDelete();
            $table->rememberToken();
            $table->timestamps();

            $table->index(['role', 'kecamatan_id']);
            $table->index(['role', 'desa_id']);
            $table->index(['role', 'tps_id']);
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->integer('expiration');
        });

        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->integer('expiration');
        });

        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('queue')->index();
            $table->longText('payload');
            $table->unsignedTinyInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });

        Schema::create('job_batches', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->integer('total_jobs');
            $table->integer('pending_jobs');
            $table->integer('failed_jobs');
            $table->longText('failed_job_ids');
            $table->mediumText('options')->nullable();
            $table->integer('cancelled_at')->nullable();
            $table->integer('created_at');
            $table->integer('finished_at')->nullable();
        });

        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });

        Schema::create('pemilu_settings', function (Blueprint $table) {
            $table->id();
            $table->string('jenis')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('rekap_partais', function (Blueprint $table) {
            $table->id();
            $table->string('jenis');
            $table->unsignedSmallInteger('nomor_urut');
            $table->string('nama_partai');
            $table->foreignId('dapil_id')->nullable()->constrained('dapils')->nullOnDelete();
            $table->timestamps();

            $table->unique(['jenis', 'dapil_id', 'nomor_urut'], 'rekap_partais_unique_party');
            $table->index(['jenis', 'dapil_id']);
        });

        Schema::create('rekap_calegs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partai_id')->constrained('rekap_partais')->cascadeOnDelete();
            $table->unsignedSmallInteger('nomor_urut');
            $table->string('nama_caleg');
            $table->timestamps();

            $table->unique(['partai_id', 'nomor_urut'], 'rekap_calegs_unique_candidate');
        });

        Schema::create('rekap_headers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tps_id')->constrained('tps')->cascadeOnDelete();
            $table->string('jenis');
            $table->enum('status', ['draft', 'perlu_dicek', 'final'])->default('draft');
            $table->text('catatan_internal')->nullable();

            $table->unsignedInteger('dpt_lk')->default(0);
            $table->unsignedInteger('dpt_pr')->default(0);
            $table->unsignedInteger('pengguna_dpt_lk')->default(0);
            $table->unsignedInteger('pengguna_dpt_pr')->default(0);
            $table->unsignedInteger('pengguna_dptb_lk')->default(0);
            $table->unsignedInteger('pengguna_dptb_pr')->default(0);
            $table->unsignedInteger('pengguna_dpk_lk')->default(0);
            $table->unsignedInteger('pengguna_dpk_pr')->default(0);
            $table->unsignedInteger('ss_diterima')->default(0);
            $table->unsignedInteger('ss_digunakan')->default(0);
            $table->unsignedInteger('ss_rusak')->default(0);
            $table->unsignedInteger('ss_sisa')->default(0);
            $table->unsignedInteger('disabilitas_lk')->default(0);
            $table->unsignedInteger('disabilitas_pr')->default(0);
            $table->unsignedInteger('suara_sah')->default(0);
            $table->unsignedInteger('suara_tidak_sah')->default(0);

            $table->foreignId('diinput_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('difinalisasi_at')->nullable();
            $table->timestamps();

            $table->unique(['tps_id', 'jenis']);
            $table->index(['jenis', 'status']);
            $table->index(['jenis', 'tps_id']);
        });

        Schema::create('rekap_partai_suaras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rekap_id')->constrained('rekap_headers')->cascadeOnDelete();
            $table->foreignId('partai_id')->constrained('rekap_partais')->cascadeOnDelete();
            $table->unsignedInteger('suara')->default(0);
            $table->timestamps();

            $table->unique(['rekap_id', 'partai_id']);
            $table->index(['partai_id', 'suara']);
        });

        Schema::create('rekap_caleg_suaras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rekap_id')->constrained('rekap_headers')->cascadeOnDelete();
            $table->foreignId('caleg_id')->constrained('rekap_calegs')->cascadeOnDelete();
            $table->unsignedInteger('suara')->default(0);
            $table->timestamps();

            $table->unique(['rekap_id', 'caleg_id']);
            $table->index(['caleg_id', 'suara']);
        });

        Schema::create('rekap_cell_flags', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis', ['dpr_ri', 'dprd_prov', 'dprd_kab']);
            $table->string('level', 32);
            $table->unsignedBigInteger('entity_id');
            $table->string('row_key', 191);
            $table->foreignId('flagged_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['jenis', 'level', 'entity_id', 'row_key'], 'rekap_cell_flags_unique_cell');
            $table->index(['jenis', 'level', 'entity_id'], 'rekap_cell_flags_lookup_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekap_cell_flags');
        Schema::dropIfExists('rekap_caleg_suaras');
        Schema::dropIfExists('rekap_partai_suaras');
        Schema::dropIfExists('rekap_headers');
        Schema::dropIfExists('rekap_calegs');
        Schema::dropIfExists('rekap_partais');
        Schema::dropIfExists('pemilu_settings');
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('cache_locks');
        Schema::dropIfExists('cache');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
        Schema::dropIfExists('tps');
        Schema::dropIfExists('desas');
        Schema::dropIfExists('kecamatans');
        Schema::dropIfExists('dapils');
    }
};
