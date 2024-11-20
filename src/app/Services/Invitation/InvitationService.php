<?php

declare(strict_types=1);

namespace App\Services\Invitation;

use App\DTO\Service\Invitation\InvitationDTO;
use App\Enums\Invitation\StatusEnum;
use App\Filters\QueryFilterInterface;
use App\Mail\Invitation\InvitationMail;
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

    public function send(Invitation $invitation): void
    {
        $url = $this->signedUrlService->createRegistrationLink($invitation->id);

        $invitation->update(['status' => StatusEnum::ACCEPTED]);

        Mail::to($invitation->email)->queue(new InvitationMail($url));
    }

    /**
     * @throws Throwable
     */
    public function createAndSend(InvitationDTO $dto): void
    {
        DB::transaction(function () use ($dto) {
            $invitation = $this->create($dto);

            $this->send($invitation);
        });
    }

    public function decline(Invitation $invitation): void
    {
        // Mail declined message

        $invitation->update(['status' => StatusEnum::DECLINED]);
    }

    /**
     * @param array<QueryFilterInterface> $filters
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
}
