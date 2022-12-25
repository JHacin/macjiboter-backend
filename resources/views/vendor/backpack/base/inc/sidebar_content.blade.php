<li class="nav-item">
    <a id="goHome" class="nav-link" href="{{ backpack_url(config('routes.admin.dashboard')) }}">
        <i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}
    </a>
</li>

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
    <div class="nav-title">Botrstva</div>
    <a class="nav-link" href="{{ backpack_url(config('routes.admin.sponsorships')) }}">
        <i class="nav-icon la la-gratipay"></i> Botrstva
    </a>
    <a class="nav-link" href="{{ backpack_url(config('routes.admin.special_sponsorships')) }}">
        <i class="nav-icon la la-money-check-alt"></i> Posebna botrstva
    </a>
</li>

<li class="nav-item">
    <div class="nav-title">Pisma botrom</div>
    <a class="nav-link" href="{{ backpack_url(config('routes.admin.sponsorship_message_types')) }}">
        <i class="las la-list nav-icon"></i> Vrste pisem
    </a>
    <a class="nav-link" href="{{ backpack_url(config('routes.admin.sponsorship_messages')) }}">
        <i class="las la-paper-plane nav-icon"></i> Pošiljanje
    </a>
</li>

<li class="nav-item">
    <div class="nav-title">Vsebina</div>
    <a class="nav-link" href="{{ backpack_url(config('routes.admin.news')) }}">
        <i class="nav-icon la la-newspaper"></i> Novice
    </a>
</li>


<li class="nav-item">
    <div class="nav-title">Uporabniki</div>
    <a class="nav-link" href="{{ backpack_url(config('routes.admin.users')) }}">
        <i class="la la-user nav-icon"></i> {{ trans('backpack::permissionmanager.users') }}
    </a>
    <a class="nav-link" href="{{ backpack_url(config('routes.admin.roles')) }}">
        <i class="la la-id-badge nav-icon"></i> {{ trans('backpack::permissionmanager.roles') }}
    </a>
    <a class="nav-link" href="{{ backpack_url(config('routes.admin.permissions')) }}">
        <i class="la la-key nav-icon"></i> {{ trans('backpack::permissionmanager.permission_plural') }}
    </a>
</li>

@role(\App\Models\User::ROLE_SUPER_ADMIN)
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
