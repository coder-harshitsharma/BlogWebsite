<header>
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('homepictures') }}">InstaBook</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
                aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav mr-auto mb-2 mb-md-0">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="{{ route('user.index') }}">Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('newposts.index') }}">Posts</a>
                    </li>
                </ul>

                <div class="dropdown">
                    <button class="btn btn-outline-success" type="button" id="profilePopover" data-bs-toggle="popover"
                        data-bs-html="true" data-bs-placement="bottom"
                    @if (!auth('admin')->user())
                        data-bs-content='
                        <div class="d-flex flex-column">
                            <a href="{{ route('user.profile') }}" class="btn btn-link">My Profile</a>

                        </div>
                    '>
                    @endif

                        <i class="fa-solid fa-user fa-fade" style="color: #63E6BE;">
                            @if (Auth::check())

                            @elseif(Auth::guard('admin')->check())
                                Admin
                            @endif
                        </i>
                        {{ Auth::check() ? Auth::user()->name : (Auth::guard('admin')->check() ? Auth::guard('admin')->user()->name : '') }}
                        <br>
                        {{ Auth::check() ? Auth::user()->email : (Auth::guard('admin')->check() ? Auth::guard('admin')->user()->email : '') }}

                    </button>
                </div>
                <form id="logoutForm" action="{{ route('logout') }}"style="margin: 0;">
                    @csrf
                    <button type="button" id="logoutButton" class="btn btn-outline-success mx-3 p-3">Logout</button>
                </form>
            </div>
        </div>
    </nav>
</header>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $('#profilePopover').popover({
            trigger: 'click',
            html: true
        });

        $('#logoutButton').on('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to logout!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, logout!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#logoutForm').submit();
                }
            });
        });
    });
</script>
