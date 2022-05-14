<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" value="{{ csrf_token() }}" />
    <title>Vue JS & Laravel</title>
    <link href="{{ mix('css/app.css') }}" type="text/css" rel="stylesheet" />
</head>
<body class="d-flex align-items-center justify-content-center w-100" style="height: 100vh;">

    <main>
        <form onsubmit="return false;">
            <div class="mb-2">
                <input class="form-control text-center" name="email" type="text" placeholder="LOGIN">
            </div>

            <div class="mb-2">
                <input class="form-control text-center" name="password" type="password" placeholder="PASSWORD">
            </div>
            <button type="submit" class="btn btn-dark w-100">LOGIN</button>
        </form>
    </main>

    <script>

        const loginField = document.body.querySelector('[name="email"]');
        const passwordField = document.body.querySelector('[name="password"]');
        document.body.querySelector('button[type="submit"]').addEventListener('click', () => {
            if (!loginField.value.length || !passwordField.value.length) {
                alert('Incorrect login or password!');
            } else {
                const csrf_token = document.querySelector('meta[name="csrf-token"]').getAttribute('value');
                fetch('/auth/login', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf_token,
                        'Content-Type': 'application/json;charset=utf-8'
                    },
                    body: JSON.stringify({
                        email: loginField.value,
                        password: passwordField.value
                    }),
                })
                    .then(response => location.href = '/')
                    .catch(err => alert(err.response.data.message))
            }
        });

    </script>

</body>
</html>