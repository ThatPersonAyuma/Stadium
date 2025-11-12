<!-- <!DOCTYPE html>
<html>

<head>
    <title>Login Page</title>
</head>

<body>
    <h2>Login</h2>

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <p style="color:red;">{{ $error }}</p>
        @endforeach
        
    @endif

    <form method="POST" action="{{ route('checkLogin') }}">
        @csrf
        <label>Email:</label>
        <input type="text" name="email"><br><br>

        <label>Password:</label>
        <input type="password" name="password"><br><br>
        <button type="submit">Login</button>

        {{-- @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach --}}
    </form>
</body>

</html> -->