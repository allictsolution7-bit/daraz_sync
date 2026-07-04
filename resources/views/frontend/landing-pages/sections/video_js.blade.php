@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const video = document.getElementById('video-section-iframe');
        if (video) {
            const overlay = document.querySelector('.unmute-overlay');
            if (overlay) {
                overlay.addEventListener('click', function() {
                    if (video && video.contentWindow) {
                        video.contentWindow.postMessage(JSON.stringify({ event: 'command', func: 'unMute', args: [] }), '*');
                        overlay.style.display = 'none';
                    }
                });
            }
        }
    });
</script>
@endpush
