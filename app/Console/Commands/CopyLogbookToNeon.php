<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use RuntimeException;
use Throwable;

class CopyLogbookToNeon extends Command
{
    protected $signature = 'neon:copy-logbook';

    protected $description =
        'Mencadangkan SQLite dan menyalin kegiatan ke Neon';

    public function handle(): int
    {
        if (config('database.default') !== 'sqlite') {
            $this->error(
                'Database utama harus tetap SQLite selama penyalinan.'
            );

            return self::FAILURE;
        }

        if (blank(config('neon.url'))) {
            $this->error('NEON_DATABASE_URL belum terbaca.');

            return self::FAILURE;
        }

        config([
            'database.connections.neon' => config('neon'),
        ]);

        try {
            DB::purge('neon');

            $sqlite = DB::connection('sqlite');
            $neon = DB::connection('neon');

            $neon->select('SELECT 1');

            if (
                Schema::connection('neon')->hasTable('activities')
                && $neon->table('activities')->exists()
            ) {
                $this->warn(
                    'Neon sudah memiliki kegiatan. Proses dihentikan '
                    . 'agar data yang ada tidak tertimpa.'
                );

                return self::FAILURE;
            }

            $backupDirectory = storage_path('app/private/backups');

            File::ensureDirectoryExists($backupDirectory);

            $backupPath = $backupDirectory
                . '/logbook_'
                . now()->format('Ymd_His')
                . '_'
                . bin2hex(random_bytes(3))
                . '.sqlite';

            $sqlite->statement('VACUUM INTO ?', [$backupPath]);

            $this->info('Cadangan SQLite berhasil dibuat.');
            $this->line($backupPath);

            $this->info('Memastikan tabel di Neon tersedia…');

            $exitCode = $this->call('migrate', [
                '--database' => 'neon',
                '--force' => true,
            ]);

            if ($exitCode !== self::SUCCESS) {
                $this->error(
                    'Pembuatan tabel belum berhasil. '
                    . 'Catatan belum disalin.'
                );

                return self::FAILURE;
            }

            $fields = [
                'id',
                'activity_date',
                'raw_note',
                'report_note',
                'learning_note',
            ];

            $records = $sqlite
                ->table('activities')
                ->orderBy('id')
                ->get([
                    ...$fields,
                    'created_at',
                    'updated_at',
                ])
                ->map(function ($row): array {
                    $record = (array) $row;

                    // Kolom DATE di PostgreSQL hanya menyimpan Y-m-d.
                    $record['activity_date'] = substr(
                        (string) $record['activity_date'],
                        0,
                        10
                    );

                    if (
                        ! preg_match(
                            '/^\d{4}-\d{2}-\d{2}$/',
                            $record['activity_date']
                        )
                    ) {
                        throw new RuntimeException(
                            'Format tanggal kegiatan tidak valid.'
                        );
                    }

                    return $record;
                })
                ->all();

            $normalize = static function (array $rows) use ($fields): array {
                return array_map(
                    static function (array $row) use ($fields): array {
                        $result = [];

                        foreach ($fields as $field) {
                            $value = $row[$field];

                            if ($field === 'activity_date') {
                                $value = substr((string) $value, 0, 10);
                            }

                            $result[$field] = $value === null
                                ? null
                                : (string) $value;
                        }

                        return $result;
                    },
                    $rows
                );
            };

            $neon->transaction(
                function () use ($neon, $records, $fields, $normalize): void {
                    foreach (array_chunk($records, 100) as $chunk) {
                        $neon->table('activities')->insert($chunk);
                    }

                    $copied = $neon
                        ->table('activities')
                        ->orderBy('id')
                        ->get($fields)
                        ->map(fn ($row): array => (array) $row)
                        ->all();

                    if ($normalize($records) !== $normalize($copied)) {
                        throw new RuntimeException(
                            'Hasil salinan tidak sesuai dengan data sumber.'
                        );
                    }

                    // Sesuaikan nomor ID untuk kegiatan baru berikutnya.
                    $neon->select(
                        "SELECT setval(
                            pg_get_serial_sequence('activities', 'id'),
                            COALESCE(MAX(id), 1),
                            MAX(id) IS NOT NULL
                        ) FROM activities"
                    );
                }
            );

            $this->info(
                count($records)
                . ' catatan berhasil disalin dan diverifikasi ke Neon.'
            );

            $this->info(
                'Database SQLite dan cadangannya tetap tersimpan.'
            );

            $this->info(
                'Website lokal masih menggunakan SQLite.'
            );

            return self::SUCCESS;
        } catch (Throwable $exception) {
            $this->error('Proses belum berhasil.');

            if ($exception instanceof RuntimeException) {
                $this->error($exception->getMessage());
            } else {
                // Hindari menampilkan rincian koneksi atau isi catatan.
                $this->line(
                    'Jenis error: ' . get_class($exception)
                );

                $this->line(
                    'Kode error: ' . $exception->getCode()
                );
            }

            $this->warn(
                'Jangan hapus database SQLite atau cadangannya.'
            );

            return self::FAILURE;
        }
    }
}