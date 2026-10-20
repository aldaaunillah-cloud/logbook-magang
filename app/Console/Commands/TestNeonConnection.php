<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Throwable;

class TestNeonConnection extends Command
{
    protected $signature = 'neon:test';

    protected $description = 'Memeriksa koneksi database Neon';

    public function handle(): int
    {
        $connection = config('neon');

        if (! is_array($connection) || empty($connection['url'])) {
            $this->error(
                'NEON_DATABASE_URL belum terbaca. '
                . 'Periksa file .env dan config/neon.php.'
            );

            return self::FAILURE;
        }

        config([
            'database.connections.neon' => $connection,
        ]);

        try {
            DB::purge('neon');

            DB::connection('neon')->select('SELECT 1');

            $this->info('Koneksi Neon berhasil.');

            return self::SUCCESS;
        } catch (Throwable $exception) {
            $this->error('Koneksi Neon belum berhasil.');

            $this->line(
                'Jenis error: ' . get_class($exception)
            );

            $this->line(
                'Kode error: ' . $exception->getCode()
            );

            return self::FAILURE;
        }
    }
}