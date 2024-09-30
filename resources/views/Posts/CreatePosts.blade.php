<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to InstaBook</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-div {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            color: #333;
        }

        input[type="text"],
        textarea,
        input[type="file"],select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 15px;
            box-sizing: border-box;
        }

        textarea {
            height: 100px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #4c90af;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #456ca0;
        }

        .login-link {
            font-weight: bold;
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <div class="form-div">
        <h1>Create new Posts</h1>
        <form enctype="multipart/form-data" id="createpost">
            @csrf
            @if (isset($post))
                @method('put')
            @endif
            <label for="title">Title</label>
            <input type="text" name="title" id="title" value="{{ $post->title ?? '' }}">

            <label for="content">Content</label>
            <input type="text" name="content" id="content" value="{{ $post->content ?? '' }}"></input>

            @isset($tags)
            <label for="tags">Tags</label>
            <select name="tags" id="tags">
                @foreach ($tags as $tag)
                    <option value="{{ $tag->id ?? ''}}">{{ $tag->name }}</option>
                @endforeach
            </select>
            @endisset

            <label for="image">Image</label>
            <input type="file" name="image" id="image" value="{{ $post->image_path ?? '' }}">

            <input type="hidden" name="user_id" id="user_id" value="{{ Auth::id() }}" required>
            <button type="submit" class="login-link">Create Post</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script>
        $(document).ready(function() {
            $('#createpost').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);

                var csrfToken = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: "{{ isset($post) ? route('newposts.update', $post->pid) : route('newposts.store') }}",
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(data) {
                        if (data.status) {
                            Swal.fire({
                                title: 'Success!',
                                text: data.message,
                                icon: 'success',
                                showConfirmButton: true
                            }).then(() => {
                                window.location.href = data.redirect;
                            });
                        };
                    },
                    error: function(xhr) {
                        var response = JSON.parse(xhr.responseText);
                        Swal.fire({
                            title: 'Error!',
                            text: response.message,
                            // 'Request Status: ' + xhr.status + ' Status Text: ' +
                            //     xhr.statusText + ' ' +
                            icon: 'error',
                        });
                    }
                });
            });
        });
    </script>

</body>

</html>
