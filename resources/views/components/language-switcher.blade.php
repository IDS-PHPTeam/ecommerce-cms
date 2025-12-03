@php
    $multilingual = \App\Models\Setting::get('multilingual', '0');
    $currentLocale = app()->getLocale();
@endphp

@if($multilingual === '1' || $multilingual == '1')
<div class="language-switcher">
    <a href="{{ route('language.switch', ['locale' => 'en']) }}" 
       class="language-link {{ $currentLocale === 'en' ? 'active' : '' }}"
       title="{{ __('cms.english') }}">
        EN
    </a>
    <span class="language-separator">|</span>
    <a href="{{ route('language.switch', ['locale' => 'ar']) }}" 
       class="language-link {{ $currentLocale === 'ar' ? 'active' : '' }}"
       title="{{ __('cms.arabic') }}">
        AR
    </a>
</div>
@endif

