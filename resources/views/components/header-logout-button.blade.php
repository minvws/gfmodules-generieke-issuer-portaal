<a href="#" id="logout-link">@lang('Logout')</a>

<form id="logout-form" method="post" action="{{ route('ziekenboeg.users.logout') }}" class="inline hidden">
    @csrf
    <button type="submit"><span class="icon	icon-user">@lang('User icon')</span>@lang('Logout')</button>
</form>
