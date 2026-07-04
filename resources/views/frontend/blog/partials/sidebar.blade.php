<div class="blog-sidebar">
    <!-- Search Box -->
    <div class="sidebar-widget search-widget">
        <h4 class="widget-title">Search</h4>
        <div class="widget-divider"></div>
        <form action="{{ route('blog.search') }}" method="GET" class="search-form">
            <div class="search-input-wrap">
                <input type="text" name="q" placeholder="Search articles..." required>
                <button type="submit" class="search-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                    </svg>
                </button>
            </div>
        </form>
    </div>

    <!-- Categories Widget -->
    <div class="sidebar-widget categories-widget">
        <h4 class="widget-title">Categories</h4>
        <div class="widget-divider"></div>
        <ul class="categories-list">
            @foreach($categories as $category)
                <li>
                    <a href="{{ route('blog.category', $category->slug) }}">
                        {{ $category->name }}
                        <span class="post-count">{{ $category->posts_count }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    <!-- Recent Posts Widget -->
    <div class="sidebar-widget recent-posts-widget">
        <h4 class="widget-title">Recent Posts</h4>
        <div class="widget-divider"></div>
        <ul class="recent-posts-list">
            @foreach($recentPosts as $recentPost)
                <li class="recent-post-item">
                    <div class="post-thumb">
                        <a href="{{ route('blog.show', $recentPost->slug) }}">
                            @if($recentPost->image)
                                <img src="{{ asset($recentPost->image) }}" alt="{{ $recentPost->image_alt ?? $recentPost->title }}">
                            @else
                                <img src="{{ asset('img/placeholder.jpg') }}" alt="Placeholder">
                            @endif
                        </a>
                    </div>
                    <div class="post-info">
                        <h5><a href="{{ route('blog.show', $recentPost->slug) }}">{{ Str::limit($recentPost->title, 40) }}</a></h5>
                        <span class="post-date">{{ $recentPost->created_at->format('M d, Y') }}</span>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>

    <!-- Tags Widget -->
    {{-- <div class="sidebar-widget tags-widget">
        <h4 class="widget-title">Popular Tags</h4>
        <div class="widget-divider"></div>
        <div class="tags-cloud">
            <a href="{{ route('blog.tag', 'travel') }}" class="tag">Travel</a>
            <a href="{{ route('blog.tag', 'accommodation') }}" class="tag">Accommodation</a>
            <a href="{{ route('blog.tag', 'tips') }}" class="tag">Tips</a>
            <a href="{{ route('blog.tag', 'adventure') }}" class="tag">Adventure</a>
            <a href="{{ route('blog.tag', 'food') }}" class="tag">Food</a>
            <a href="{{ route('blog.tag', 'culture') }}" class="tag">Culture</a>
            <a href="{{ route('blog.tag', 'budget') }}" class="tag">Budget</a>
            <a href="{{ route('blog.tag', 'luxury') }}" class="tag">Luxury</a>
        </div>
    </div> --}}
</div>