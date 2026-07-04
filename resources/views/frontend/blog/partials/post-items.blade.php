@foreach ($posts as $post)
    <div class="col-md-4 mb-4">
        <div class="blog-card">
            <a href="{{ route('blog.show', $post->slug) }}" class="blog-image-link">
                @if ($post->image)
                    <img src="{{ asset($post->image) }}"
                        alt="{{ $post->image_alt }}">
                @else
                    <img src="{{ asset('img/placeholder.jpg') }}" alt="Placeholder">
                @endif
            </a>
            <div class="blog-content">
                <div class="category-label mb-2">
                    @if ($post->postcategory)
                        <a href="{{ route('blog.category', $post->postcategory->slug) }}">{{ $post->postcategory->name }}</a>
                    @endif
                </div>
                <h3><a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a></h3>
                <p>{{ Str::limit(strip_tags($post->content), 100) }}</p>
            </div>
        </div>
    </div>
@endforeach