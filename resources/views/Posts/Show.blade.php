<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $post->title }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="{{ asset('css/showblade.css') }}">
</head>

<body>

    @include('Layouts.Navbar')

    <div class="post-banner d-flex align-items-center justify-content-center mt-5"
        style="--background-image: url('{{ Storage::url($post->image_path) }}');">>
        <div class="container text-center">
            <h1 class="display-4">{{ $post->title }}</h1>
        </div>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 post-content">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="post-title mb-0">{{ $post->title }}</h2>
                    <a href="{{ route('newposts.index') }}" class="btn btn-primary">Back to Posts</a>
                </div>
                <p class="text-muted">Posted by {{ $post->user->name }} on {{ $post->created_at->diffForHumans() }}</p>
                <div class="post-body">
                    <p>{{ $post->content }}</p>
                </div>

                <form class="like-form d-inline">
                    @csrf
                    <button type="submit" class="like-btn btn btn-outline-primary">
                        @if ($post->likedByUsers->contains(Auth::user()))
                            <i class="fas fa-thumbs-up"></i> Unlike
                        @else
                            <i class="fas fa-thumbs-up"></i> Like
                        @endif
                    </button>
                </form>

                <p class="likes-count">{{ $post->likedByUsers->count() }} likes</p>
            </div>
        </div>
    </div>

    <div class="container mt-4" id="comments-container">
        @foreach ($post->comments as $comment)
            <div class="comment">
                <div class="comment-box">
                    <img src="{{ Storage::url($comment->user->image_path) }}" alt="User Image" class="profile-picture">
                    <strong class="comment-author">{{ $comment->user->name }}</strong>
                    @if ($comment->user->id == $post->user->id)
                        <span class="badge badge-primary ml-2">Author</span>
                    @endif
                    <span class="text-muted ml-2">{{ $comment->created_at->diffForHumans() }}</span>
                    <button class="btn btn-link reply-button"
                        onclick="toggleReplyForm({{ $comment->id }})">Reply</button>

                    <div id="comment-{{ $comment->id }}">
                        <button class="btn btn-like btn-primary btn-sm mx-2" data-comment-id="{{ $comment->id }}">
                            <span class="like-count">{{ $comment->likedByUsers->count() }}</span> Likes
                        </button>
                    </div>

                    @if (auth('admin')->user() || (auth()->id() === $comment->user_id || auth()->id() === $post->user->id))
                        <button class="btn btn-danger btn-sm delete-comment"
                            data-comment-id="{{ $comment->id }}">Delete</button>
                    @endif
                </div>

                <p>{{ $comment->content }}</p>

                <form action="{{ route('comments.store', $post) }}" method="POST" class="reply-form ajax-reply-form"
                    id="reply-form-{{ $comment->id }}">
                    @csrf
                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                    <textarea name="content" class="form-control" placeholder="Reply to {{ $comment->user->name }}" rows="2"
                        required></textarea>
                    <button type="submit" class="btn btn-primary mt-2">Reply</button>
                </form>


                <div class="comment-thread">
                    @foreach ($comment->replies as $reply)
                        <div class="reply">
                            <div class="comment-box">
                                <img src="{{ Storage::url($reply->user->image_path) }}" alt="User Image"
                                    class="profile-picture">
                                <strong class="comment-author">{{ $reply->user->name }}</strong>
                                <span class="text-muted ml-2">{{ $reply->created_at->diffForHumans() }}</span>

                                <div id="comment-{{ $comment->id }}">
                                    <button class="btn btn-like btn-primary btn-sm mx-2"
                                        data-comment-id="{{ $comment->id }}">
                                        <span class="like-count">{{ $comment->likedByUsers->count() }}</span> Likes
                                    </button>
                                </div>

                                @if (auth('admin')->user() || (auth()->id() === $reply->user_id || auth()->id() === $post->user->id))
                                    <button class="btn btn-danger btn-sm delete-reply mx-2"
                                        data-comment-id="{{ $comment->id }}"
                                        data-reply-id="{{ $reply->id }}">Delete</button>
                                @endif
                            </div>
                            <p>{{ $reply->content }}</p>
                        </div>
                    @endforeach

                </div>
            </div>
        @endforeach
    </div>

    <div class="container mt-5">
        <form id="commentForm" action="{{ route('comments.store', $post) }}" method="POST">
            @csrf
            <textarea name="content" class="form-control" placeholder="Add a comment..." rows="3" required></textarea>
            <button type="submit" class="btn btn-primary mt-2">Add Comment</button>
        </form>
    </div>

    @include('Layouts.Footer')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).on('submit', '.like-form', function(e) {
            e.preventDefault();

            var form = $(this);

            $.ajax({
                type: "POST",
                url: '{{ route('posts.like', $post) }}',
                data: form.serialize(),
                success: function(response) {
                    if (response.liked) {
                        form.find('.like-btn').html('<i class="fas fa-thumbs-up"></i> Unlike');
                    } else {
                        form.find('.like-btn').html('<i class="fas fa-thumbs-up"></i> Like');
                    }
                    form.siblings('.likes-count').text(response.likes_count + ' likes');
                },
                error: function(xhr) {
                    console.error('Error:', xhr.responseText);
                }
            });
        });

        function toggleReplyForm(commentId) {
            let replyForm = document.getElementById(`reply-form-${commentId}`);
            $(replyForm).slideToggle();
        }

        $('#commentForm').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);

            $.ajax({
                type: "POST",
                url: form.attr('action'),
                data: form.serialize(),
                success: function(response) {
                    if (response) {
                        $('#comments-container').append(`
                    <div class="comment">
                        <div class="comment-box">
                            <img src="${response.user_image}" alt="User Image" class="profile-picture">
                            <strong class="comment-author">${response.user_name}</strong>
                            <span class="text-muted ml-2">${response.time_ago}</span>
                            <button class="btn btn-link reply-button"
                                onclick="toggleReplyForm(${response.comment_id})">Reply</button>
                            <div id="comment-${response.comment_id}">
                                <button class="btn btn-like btn-primary btn-sm mx-2" data-comment-id="${response.comment_id}">
                                    <span class="like-count">0</span> Likes
                                </button>
                            </div>

                            ${response.is_auth ? `
                                <button class="btn btn-danger btn-sm delete-comment" data-comment-id="${response.comment_id}">
                                    Delete
                                </button>
                                ` : ''}
                        </div>
                        <p>${response.content}</p>
                    </div>
                `);
                    } else {
                        console.error('Error in data');
                    }

                    form.find('textarea[name="content"]').val('');
                },
                error: function(xhr) {
                    console.error('Error:', xhr.responseText);
                }
            });
        });


        $(document).on('submit', '.ajax-reply-form', function(e) {
            e.preventDefault();

            var form = $(this);
            var commentId = form.find('input[name="parent_id"]').val();

            $.ajax({
                type: "POST",
                url: form.attr('action'),
                data: form.serialize(),
                success: function(response) {
                    if (response) {
                        $(`#reply-form-${commentId}`).before(`
                    <div class="reply">
                        <div class="comment-box">
                            <img src="${response.user_image}" alt="User Image" class="profile-picture">
                            <strong class="comment-author">${response.user_name}</strong>
                            <span class="text-muted ml-2">${response.time_ago}</span>
                        </div>
                        <p>${response.content}</p>
                    </div>
                `);
                        form.find('textarea').val('');
                        form.slideUp();
                    }
                },
                error: function(xhr) {
                    console.error('Error:', xhr.responseText);
                }
            });
        });


        $(document).on('click', '.delete-comment', function(e) {
            e.preventDefault();

            var commentId = $(this).data('comment-id');
            var url = `/posts/{{ $post->pid }}/comments/${commentId}`;

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "DELETE",
                        url: url,
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire(
                                'Deleted!',
                                response.message,
                                'success'
                            );
                            $(`button[data-comment-id="${commentId}"]`).closest('.comment')
                                .remove();
                        },
                        error: function(xhr) {
                            console.error('Error:', xhr.responseText);
                            Swal.fire(
                                'Error!',
                                'There was a problem deleting the comment.',
                                'error'
                            );
                        }
                    });
                }
            });
        });

        $(document).on('click', '.delete-reply', function(e) {
            e.preventDefault();

            var commentId = $(this).data('comment-id');
            var replyId = $(this).data('reply-id');
            var url = `/posts/{{ $post->pid }}/comments/${commentId}/reply/${replyId}`;

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "DELETE",
                        url: url,
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire(
                                'Deleted!',
                                response.message,
                                'success'
                            );
                            $(`button[data-reply-id="${replyId}"]`).closest('.reply').remove();
                        },
                        error: function(xhr) {
                            console.error('Error:', xhr.responseText);
                            Swal.fire(
                                'Error!',
                                'There was a problem deleting the reply.',
                                'error'
                            );
                        }
                    });
                }
            });
        });

        $(document).on('click', '.btn-like', function() {
            var commentId = $(this).data('comment-id');
            var likeBtn = $(this);

            $.ajax({
                url: '/comments/' + commentId + '/like',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        likeBtn.find('.like-count').text(response.likes_count);
                        likeBtn.toggleClass('liked', response.liked);
                    }
                }
            });
        });
    </script>
</body>

</html>
