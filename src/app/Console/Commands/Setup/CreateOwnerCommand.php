<?php

declare(strict_types=1);

namespace App\Console\Commands\Setup;

use App\DTO\Service\User\UserDTO;
use App\Enums\RoleEnum;
use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

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
    public function handle(UserService $userService): int
    {
        if (config('app.env') === 'production' || $userService->getOwner()) {
            return self::INVALID;
        }

        $email    = $this->askEmail();
        $password = Str::random();

        try {
            DB::transaction(function () use ($userService, $email, $password) {
                $user = $userService->updateOrCreate(
                    new UserDTO(
                        name    : 'owner',
                        email   : $email,
                        password: $password
                    )
                );

                $user->assignRole(RoleEnum::OWNER);
                $user->markEmailAsVerified();
            });
        } catch (Throwable) {
            $this->error('Failed to create owner');
            return self::FAILURE;
        }

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
