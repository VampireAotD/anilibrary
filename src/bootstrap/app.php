<?php

declare(strict_types=1);

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\Invitation\AcceptedInvitationMiddleware;
use App\Http\Middleware\Invitation\NotDeclinedInvitationMiddleware;
use App\Http\Middleware\Invitation\PendingInvitationMiddleware;
use App\Http\Middleware\Registration\HasInvitationMiddleware;
use App\Http\Middleware\Telegram\RedirectIfHasAssignedUserMiddleware;
use App\Http\Middleware\Telegram\ValidateSignatureMiddleware;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
                  ->withRouting(
                      web     : __DIR__ . '/../routes/web.php',
                      commands: __DIR__ . '/../routes/console.php',
                      channels: __DIR__ . '/../routes/channels.php',
                      health  : '/up',
                  )
                  ->withMiddleware(function (Middleware $middleware) {
                      $middleware->alias([
                          'role'                        => RoleMiddleware::class,
                          'permission'                  => PermissionMiddleware::class,
                          'role_or_permission'          => RoleOrPermissionMiddleware::class,
                          'telegram.signed'             => ValidateSignatureMiddleware::class,
                          'telegram.assigned'           => RedirectIfHasAssignedUserMiddleware::class,
                          'registration.has_invitation' => HasInvitationMiddleware::class,
                          'invitation.pending'          => PendingInvitationMiddleware::class,
                          'invitation.accepted'         => AcceptedInvitationMiddleware::class,
                          'invitation.not_declined'     => NotDeclinedInvitationMiddleware::class,
                      ]);

                      $middleware->web(append: [
                          HandleInertiaRequests::class,
                          AddLinkHeadersForPreloadedAssets::class,
                          Authenticate::class,
                          EnsureEmailIsVerified::class,
                      ]);

                      $middleware->api(prepend: [
                          ThrottleRequests::class . ':api',
                      ]);
                  })
                  ->withExceptions(function (Exceptions $exceptions) {
                      //
                  })->create();
