<?php

namespace App\Console\Commands;

use App\Business;
use App\BusinessAsas;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CleanupPolicyArchive extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cleanupPolicyArchive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Clean up policy manuals that are at least 7 years old and not the latest.";
    
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      $baseFolder = storage_path(sprintf('bentericksen/policy-archive'));

      if (!is_dir($baseFolder)) {
        $this->info('Policy archive folder not setup properly, aborting.');
      }

      $folders = array_diff(scandir($baseFolder, 1), ['..', '.', '.DS_Store', '.gitignore']);

      $folderCount = count($folders);

      $this->info("Scanning {$folderCount} folders");
      
      $fimplode = implode(',', array_slice($folders, 0, 5));

      $this->info("Something like {$fimplode}");

      array_walk($folders, [$this, 'checkFolder']);
    }

    protected function checkFolder($directory)
    {
      $baseFolder = storage_path(sprintf('bentericksen/policy-archive/%s', $directory));
      $files = array_diff(scandir($baseFolder, 1), ['..', '.', '.DS_Store', '.gitignore']);
      
      $retentionLimit = config('policy.retention_limit_years');

      $this->info("Checking folder $baseFolder for policies $retentionLimit years or older");

      if (count($files) <= 1) {
        $this->info("Folder $baseFolder has only one file so skipping since automatically the latest.");
        return;
      }

      $stats = array_map(function($file) use($directory) {
        return $this->getStats($directory, $file);
      }, $files);
      
      $baseLine = Carbon::now()->subYears($retentionLimit);

      $stats = array_filter($stats, function($stat) use($baseLine) {
        return $stat['date']->lessThanOrEqualTo($baseLine);
      });

      if (empty(count($stats)) || count($stats) == 1) {
        $this->info('No delete candidates found so skipping.');
        return;
      }

      $latestStat = array_shift($stats);

      $this->info("Keeping file: {$latestStat['file']} as the oldest file at $retentionLimit years.");

      $this->info('Removing anything older...');

      $files = array_map(function($stat) {
        return $stat['file'];
      }, $stats);

      array_walk($files, [$this, 'unlink']);
    }

    protected function getStats($directory, $file): array
    {
      $filePath = storage_path(sprintf('bentericksen/policy-archive/%s/%s', $directory, $file));
      $this->info("Getting stats for $filePath");
      $filePointer = fopen($filePath, 'r');
      
      $fstat = fstat($filePointer);

      fclose($filePointer);

      return [
        'date' => new Carbon($fstat['mtime']),
        'file' => $filePath
      ];
    }

    protected function unlink($file): void
    {
      $this->info("Unlinking file: $file");

      try {
        unlink($file);
      } catch (\Exception $e) {
        echo $e->getMessage();
      }
    }
}