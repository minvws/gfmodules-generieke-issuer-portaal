<nav
    data-open-label="Menu"
    data-close-label="Sluit menu"
    data-media="(min-width: 42rem)"
    aria-label="{{ __('Main navigation') }}"
    class="collapsible">
    <div class="collapsing-element">
        <ul>
            <li>
                <a href="{{ route('index') }}" @if(\Illuminate\Support\Facades\Route::currentRouteName() === 'index') aria-current="page" @endif><span class="icon icon-home">Home-icoon</span>@lang('Home')</a>
                <a href="{{ route('flow') }}" @if(\Illuminate\Support\Facades\Route::currentRouteName() === 'flow') aria-current="page" @endif>@lang('Flow')</a>
            </li>
        </ul>
    </div>
</nav>

{{--@if(!request()->routeIs('index'))--}}
{{--<nav class="breadcrumb-bar">--}}
{{--    <div>--}}
{{--        <ul>--}}
{{--            <li><a href="{{ route('index') }}"><span class="icon icon-home">Home-icoon</span>@lang('Landing')</a></li>--}}
{{--        </ul>--}}
{{--    </div>--}}
{{--</nav>--}}
{{--@endif--}}
