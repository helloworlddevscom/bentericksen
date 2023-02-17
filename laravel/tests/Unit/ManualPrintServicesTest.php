<?php

namespace Tests\Unit;

use Artisan;
use App\Business;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Role;
use App\Category;
use App\User;
use Bentericksen\PrintServices\ManualPrintService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\SeededTestCase;


class ManualPrintServicesTest extends SeededTestCase
{
  use RefreshDatabase;

  /**
   * The print service should only hold the latest two manuals printed for a non-finalized business.
   * @return void
   * @group business_operation
   */
  public function testManualPrintServiceForNonFinalizedBusiness(): void
  {
    $business = Business::find(1);
    $business->finalized = 0;
    $business->save();

    $this->printServiceManuals($business);

    $folder = storage_path(sprintf('bentericksen/policy-archive/%s', $business->id));

    $this->assertEquals(2, count(array_diff(scandir($folder), ['..', '.'])));
  }

  /**
   * The print service should hold all previous manuals printed for a finalized business.
   * @return void
   * @group business_operation
   */
  public function testManualPrintServiceForFinalizedBusiness(): void
  {
    $business = Business::find(1);
    $business->finalized = 1;
    $business->save();

    $this->printServiceManuals($business);

    $folder = storage_path(sprintf('bentericksen/policy-archive/%s', $business->id));

    $this->assertEquals(4, count(array_diff(scandir($folder), ['..', '.', '.DS_Store', '.gitignore'])));
  }


  /**
   * The print service should hold up to 7 years of policy manuals. Older manuals are deleted.
   * @return void
   * @group business_operation
   */
  public function testManualPrintJobCleanupService(): void
  {
    $business = Business::find(1);
    $business->finalized = 1;
    $business->save();

    $this->printServiceManuals($business, 10);

    $folder = storage_path(sprintf('bentericksen/policy-archive/%s', $business->id));

    Artisan::call('cleanupPolicyArchive');

    $this->assertEquals(7, count(array_diff(scandir($folder), ['..', '.', '.DS_Store', '.gitignore'])));
  }

  protected function printServiceManuals(Business $business, int $count = 4)
  {
    $category = Category::find(1);

    $policy = factory(\App\Policy::class)->create(['business_id'=> $business->id, 'content' => 'test', 'content_raw' => 'test', 'special' => '', 'special_extra' => '', 'category_id' => $category->id]);
    
    $business->policies()->save($policy);

    $business->refresh();

    $folder = storage_path(sprintf('bentericksen/policy-archive/%s', $business->id));

    try {
      $this->delTree($folder);
    } catch (\Exception $e) {
      echo $e->getMessage();
    }
    
    for ( $i = $count; $i >= 0; $i--) {
      $filename = Str::random(40);

      $manualService = new ManualPrintService($business, $filename);
      $manualService->generate();
      $this->modifyBackupPolicyFileTime($business);
      $business->manual = $filename;
      $business->manual_created_at = Carbon::now()->subYears($i);
    }
  }

  protected function delTree($dir)
  {
    if (!is_dir($dir)) {
      return;
    }
    $files = array_diff(scandir($dir), array('.','..'));
     foreach ($files as $file) {
       (is_dir("$dir/$file") && !is_link($dir)) ? $this->delTree("$dir/$file") : unlink("$dir/$file");
     }
     return rmdir($dir);
   }

   protected function modifyBackupPolicyFileTime(Business $business): void
   {
      $id = $business->id;
      $state = $business->state;
      $name = $business->name;
      $time = date('Y-m-d', strtotime($business->manual_created_at));

      $file = storage_path(
        sprintf('bentericksen/policy-archive/%s/%s-%s-%s.pdf', $id, $state, $time, $name )
      );

      if (is_file($file)) {
        touch($file, strtotime($business->manual_created_at));
      }
   }
}
