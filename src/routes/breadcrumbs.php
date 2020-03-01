<?php
Breadcrumbs::for('dashboard', function ($trail, $data) {
    $trail->push(_t('dashboard'), route('dashboard'));
});

/**
 * Users
 */
Breadcrumbs::for('dashboard.users.index', function ($trail, $data) {
    $trail->parent('dashboard', $data);
    $trail->push(_t('dashboard.users.index') . (isset($data['users']) ? ' (' . $data['users']->total() . ')' : ''), route('dashboard.users.index'));
});

Breadcrumbs::for('dashboard.users.create', function ($trail, $data) {
    $trail->parent('dashboard.users.index', $data);
    $trail->push(_t('dashboard.users.create'), route('dashboard.users.create'));
});

Breadcrumbs::for('dashboard.users.show', function ($trail, $data) {
    $trail->parent('dashboard.users.index', $data);
    $trail->push($data['user']->name ?: _t('Anonymous'), route('dashboard.users.create'));
});

Breadcrumbs::for('dashboard.users.edit', function ($trail, $data) {
    $trail->parent('dashboard.users.show', $data);
    $trail->push(_t('dashboard.users.edit'), route('dashboard.users.edit', $data['user']->serial ?: $data['user']->id));
});

/**
 * Guards
 */
Breadcrumbs::for('dashboard.guards.index', function ($trail, $data) {
    $trail->parent('dashboard', $data);
    $trail->push(_t('dashboard.guards.index') . (isset($data['guards']) ? ' (' . $data['guards']->total() . ')' : ''), route('dashboard.guards.index'));
});

Breadcrumbs::for('dashboard.guards.create', function ($trail, $data) {
    $trail->parent('dashboard.guards.index', $data);
    $trail->push(_t('dashboard.guards.create'), route('dashboard.guards.create'));
});

Breadcrumbs::for('dashboard.guards.edit', function ($trail, $data) {
    $trail->parent('dashboard.guards.index', $data);
    $trail->push($data['guard']->title, route('dashboard.guards.edit', $data['guard']->serial ?: $data['guard']->id));
});
