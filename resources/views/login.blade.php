<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>

    <section class="login-sec">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="main-login-page">
                        <div class="vnexia-logo">
                            <img src="assets/images/vnexia-white-logo.png" alt="">
                        </div>
                        <div class="lg-fld">
                            <form method="POST" action="{{ route('login') }}" class="login-pg">
                                @csrf
                                <div class="inputss">
                                    <label for="email" style="color:white">Email</label>
                                    <input type="email" name="email" placeholder="email" class="form-control mt-1" value=""
                                        required>
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>
                                <div class="mt-2 psw">
                                    <label for="password" style="color:white">Password</label>
                                    <input type="password" type="password" placeholder="password" name="password" required
                                        class="form-control mt-1" value="" required>
                                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                </div>
                                <button type="submit" class="btn btn-dark mt-3 lg-bt">Login</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


</body>

</html>
