@php($links = \App\Models\SocialLink::footerLinks())
@if($links->isNotEmpty())
    <nav class="store-social" aria-label="{{ __('store.footer.social') }}">
        @foreach($links as $link)
            <a href="{{ $link->safeUrl() }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $link->label() }}">{!! $link->iconSvg() !!}</a>
        @endforeach
    </nav>
@endif
