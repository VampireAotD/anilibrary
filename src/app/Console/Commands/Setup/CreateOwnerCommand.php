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
     * Default owner email.
     *
     * @var string
     */
    protected string $defaultEmail = 'admin@gmail.com';

    /**
     * Execute the console command.
     */
    public function handle(UserRepositoryInterface $userRepository): int
    {
        if (config('app.env') === 'production' || $userRepository->findOwner()) {
            return self::INVALID;
        }

        $email    = $this->askEmail();
        $password = Str::random();

        $user = $userRepository->upsert([
            'name'     => 'owner',
            'email'    => $email,
            'password' => $password,
        ]);

        $user->assignRole(RoleEnum::OWNER);
        $user->markEmailAsVerified();

        $this->info('Owner has been created');
        $this->newLine()->info(sprintf('Credentials - email: %s, password: %s', $email, $password));

        return self::SUCCESS;
    }

    private function askEmail(): string
    {
        try {
            $email = $this->ask('Provide valid email address or default will be used', $this->defaultEmail);

            $validated = Validator::make(
                ['email' => $email],
                ['email' => 'required|string|email|unique:' . User::class]
            )->validated();

            return $validated['email'];
        } catch (ValidationException $exception) {
            $this->warn($exception->getMessage());

            return '';
        }
    }
}
