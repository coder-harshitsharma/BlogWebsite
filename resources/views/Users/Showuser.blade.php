<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $user->name }}'s Posts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLR1Q1xx2zX+rENmF6PfzmLCQP9FNmBmkLdH5HcWti" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f7f7f7;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin-top: 5rem;
        }

        .post-card {
            border: none;
            border-radius: 15px;
            background-color: #fff;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .post-card img {
            border-radius: 15px 15px 0 0;
            max-height: 400px;
            object-fit: cover;
        }

        .post-card-body {
            padding: 20px;
        }

        .post-title {
            font-size: 1.6rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .post-content {
            font-size: 1rem;
            color: #555;
            margin-bottom: 10px;
        }

        .post-date {
            font-size: 0.9rem;
            color: #aaa;
        }

        .profile-img {
            border-radius: 50%;
            width: 50px;
            height: 50px;
            object-fit: cover;
            margin-right: 10px;
        }

        .post-header {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .post-header h2 {
            margin: 0;
        }

        .post-header p {
            margin: 0;
            font-size: 0.9rem;
            color: #aaa;
        }

        .like-button {
            cursor: pointer;
            color: #ff0000;
        }

        .like-button:hover {
            color: #cc0000;
        }

        footer {
            background-color: #343a40;
            color: white;
            padding: 20px 0;
        }

        footer a {
            color: #fff;
        }

        footer a:hover {
            color: #ddd;
        }
    </style>
</head>

<body>
    @include('Layouts.Navbar')
    <div class="container">
        <h1 class="text-center mb-4">{{ $user->name }}'s Posts</h1>

        @forelse($user->posts as $post)
            <div class="post-card">
                @if ($post->image_path)
                    <img src="{{ asset('storage/' . $post->image_path) }}" alt="Post Image" class="img-fluid">
                @endif
                <div class="post-card-body">
                    <div class="post-header">
                        <div>
                            <h2 class="post-title">{{ $post->title }}</h2>
                            <p class="post-date"><strong>Published on:</strong> {{ \Carbon\Carbon::parse($post->created_at)->format('F d, Y') }}</p>
                        </div>
                    </div>
                    <p class="post-content">{{ $post->content }}</p>
                </div>
            </div>
        @empty
            <h1 class="text-center" style="margin-top: 200px; margin-bottom:250px;">This user has no posts.</h1>
        @endforelse
    </div>

    @include('Layouts.Footer')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
