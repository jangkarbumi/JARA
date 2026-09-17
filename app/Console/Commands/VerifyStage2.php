<?php

namespace App\Console\Commands;

use App\Models\TaskList;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class VerifyStage2 extends Command
{
    protected $signature = 'verify:stage2';
    protected $description = 'Verifikasi model, relasi, dan logika Tahap 2';

    public function handle(): int
    {
        $this->info('--- Memulai Verifikasi Tahap 2 ---');

        DB::beginTransaction();

        try {
            // 1. Buat User Owner dan Collaborator
            $owner = User::create([
                'name' => 'Owner Test',
                'email' => 'owner_test_' . uniqid() . '@example.com',
                'password' => bcrypt('password'),
            ]);

            $collab = User::create([
                'name' => 'Collab Test',
                'email' => 'collab_test_' . uniqid() . '@example.com',
                'password' => bcrypt('password'),
            ]);

            $stranger = User::create([
                'name' => 'Stranger Test',
                'email' => 'stranger_test_' . uniqid() . '@example.com',
                'password' => bcrypt('password'),
            ]);

            $this->info('✓ Berhasil membuat akun pengguna pengujian.');

            // 2. Buat TaskList
            $list = TaskList::create([
                'name' => 'Project Alpha',
                'user_id' => $owner->id,
            ]);

            $this->info('✓ Berhasil membuat TaskList: ' . $list->name);

            // 3. Verifikasi Relasi Owner
            if ($list->owner->id === $owner->id && $owner->ownedLists->contains($list)) {
                $this->info('✓ Relasi Owner <-> TaskList berfungsi dengan benar.');
            } else {
                $this->error('✗ Relasi Owner gagal.');
                return 1;
            }

            // 4. Hubungkan Kolaborator via Pivot
            $list->collaborators()->attach($collab->id, ['role' => 'collaborator']);

            if ($list->collaborators->contains($collab) && $collab->collaboratingLists->contains($list)) {
                $role = $list->collaborators()->where('user_id', $collab->id)->first()->pivot->role;
                $this->info("✓ Relasi Kolaborasi via pivot list_user berhasil (Role: {$role}).");
            } else {
                $this->error('✗ Relasi Kolaborasi gagal.');
                return 1;
            }

            // 5. Cek Helper isAccessibleBy
            if ($list->isAccessibleBy($owner) && $list->isAccessibleBy($collab) && ! $list->isAccessibleBy($stranger)) {
                $this->info('✓ Logika otorisasi akses isAccessibleBy valid (Owner: Ya, Kolaborator: Ya, Asing: Tidak).');
            } else {
                $this->error('✗ Logika isAccessibleBy salah.');
                return 1;
            }

            // 6. Cek Accessor Progress Percentage (0 tugas -> 0%)
            $this->info("✓ Perhitungan Progress (0 tugas): {$list->progress_percentage}%");

            // Buat 4 tugas (2 selesai, 2 belum)
            $list->tasks()->create(['name' => 'Tugas 1', 'is_completed' => true]);
            $list->tasks()->create(['name' => 'Tugas 2', 'is_completed' => true]);
            $list->tasks()->create(['name' => 'Tugas 3', 'is_completed' => false]);
            $list->tasks()->create(['name' => 'Tugas 4', 'is_completed' => false]);

            $progress = $list->progress_percentage;
            if ($progress === 50) {
                $this->info("✓ Perhitungan Progress Akurat: {$progress}% (2 selesai dari 4 tugas).");
            } else {
                $this->error("✗ Perhitungan Progress salah: {$progress}% (seharusnya 50%).");
                return 1;
            }

            DB::rollBack();
            $this->info('--- Semua verifikasi Tahap 2 BERHASIL! (DB rolled back) ---');
            return 0;
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->error('Terjadi error: ' . $e->getMessage());
            return 1;
        }
    }
}
