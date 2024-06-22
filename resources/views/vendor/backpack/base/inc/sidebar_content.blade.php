@php
    use App\Models\User;
@endphp

<li class="nav-item">
    <div class="nav-title">Muce</div>
    <a class="nav-link" href="{{ backpack_url(config('routes.admin.cats')) }}">
        <i class="nav-icon la la-cat"></i> Muce
    </a>
    <a class="nav-link" href="{{ backpack_url(config('routes.admin.cat_locations')) }}">
        <i class="nav-icon la la-map-marker"></i> Lokacije
    </a>
</li>

<li class="nav-item">
    <div class="nav-title">Botri</div>
    <a class="nav-link" href="{{ backpack_url(config('routes.admin.sponsors')) }}">
        <i class="nav-icon la la-hands-helping"></i> Botri
    </a>
</li>

<li class="nav-item">
    <div class="nav-title">Redna botrstva</div>
    <a class="nav-link" href="{{ backpack_url(config('routes.admin.sponsorships')) }}">
        <i class="nav-icon las la-list"></i> Redna botrstva
    </a>
    <a class="nav-link" href="{{ backpack_url(config('routes.admin.sponsorship_wallpapers')) }}">
        <i class="nav-icon las la-image"></i> Ozadja
    </a>
</li>

<li class="nav-item">
    <div class="nav-title">Posebna botrstva</div>
    <a class="nav-link" href="{{ backpack_url(config('routes.admin.special_sponsorships')) }}">
        <i class="nav-icon las la-list"></i> Posebna botrstva
    </a>
</li>

@hasanyrole([User::ROLE_SUPER_ADMIN, User::ROLE_ADMIN])
<li class="nav-item">
    <div class="nav-title">Pisma botrom</div>
    <a class="nav-link" href="{{ backpack_url(config('routes.admin.sponsorship_message_types')) }}">
        <i class="las la-list nav-icon"></i> Vrste pisem
    </a>
    <a class="nav-link" href="{{ backpack_url(config('routes.admin.sponsorship_messages')) }}">
        <i class="las la-share-square nav-icon"></i> Poslana pisma
    </a>
    <a class="nav-link" href="{{ backpack_url(config('routes.admin.sponsorship_messages_add')) }}">
        <i class="las la-envelope nav-icon"></i> Pošlji enemu botru
    </a>
    <a class="nav-link" href="{{ backpack_url(config('routes.admin.notify_active_sponsors')) }}">
        <i class="las la-mail-bulk nav-icon"></i> Pošlji aktivnim botrom
    </a>
</li>
@endhasanyrole

<li class="nav-item">
    <div class="nav-title">Vsebina</div>
    <a class="nav-link" href="{{ backpack_url(config('routes.admin.news')) }}">
        <i class="nav-icon la la-newspaper"></i> Novice
    </a>
</li>

@role(User::ROLE_SUPER_ADMIN)
    <li class="nav-item">
        <div class="nav-title">Uporabniki</div>
        <a class="nav-link" href="{{ backpack_url(config('routes.admin.users')) }}">
            <i class="la la-user nav-icon"></i> {{ trans('backpack::permissionmanager.users') }}
        </a>
        <a class="nav-link" href="{{ backpack_url(config('routes.admin.roles')) }}">
            <i class="la la-id-badge nav-icon"></i> {{ trans('backpack::permissionmanager.roles') }}
        </a>
        {{--    <a class="nav-link" href="{{ backpack_url(config('routes.admin.permissions')) }}">--}}
        {{--        <i class="la la-key nav-icon"></i> {{ trans('backpack::permissionmanager.permission_plural') }}--}}
        {{--    </a>--}}
    </li>
@endrole

@role(User::ROLE_SUPER_ADMIN)
    <li class="nav-item">
        <div class="nav-title">Advanced</div>
        <a class="nav-link" href="{{ backpack_url('setting') }}">
            <i class="nav-icon la la-cog"></i> Settings
        </a>
        <a class="nav-link" href="{{ backpack_url('log') }}">
            <i class="nav-icon la la-terminal"></i> Logs
        </a>
        <a class="nav-link" href="{{ backpack_url('backup') }}">
            <i class="nav-icon la la-hdd-o"></i> Backups
        </a>
    </li>
@endrole
