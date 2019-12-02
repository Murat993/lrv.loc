<?php

use App\Entity\Adverts\Advert\Advert;
use App\Entity\Adverts\Attribute;
use App\Entity\Adverts\Category;
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

BreadCrumbs::register('login.phone', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->push('Login', route('login.phone'));
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


// ROOT Adverts
Breadcrumbs::register('adverts.inner_region', function (BreadcrumbsGenerator $crumbs, Regions $region = null, Category $category = null) {
    if ($region && $parent = $region->parent) {
        $crumbs->parent('adverts.inner_region', $parent, $category);
    } else {
        $crumbs->parent('home');
        $crumbs->push('Adverts', route('adverts.index'));
    }
    if ($region) {
        $crumbs->push($region->name, route('adverts.index', $region, $category));
    }
});

Breadcrumbs::register('adverts.inner_category', function (BreadcrumbsGenerator $crumbs, Regions $region = null, Category $category = null) {
    if ($category && $parent = $category->parent) {
        $crumbs->parent('adverts.inner_category', $region, $parent);
    } else {
        $crumbs->parent('adverts.inner_region', $region, $category);
    }
    if ($category) {
        $crumbs->push($category->name, route('adverts.index', $region, $category));
    }
});

Breadcrumbs::register('adverts.index', function (BreadcrumbsGenerator $crumbs, Regions $region = null, Category $category = null) {
    $crumbs->parent('adverts.inner_category', $region, $category);
});

Breadcrumbs::register('adverts.show', function (BreadcrumbsGenerator $crumbs, Advert $advert) {
    $crumbs->parent('adverts.index', $advert->region, $advert->category);
    $crumbs->push($advert->title, route('adverts.show', $advert));
});


// Кабинет
BreadCrumbs::register('cabinet.home', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('home');
    $crumbs->push('Cabinet', route('cabinet.home'));
});


// Кабинет Profile
Breadcrumbs::register('cabinet.profile.home', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('cabinet.home');
    $crumbs->push('Profile', route('cabinet.profile.home'));
});

Breadcrumbs::register('cabinet.profile.edit', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('cabinet.profile.home');
    $crumbs->push('Edit', route('cabinet.profile.edit'));
});


// Кабинет Profile Phone
Breadcrumbs::register('cabinet.profile.phone', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('cabinet.profile.home');
    $crumbs->push('Profile', route('cabinet.profile.phone'));
});


// Кабинет Advert
Breadcrumbs::register('cabinet.adverts.index', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('cabinet.home');
    $crumbs->push('Adverts', route('cabinet.adverts.index'));
});

Breadcrumbs::register('cabinet.adverts.create', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('adverts.index');
    $crumbs->push('Create', route('cabinet.adverts.create'));
});

Breadcrumbs::register('cabinet.adverts.create.region', function (BreadcrumbsGenerator $crumbs, Category $category, Regions $region = null) {
    $crumbs->parent('cabinet.adverts.create');
    $crumbs->push($category->name, route('cabinet.adverts.create.region', [$category, $region]));
});

Breadcrumbs::register('cabinet.adverts.create.advert', function (BreadcrumbsGenerator $crumbs, Category $category, Regions $region = null) {
    $crumbs->parent('cabinet.adverts.create.region', $category, $region);
    $crumbs->push($region ? $region->name : 'All', route('cabinet.adverts.create.advert', [$category, $region]));
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


// Админка Adverts/Advert
Breadcrumbs::register('admin.adverts.adverts.index', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('admin.home');
    $crumbs->push('Categories', route('admin.adverts.adverts.index'));
});

Breadcrumbs::register('admin.adverts.adverts.edit', function (BreadcrumbsGenerator $crumbs, Advert $advert) {
    $crumbs->parent('admin.home');
    $crumbs->push($advert->title, route('admin.adverts.adverts.edit', $advert));
});

Breadcrumbs::register('admin.adverts.adverts.reject', function (BreadcrumbsGenerator $crumbs, Advert $advert) {
    $crumbs->parent('admin.home');
    $crumbs->push($advert->title, route('admin.adverts.adverts.reject', $advert));
});


// Админка Adverts/Category
BreadCrumbs::register('admin.adverts.categories.index', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('admin.home');
    $crumbs->push('Categories', route('admin.adverts.categories.index'));
});

BreadCrumbs::register('admin.adverts.categories.create', function (BreadcrumbsGenerator $crumbs) {
    $crumbs->parent('admin.adverts.categories.index');
    $crumbs->push('Category Create', route('admin.adverts.categories.create'));
});

BreadCrumbs::register('admin.adverts.categories.show', function (BreadcrumbsGenerator $crumbs, Category $category) {
    if ($parent = $category->parent) {
        $crumbs->parent('admin.adverts.categories.show', $parent);
    } else {
        $crumbs->parent('admin.adverts.categories.index');
    }
    $crumbs->push($category->name, route('admin.adverts.categories.show', $category));
});

BreadCrumbs::register('admin.adverts.categories.edit', function (BreadcrumbsGenerator $crumbs, Category $category) {
    $crumbs->parent('admin.adverts.categories.show', $category);
    $crumbs->push('Edit', route('admin.adverts.categories.edit', $category));
});


// Админка Adverts/Category/Attribute

BreadCrumbs::register('admin.adverts.categories.attributes.create', function (BreadcrumbsGenerator $crumbs, Category $category) {
    $crumbs->parent('admin.adverts.categories.show', $category);
    $crumbs->push('Attribute Create', route('admin.adverts.categories.attributes.create', $category));
});

BreadCrumbs::register('admin.adverts.categories.attributes.show', function (BreadcrumbsGenerator $crumbs, Category $category, Attribute $attribute) {
    $crumbs->parent('admin.adverts.categories.show', $category);
    $crumbs->push($category->name, route('admin.adverts.categories.attributes.show', [$category, $attribute]));
});

Breadcrumbs::register('admin.adverts.categories.attributes.edit', function (BreadcrumbsGenerator $crumbs, Category $category, Attribute $attribute) {
    $crumbs->parent('admin.adverts.categories.attributes.show', $category, $attribute);
    $crumbs->push('Edit', route('admin.adverts.categories.attributes.edit', [$category, $attribute]));
});



