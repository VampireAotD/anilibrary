<?php

declare(strict_types=1);

namespace App\Console\Commands\Setup;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class ResolveOwnerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:resolve-owner';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resolve Anilibrary owner';

    /**
     * Execute the console command.
     */
    public function handle(UserRepositoryInterface $userRepository): int
    {
        if (config('app.env') === 'production') {
            return Command::SUCCESS;
        }

        $email    = $this->askEmail();
        $password = $this->askPassword();

        $user = $userRepository->upsert(
            [
                'name'     => 'owner',
                'email'    => $email,
                'password' => Hash::make($password),
            ]
        );

        $user->markEmailAsVerified();

        $this->info('Owner has been added');

        return Command::SUCCESS;
    }

    private function askEmail(): string
    {
        try {
            $email = $this->ask('Provide email');

            $validated = Validator::make(
                ['email' => $email],
                ['email' => 'required|email|string|unique:' . User::class]
            )->validated();

            return $validated['email'];
        } catch (ValidationException $exception) {
            $this->warn($exception->getMessage());
            return $this->askEmail();
        }
    }

    private function askPassword(): string
    {
        try {
            $password = $this->ask('Provide password');

            $validated = Validator::make(
                ['password' => $password],
                ['password' => Password::required()]
            )->validated();

            return $validated['password'];
        } catch (ValidationException $exception) {
            $this->warn($exception->getMessage());
            return $this->askPassword();
        }
    }
}
