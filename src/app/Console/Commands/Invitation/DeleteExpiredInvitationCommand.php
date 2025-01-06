<?php

declare(strict_types=1);

namespace App\Console\Commands\Invitation;

use App\Services\Invitation\InvitationService;
use Illuminate\Console\Command;

final class DeleteExpiredInvitationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invitation:delete-expired {--id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete expired invitations';

    /**
     * Execute the console command.
     */
    public function handle(InvitationService $invitationService): int
    {
        $invitationService->deleteExpired($this->option('id'));

        return self::SUCCESS;
    }
}
