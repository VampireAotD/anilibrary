<?php

declare(strict_types=1);

namespace App\Services\Invitation;

use App\DTO\Service\Invitation\InvitationDTO;
use App\Enums\Invitation\StatusEnum;
use App\Filters\QueryFilterInterface;
use App\Helpers\Registration;
use App\Mail\Invitation\AcceptedInvitationMail;
use App\Mail\Invitation\DeclinedInvitationMail;
use App\Models\Invitation;
use App\Services\Url\SignedUrlService;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Throwable;

final readonly class InvitationService
{
    public function __construct(private SignedUrlService $signedUrlService)
    {
    }

    public function create(InvitationDTO $dto): Invitation
    {
        return Invitation::updateOrCreate(['email' => $dto->email], $dto->toArray());
    }

    public function accept(Invitation $invitation): void
    {
        $invitation->update([
            'status'     => StatusEnum::ACCEPTED,
            'expires_at' => Registration::expirationDate(),
        ]);

        $this->send($invitation);
    }

    public function decline(Invitation $invitation): void
    {
        $invitation->update(['status' => StatusEnum::DECLINED]);

        Mail::to($invitation->email)->queue(new DeclinedInvitationMail());
    }

    public function resend(Invitation $invitation): void
    {
        $invitation->update(['expires_at' => Registration::expirationDate()]);

        $this->send($invitation);
    }

    /**
     * @throws Throwable
     */
    public function createAndAccept(InvitationDTO $dto): void
    {
        DB::transaction(function () use ($dto) {
            $invitation = $this->create($dto);

            $this->accept($invitation);
        });
    }

    /**
     * @param list<QueryFilterInterface> $filters
     * @return CursorPaginator<Invitation>
     */
    public function paginate(array $filters = []): CursorPaginator
    {
        return Invitation::filter($filters)->cursorPaginate();
    }

    public function deleteDeclined(?string $id = null): void
    {
        $query = Invitation::declined()->where('created_at', '<=', now()->subYear());

        if ($id) {
            $query->where('id', $id);
        }

        $query->delete();
    }

    public function deleteExpired(?string $id = null): void
    {
        $query = Invitation::expired();

        if ($id) {
            $query->where('id', $id);
        }

        $query->delete();
    }

    private function send(Invitation $invitation): void
    {
        $url = $this->signedUrlService->createRegistrationLink($invitation->id);

        Mail::to($invitation->email)->queue(new AcceptedInvitationMail($url));
    }
}
