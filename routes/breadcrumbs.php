<?php

use App\Entity\Regions;
use App\Entity\User;
use DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;

// Регистрационные крошки
BreadCrumbs::register('home', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->push('Home', route('home'));
});

BreadCrumbs::register('login', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->push('Login', route('login'));
});

BreadCrumbs::register('register', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('login');
    $crumbs->push('Register', route('register'));
});

BreadCrumbs::register('password.request', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('login');
    $crumbs->push('Password Request', route('password.request'));
});

BreadCrumbs::register('password.reset', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('password.request');
    $crumbs->push('Password Reset', route('password.reset'));
});

// Кабинет
BreadCrumbs::register('cabinet', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('home');
    $crumbs->push('Cabinet', route('cabinet'));
});


// Админка
BreadCrumbs::register('admin.home', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->push('Admin', route('admin.home'));
});


// Админка Users
BreadCrumbs::register('admin.users.index', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('admin.home');
    $crumbs->push('Users', route('admin.users.index'));
});

BreadCrumbs::register('admin.users.create', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('admin.users.index');
    $crumbs->push('User Create', route('admin.users.create'));
});

BreadCrumbs::register('admin.users.show', function (BreadcrumbsGenerator $crumbs, User $user) {
    $crumbs->parent('admin.users.index');
    $crumbs->push($user->name, route('admin.users.show', $user));
});

BreadCrumbs::register('admin.users.edit', function (BreadcrumbsGenerator $crumbs, User $user) {
    $crumbs->parent('admin.users.index');
    $crumbs->push('Edit', route('admin.users.edit', $user));
});


// Админка Regions
BreadCrumbs::register('admin.regions.index', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('admin.home');
    $crumbs->push('Regions', route('admin.regions.index'));
});

BreadCrumbs::register('admin.regions.create', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('admin.regions.index');
    $crumbs->push('Region Create', route('admin.regions.create'));
});

BreadCrumbs::register('admin.regions.show', function (BreadcrumbsGenerator $crumbs, Regions $regions) {
    if ($parent = $regions->parent) {
        $crumbs->parent('admin.regions.show', $parent);
    } else {
        $crumbs->parent('admin.regions.index');
    }
    $crumbs->push($regions->name, route('admin.regions.show', $regions));
});

BreadCrumbs::register('admin.regions.edit', function (BreadcrumbsGenerator $crumbs, Regions $regions) {
    $crumbs->parent('admin.regions.show', $regions);
    $crumbs->push('Edit', route('admin.regions.edit', $regions));
});

