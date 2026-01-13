<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Laravel Chat</title>
    <link rel="stylesheet" href="{{ asset('scripts/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('scripts/custom.css') }}">

</head>

<body>


    <div class="container">

        <div class="row">
            <div class="col-sm-2"></div>
            <div class="col-sm-8">

                <div class="login-box">
                    <h3 class="text-center mb-4 fw-semibold">Login</h3>

                    <form method="POST" action="{{ route('dologin') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>
                </div>
            </div>
            <div class="col-sm-2"></div>
        </div>
    </div>


    <script src="{{ asset('scripts/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('scripts/custom.js') }}"></script>

</body>

</html>