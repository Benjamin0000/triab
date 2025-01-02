<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Not Found</title>
    <link rel="stylesheet" href="styles.css">
    <style>
body {
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-color: black;
    color: white;
    font-family: Arial, sans-serif;
}

.container {
    text-align: center;
}

.logo {
    width: 100px;
    margin-bottom: 20px;
}

h1 {
    font-size: 50px;
    margin: 0;
}

h2 {
    font-size: 80px;
    margin: 10px 0;
}

p {
    font-size: 24px;
    margin: 10px 0 20px;
}

.home-link {
    text-decoration: none;
    color: white;
    border: 2px solid white;
    padding: 10px 20px;
    border-radius: 5px;
    transition: background-color 0.3s, color 0.3s;
}

.home-link:hover {
    background-color: white;
    color: black;
}

    </style>
</head>
<body>
    <div class="container">
        <img src="/assets/frontpage/img/logo.png" alt="Website Logo" class="logo">
        <h1>Triab</h1>
        <h2>404</h2>
        <p>Page Not Found</p>
        <a href="/" class="home-link">Go to Home Page</a>
    </div>
</body>
</html>