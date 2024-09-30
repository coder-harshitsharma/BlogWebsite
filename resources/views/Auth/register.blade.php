<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .register-container {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        .register-container h1 {
            margin-bottom: 1rem;
            font-size: 1.5rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
        }

        .form-group input {
            margin-left: -10px;
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .form-group button {
            width: 100%;
            padding: 0.75rem;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
        }

        .form-group button:hover {
            background-color: #0056b3;
        }

        .login-link {
            margin-top: 1rem;
            text-align: center;
        }

        .login-link a {
            color: #007bff;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <div class="register-container">
        <h1>{{ isset($user) ? 'Edit Profile' : 'Register' }}</h1>
        <form enctype="multipart/form-data" id="registeruser">
            @csrf
            @if (isset($user))
                @method('put')
            @endif
            <div class="form-group">
                <label for="name">Name</label>
                <input id="name" type="text" name="name" value="{{Auth::user()->name ?? ''}}">
            </div>

            <div class="form-group">
                <label for="image">Profile Image</label>
                <input type="file" name="image" id="image" value="{{Auth::user()->image_path ?? ''}}">
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input id="email" type="email" name="email" value="{{Auth::user()->email ?? ''}}">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" value="{{Auth::user()->password ?? ''}}">
            </div>
            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" value="{{Auth::user()->password ?? ''}}">
            </div>

            <div class="form-group">
                <button type="submit">{{ isset($user) ? 'Edit' : 'Register' }}</button>
            </div>
        </form>
        @if (!isset($user))
        <div class="login-link">
            <p>Already have an account? <a href="{{ route('login') }}">Login here</a></p>
        </div>
        @endif

    </div>

    {{-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script> --}}
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        $(document).ready(function() {
            $('#registeruser').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                var csrfToken = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: "{{ isset($user) ? route('user.update',Auth::id()) : route('user.store') }}",
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
                            });;
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
