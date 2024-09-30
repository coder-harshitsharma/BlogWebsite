<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLR1Q1xx2zX+rENmF6PfzmLCQP9FNmBmkLdH5HcWti" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: "Poppins", sans-serif;
            background-color: #f7f8fa;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            /* display: flex;  */
        }

        h1 {
            color: #333;
            font-weight: 600;
            margin-bottom: 40px;
        }

        .user-card {
            margin: 5px;
            width: 305px;
            background-color: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .user-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
        }

        .profile {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-evenly;
        }

        .profile-picture {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #ddd;
            margin-bottom: 15px;
        }

        .user-info {
            font-size: 18px;
            font-weight: 500;
            color: #333;
        }

        .user-email {
            font-size: 14px;
            color: #888;
            margin-top: 5px;
        }

        .btn-posts {
            background-color: #007bff;
            color: white;
            border: none;
            font-weight: 500;
            border-radius: 50px;
            padding: 10px 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        .btn-posts:hover {
            background-color: #0056b3;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Responsive adjustments */
        @media screen and (max-width: 768px) {
            .profile-picture {
                width: 90px;
                height: 90px;
            }

            .user-info {
                font-size: 16px;
            }

            .user-email {
                font-size: 12px;
            }
        }

        @media screen and (max-width: 576px) {
            .profile-picture {
                width: 80px;
                height: 80px;
            }

            .user-info {
                font-size: 14px;
            }

            .user-email {
                font-size: 10px;
            }
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
    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="mt-5">Discover People</h1>
            <a href="{{ route('homepictures') }}" class="btn btn-primary">Back to Homepage</a>
        </div>
    </div>

    <div class="row profile mx-5">
        @foreach ($users as $user)
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="user-card">
                    <img src="{{ Storage::url($user->image_path)}}" alt="User Image"
                        class="profile-picture">
                    <h5 class="user-info">{{ $user->name }}</h5>
                    <p class="user-email">{{ $user->email }}</p>
                    <a href="{{ route('user.show', $user->id) }}" class="btn btn-posts mt-3">View Posts</a>
                </div>
            </div>
        @endforeach
    </div>

    @include('Layouts.Footer')
    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ho+ZXoG29cEY3pF2S62VCDMe58ZgcrLZmD9C+D8J/59e3SRgaoevKfdSvo3whGRc" crossorigin="anonymous">
    </script>
</body>

</html>
