@include('Layouts.Header')

<body>

    @include('Layouts.Navbar')

    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            @foreach ($posts->reverse()->take(3) as $post)
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{ $loop->index }}"
                    class="{{ $loop->first ? 'active' : '' }}"></button>
            @endforeach
        </div>
        <div class="carousel-inner">
            @foreach ($posts->reverse()->take(3) as $post)
                <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                    <img src="{{ asset('storage/' . $post->image_path) }}" class="d-block w-100"
                        alt="{{ $post->title }}">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>{{ $post->title }}</h5>
                        @if (strlen($post->content) > 100)
                            {{ substr($post->content, 0, 100) }}<span class="dots">...</span>
                            <a href="{{ route('newposts.show', $post->pid) }}" class="read-more">Read
                                more</a>
                        @else
                            {{ $post->content }}
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"
            data-bs-slide="prev" style="opacity: 0; transition: opacity 0.5s;">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"
            data-bs-slide="next" style="opacity: 0; transition: opacity 0.5s;">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>

        <div class="container mt-5">
            <h1 class="mb-5">Trending Blogs :</h1>
            @foreach ($posts->take(5) as $index => $post)
                <div class="row mb-4 align-items-center {{ $index % 2 == 0 ? 'flex-row' : 'flex-row-reverse' }}">
                    <div class="col-md-4 text-center">
                        <img src="{{ asset('storage/' . $post->image_path) }}" class="rounded-circle"
                            alt="{{ $post->title }}" style="width: 250px; height: 250px; object-fit: cover;">
                    </div>
                    <div class="col-md-8">
                        <h2 class="mb-3">{{ $post->title }}</h2>
                        <p class="mb-3">{{ Str::limit($post->content, 200) }}</p>
                        <a href="{{ route('newposts.show', $post->pid) }}" class="btn btn-outline-primary">Read
                            More</a>
                    </div>
                </div>
                <hr>
            @endforeach
        </div>

        @include('Layouts.Footer')
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
