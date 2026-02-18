<div class="btn-group mb-1">
    <button type="button" class="btn btn-light btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false">
        @if (App::getLocale() == 'ar')
        {{ LaravelLocalization::getCurrentLocaleName() }}
        <img src="{{ URL::asset('assets/images/flags/EG.png') }}" alt="">
        @else
        {{ LaravelLocalization::getCurrentLocaleName() }}
        <img src="{{ URL::asset('assets/images/flags/US.png') }}" alt="">
        @endif
    </button>

    <div class="dropdown-menu">
        @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
        @if ($localeCode === App::getLocale())
        <a class="dropdown-item active disabled d-flex justify-content-between align-items-center" href="#">
            {{ $properties['native'] }}
            <span>✓</span>
        </a>
        @else
        <a class="dropdown-item" rel="alternate" hreflang="{{ $localeCode }}"
            href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
            {{ $properties['native'] }}
        </a>
        @endif
        @endforeach
    </div>
</div>