<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #d9534f, #f5f5f5);
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 40px;
            text-align: center;
            max-width: 400px;
            width: 90%;
        }

        h1 {
            font-size: 2.5rem;
            color: #d9534f;
            margin-bottom: 20px;
        }

        p {
            font-size: 1rem;
            margin-bottom: 30px;
            color: #555;
        }

        .buttons {
            display: flex;
            justify-content: space-between;
        }

        .btn {
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: bold;
            text-align: center;
            transition: background-color 0.3s, color 0.3s;
        }

        .btn-home {
            background: #0275d8;
            color: #fff;
        }

        .btn-home:hover {
            background: #025aa5;
        }

        .btn-login {
            background: #5cb85c;
            color: #fff;
        }

        .btn-login:hover {
            background: #449d44;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Access Denied</h1>
        <p>Sorry, you do not have permission to view this page. Please check your access level or contact the administrator.</p>
        <form action="/redirectDashboard" method="POST">
            <button type="submit" class="btn btn-home">Go to Home</button>
        </form>
    </div>
</body>
</html>
