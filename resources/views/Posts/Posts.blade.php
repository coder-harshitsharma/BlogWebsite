<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>InstaBook</title>

    <!-- Bootstrap Core CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />


    <style>
        .album-section {
            padding: 50px 0;
            background-color: #f7f7f7;
        }

        .album-card {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease-in-out;
        }

        .album-card:hover {
            transform: scale(1.05);
        }

        .card-body {
            text-align: center;
        }

        .profile-img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 10px;
        }


        .mb-3,
        .my-3 {
            margin: 1rem !important;
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

    <div class="album-section mt-5">
        <div class="container">
            <div class="row mb-3 align-items-center">
                <div>
                    <select id="tagFilter" class="form-control">
                        <option value="">Select a tag</option>
                        @foreach ($tags as $tag)
                            <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="margin-left: 800px">
                    <a href="{{ route('newposts.create') }}" class="btn btn-primary btn-md">Add new Post</a>
                </div>
            </div>

            <div class="row">
                @foreach ($posts as $post)
                    <div class="col-md-4 mt-4 post-item"
                        data-tags="{{ implode(',', $post->tags->pluck('id')->toArray()) }}">
                        <div class="card album-card h-5">
                            <div class="d-flex align-items-center mb-3">
                                <img src="{{ Storage::url($post->user->image_path) }}" alt="User Image"
                                    class="profile-img">
                                <a class="font-weight-bold" href="{{ route('user.show', $post->user->id) }}" style="color: #343a40; text-decoration:none;">{{ $post->user->name }}</a>
                            </div>
                            <img src="{{ Storage::url($post->image_path) }}" class="card-img-top" style="height: 250px"
                                alt="Album Thumbnail">
                            <div class="card-body">
                                <h5 class="card-title">{{ $post->title }}</h5>
                                <p class="card-text" style="height: 60px">
                                    @if (strlen($post->content) > 100)
                                        {{ substr($post->content, 0, 100) }}<span class="dots">...</span>

                                        <a href="{{ route('newposts.show', $post->pid) }}" class="read-more">Read
                                            more</a>
                                    @else
                                        {{ $post->content }}
                                    @endif
                                </p>
                                <a href="{{ route('newposts.show', $post->pid) }}" class="btn btn-info">View</a>
                                @if (auth('admin')->check() || (auth('web')->user()->id == $post->user_id))
                                    <a href="{{ route('newposts.edit', $post->pid) }}"
                                        class="btn btn-warning">Update</a>
                                    <form action="{{ route('newposts.destroy', $post->pid) }}" method="post"
                                        class="d-inline" id="delete-form-{{ $post->pid }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger"
                                            onclick="confirmDelete({{ $post->pid }})">Delete</button>
                                    </form>
                                @endif




                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>


    @include('Layouts.Footer')

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        // $(document).ready(function() {
        //     $('#tagFilter').on('change', function() {
        //         var tagId = $(this).val();

        //         $.ajax({
        //             url: "{{ route('posts.filterByTag') }}",
        //             method: 'GET',
        //             data: {
        //                 tag_id: tagId
        //             },
        //             success: function(data) {
        //                 $('body').html(data.html);
        //             },
        //             error: function(xhr) {
        //                 console.log('Error:', xhr);
        //             }
        //         });
        //     });
        // });

        $(document).ready(function() {
            $('#tagFilter').on('change', function() {
                var selectedTag = $(this).val();

                if (selectedTag) {
                    $('.post-item').each(function() {
                        var postTags = $(this).data('tags').toString().split(',');
                        postTags.includes(selectedTag) ? $(this).show() : $(this).hide();
                    });
                } else {
                    $('.post-item').show();
                }
            });
        });

        function confirmDelete(postId) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: true
            });

            swalWithBootstrapButtons.fire({
                title: 'Are you sure?',
                text: 'You won\'t be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: $('#delete-form-' + postId).attr('action'),
                        type: 'POST',
                        data: $('#delete-form-' + postId).serialize(),
                        success: function(response) {
                            if (response.status === true) {
                                swalWithBootstrapButtons.fire(
                                    'Deleted!',
                                    response.message,
                                    'success'
                                ).then(() => {
                                    window.location.href = response.redirect;
                                });
                            }
                        },
                        error: function(xhr) {
                            swalWithBootstrapButtons.fire(
                                'Error',
                                'Something went wrong!',
                                'error'
                            );
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire(
                        'Cancelled',
                        'Your post is safe :)',
                        'error'
                    );
                }
            });
        }
    </script>
</body>

</html>
