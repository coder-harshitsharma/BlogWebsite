@include('Layouts.Header')

<body>
    @include('Layouts.Navbar')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <img src="{{ asset('storage/' . Auth::user()->image_path) }}" alt="Profile Picture" class="rounded-circle mt-5" style="width: 150px; height: 150px; object-fit: cover; border: 2px solid #63E6BE;">
                        <h2 class="mt-3">{{ Auth::user()->name }}</h2>
                        <p class="text-muted">{{ Auth::user()->email }}</p>
                        <p class="text-muted">✨ Passionate about singing & dance
                            🎤 Sharing my musical journey
                            💃 Dance enthusiast
                            📸 Capturing moments in style
                            👇 Check out my latest posts!</p>
                        <div class="mt-4">
                            <a href="{{ route('user.edit', Auth::user()->id) }}" class="btn btn-outline-primary">Edit Profile</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('Layouts.Footer')
</body>
</html>
