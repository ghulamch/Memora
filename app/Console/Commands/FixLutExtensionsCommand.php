<?php

namespace App\Console\Commands;

use App\Models\Lut;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class FixLutExtensionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'luts:fix-extensions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Memperbaiki ekstensi file LUT yang salah (.txt atau .tif menjadi .cube)';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Memulai perbaikan ekstensi file LUT...');
        
        $luts = Lut::whereNotNull('file_path')->get();
        $fixed = 0;
        $skipped = 0;
        $errors = 0;

        $this->info("Ditemukan {$luts->count()} file LUT.");
        
        $progressBar = $this->output->createProgressBar($luts->count());
        $progressBar->start();

        foreach ($luts as $lut) {
            $oldPath = $lut->file_path;
            
            // Skip jika sudah .cube atau .3dl
            if (preg_match('/\.(cube|3dl)$/i', $oldPath)) {
                $skipped++;
                $progressBar->advance();
                continue;
            }

            // Check apakah file ada
            if (!Storage::disk('public')->exists($oldPath)) {
                $this->error("\n✗ File tidak ditemukan: {$oldPath}");
                $errors++;
                $progressBar->advance();
                continue;
            }

            // Baca beberapa baris pertama untuk validasi apakah ini LUT file
            try {
                $content = Storage::disk('public')->get($oldPath);
                $firstLines = substr($content, 0, 200);
                
                // Cek apakah ini valid LUT file
                if (!preg_match('/LUT_3D_SIZE|LUT_1D_SIZE/i', $firstLines)) {
                    $this->warn("\n⚠ Bukan file LUT yang valid: {$oldPath}");
                    $errors++;
                    $progressBar->advance();
                    continue;
                }
            } catch (\Exception $e) {
                $this->error("\n✗ Error membaca file: {$oldPath} - {$e->getMessage()}");
                $errors++;
                $progressBar->advance();
                continue;
            }

            // Generate nama file baru dengan ekstensi .cube
            $newPath = preg_replace('/\.(txt|tif|tiff)$/i', '.cube', $oldPath);
            
            // Jika tidak ada ekstensi, tambahkan .cube
            if ($newPath === $oldPath) {
                $newPath = $oldPath . '.cube';
            }

            // Rename file
            try {
                if (Storage::disk('public')->move($oldPath, $newPath)) {
                    $lut->update(['file_path' => $newPath]);
                    $fixed++;
                    $this->line("\n✓ {$lut->name}: {$oldPath} → {$newPath}");
                } else {
                    $this->error("\n✗ Gagal rename: {$oldPath}");
                    $errors++;
                }
            } catch (\Exception $e) {
                $this->error("\n✗ Error: {$oldPath} - {$e->getMessage()}");
                $errors++;
            }
            
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);
        
        // Summary
        $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->info('RINGKASAN:');
        $this->info("✓ Berhasil diperbaiki: {$fixed} file");
        $this->info("- Dilewati (sudah benar): {$skipped} file");
        if ($errors > 0) {
            $this->error("✗ Error: {$errors} file");
        }
        $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

        return $fixed > 0 ? Command::SUCCESS : Command::FAILURE;
    }
}