<?php

declare(strict_types=1);

namespace App\Console\Commands\Setup;

use App\Enums\RoleEnum;
use App\Models\User;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CreateOwnerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:create-owner';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Anilibrary owner if none exists';

    /**
     * Max attempts to provide email or password
     *
     * @var int
     */
    protected int $maxAttempts = 3;

    /**
     * Execute the console command.
     */
    public function handle(UserRepositoryInterface $userRepository): int
    {
        if (config('app.env') === 'production' || $userRepository->findOwner()) {
            return Command::INVALID;
        }

        $email = $this->askEmail();

        if (!$email) {
            $this->error('Exceeded maximum tries to provide valid email address');

            return Command::FAILURE;
        }

        $password = Str::random();

        $user = $userRepository->upsert([
            'name'     => 'owner',
            'email'    => $email,
            'password' => $password,
        ]);

        $user->assignRole(RoleEnum::OWNER->value);
        $user->markEmailAsVerified();

        $this->info('Owner has been created');
        $this->newLine()->info(sprintf('Credentials - email: %s, password: %s', $email, $password));

        return Command::SUCCESS;
    }

    private function askEmail(int $attempts = 1): string
    {
        if ($attempts > $this->maxAttempts) {
            return '';
        }

        try {
            $email = $this->ask('Provide valid email address');

            $validated = Validator::make(
                ['email' => $email],
                ['email' => 'required|email|string|unique:' . User::class]
            )->validated();

            return $validated['email'];
        } catch (ValidationException $exception) {
            $this->warn($exception->getMessage());

            return $this->askEmail(++$attempts);
        }
    }
}
