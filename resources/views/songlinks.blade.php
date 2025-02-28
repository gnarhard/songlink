<div style="background: url({{ asset($song->album_artwork_path) }}); background-size: cover; background-position: center;" class="container-fluid">
    <div class="row" style="background-color: rgba(0,0,0,.7);">
        <div class="col col-md-8 mx-auto py-4" style="max-width: 600px;">
            <h6 class="text-center mb-1 d-block">LISTEN TO</h6>
            <h2 class="text-center text-uppercase mb-0">{{ $song->title }}</h2>
            <h4 class="text-center mb-4">{{ $artist ?? '' }}</h4>
            <ul class="list-unstyled mx-auto mt-4 text-center">
                @foreach ($song->platform_urls as $platform => $url)
                    <li class="mb-1">
                        <a href="{{ $url }}" id="{{ Gnarhard\SongLink\Facades\SongLink::getPlatformClickId($platform) }}" data-conversion-value="{{ Gnarhard\SongLink\Facades\SongLink::getPlatformConversionValue($platform) }}" class="btn-primary btn position-relative listen_link" target="_blank" aria-label="{{ $platform }}" style="min-width: 180px;">
                            <i class="fa-1x position-absolute {{ Gnarhard\SongLink\Facades\SongLink::getPlatformIcon($platform) }}" style="left: 12px; top: 12px;"></i>
                            <span class="ms-1 btn-icon-primary__link">{{ $platform }}</span>
                        </a>
                    </li>
                @endforeach
                @if (route('mailing-list'))
                    <li class="mt-3 mb-1">
                        <a href="{{ route('mailing-list') }}" class="btn-primary btn position-relative" aria-label="Share" style="min-width: 200px;">
                            <i class="fa fa-1x fa-envelope position-absolute" style="left: 12px; top: 12px;"></i>
                            <span class="ms-1 btn-icon-primary__link">Join Mailing List</span>
                        </a>
                    </li>
                @endif
                <li class="">
                    <button id="share_listen_page" onclick="window.copyLinkToClipboard()" class="btn-primary btn position-relative" target="_blank" aria-label="Share" style="min-width: 200px;">
                        <i class="fa fa-1x fa-share position-absolute" style="left: 12px; top: 12px;"></i>
                        <span class="ms-1 btn-icon-primary__link">Share</span>
                    </button>
                </li>
            </ul>

            <h3 class="text-center">LATEST VIDEO</h3>
            @empty(!$song->youtube_video_id)
                @include('components.embedded_video', ['videoId' => $song->youtube_video_id])
            @endempty
            <div class="link_copied bg-success text-center p-3 rounded d-none">LINK COPIED TO CLIPBOARD</div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        window.copyLinkToClipboard = function() {
            const el = document.createElement('textarea');
            el.value = "{{ route('listen') }}";
            document.body.appendChild(el);
            el.select();
            document.execCommand('copy');
            document.body.removeChild(el);
            document.querySelector('.link_copied').classList.remove('d-none');
            setTimeout(() => {
                document.querySelector('.link_copied').classList.add('d-none');
            }, 3000);
        }
    </script>
@endpush
