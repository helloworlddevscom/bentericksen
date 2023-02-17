<?php

namespace App\Jobs;

use App\User;
use Bentericksen\StreamdentServices\StreamdentAPIService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class PerformStreamdentUserProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * Attempted job tries
     *
     * @var integer
     */
    public $tries = 40;

    /** @var User */
    protected $user;

    /** @var string */
    protected $process;

    /**
     * Attempted login count
     *
     * @var integer
     */
    private $loginAttemptCount = 1;

    /**
     * Create a new job instance.
     *
     * @param User $user
     * @param string $process
     */
    public function __construct(User $user, string $process)
    {
        $this->user = $user;
        $this->process = $process;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        Log::info("PerformStreamdentUserProcess - handle started for user {$this->user->id}");

        switch($this->process) {
            case 'create':
                $this->createStreamdentUser($this->user);
                break;
            case 'toggle':
                  $this->toggleStreamdentUser($this->user);
                  break;
            case 'disable':
                $this->disableStreamdentUser($this->user); //runs when user deleted
                break;
        }
    }

    /**
     * On Job failure
     *
     * @param [type] $exception
     * @return void
     */
    protected function failure(\Exception $exception): void
    {
        Log::error("PerformStreamdentUserProcess - error: {$exception->getMessage()} user: {$this->user->id}");

        if (strpos(strtolower($exception->getMessage()), 'streamdent: login already exists') !== false) {
          //this re-triggers the user observer which then spawns another job and re-runs this logic. This runs in addition to the retry logic, which can create a mess.
          $this->user->update(['streamdent_login_increment' => ++$this->user->streamdent_login_increment]);
        }

        throw new \Exception($exception->getMessage());
    }

    /**
     * @param User $user
     * @return void
     */
    protected function createStreamdentUser(User $user): void
    {
        Log::info("PerformStreamdentUserProcess - create started for user {$user->id}");

        try {
          $streamdentService = new StreamdentAPIService();
          $streamdentService->createUser([
            'user_id' => $user->id,
            'business_id' => $user->business->id,
            'is_active' => $user->terminated ? 0 : 1
          ]);
        } catch(\Exception $e) {
          $this->failure($e);
        }

        //$this->login();
    }

    /**
     * @param User $user
     * @return void
     */
    protected function disableStreamdentUser(User $user): void
    {
      Log::info("PerformStreamdentUserProcess - disable started for user {$user->id}");

      try {
        $streamdentService = new StreamdentAPIService();
        $streamdentService->updateUser([
          'user_id' => $user->id,
          'business_id' => $user->business->id,
          'is_active' => 0
        ]);
      } catch(\Exception $e) {
        $this->failure($e);
      }
    }

    /**
     * @param User $user
     * @return void
     */
    protected function toggleStreamdentUser(User $user): void
    {
      Log::info("PerformStreamdentUserProcess - toggle started for user {$user->id}");

      try {  
        $streamdentService = new StreamdentAPIService();
        $streamdentService->updateUser([
          'user_id' => $user->id,
          'business_id' => $user->business->id,
          'is_active' => $user->terminated ? 0 : 1
        ]);
      } catch(\Exception $e) {
        $this->failure($e);
      }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    protected function login(): void
    {
        try {
            $streamdentService = new StreamdentAPIService();
            $streamdentService->login(
              $this->user->streamdentUser->login,
              Crypt::decryptString($this->user->streamdentUser->password)
            );
        } catch(\Exception $e) {
            $this->handleFailure($e);
        }
    }

    /**
     * Handle login failures
     *
     * @param string $errorMessage
     * @return void
     */
    private function handleFailure(string $errorMessage): void
    {
        if ($this->loginAttemptCount < 10) {
          sleep(5);
          $this->login();
        }

        $this->loginAttemptCount++;

        Log::debug("PerformStreamdentUserProcess - login attempt: {$this->loginAttemptCount}");

        throw new \Exception($errorMessage);
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getProcess(): string
    {
        return $this->process;
    }
}
