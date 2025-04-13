<?php

declare(strict_types=1);

use App\Models\Anime;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('dashboard', static fn(BreadcrumbTrail $trail) => $trail->push('Home', route('dashboard')));

Breadcrumbs::for('profile.edit', static function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard')->push('Edit Profile', route('profile.edit'));
});

Breadcrumbs::for('invitation.index', static function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard')->push('Invitations', route('invitation.index'));
});

Breadcrumbs::for('anime.index', static function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard')->push('Anime', route('anime.index'));
});

Breadcrumbs::for('anime.show', static function (BreadcrumbTrail $trail, Anime $anime) {
    $trail->parent('anime.index')->push($anime->title, route('anime.show', $anime));
});
