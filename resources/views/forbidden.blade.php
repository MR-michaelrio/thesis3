<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #ff4b2b, #ff416c);
            color: #fff;
        }
        .container {
            text-align: center;
        }
        h1 {
            font-size: 50px;
            margin-bottom: 20px;
        }
        p {
            font-size: 18px;
            margin-bottom: 30px;
        }
        a {
            text-decoration: none;
            color: #ffeb3b;
            font-weight: bold;
            padding: 10px 20px;
            border: 2px solid #ffeb3b;
            border-radius: 5px;
            transition: 0.3s;
        }
        a:hover {
            background-color: #ffeb3b;
            color: #ff416c;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>403 - Forbidden</h1>
        <p>You are not allowed to access this page!</p>
        <a href="{{route('home')}}">Go Back</a>
    </div>
</body>
</html>
