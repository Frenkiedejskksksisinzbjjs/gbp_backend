<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accès Interdit</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-image: url('https://via.placeholder.com/1600x900'); /* Image de fond */
            background-size: cover;
            background-position: center;
        }
        .container {
            text-align: center;
            background-color: rgba(0, 0, 0, 0.6);
            padding: 50px;
            border-radius: 10px;
            color: white;
        }
        h1 {
            font-size: 3rem;
            margin-bottom: 20px;
        }
        p {
            font-size: 1.25rem;
            margin-bottom: 30px;
        }
        .icon {
            font-size: 5rem;
            margin-bottom: 20px;
        }
        .button {
            padding: 10px 20px;
            background-color: #ff4747;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-transform: uppercase;
            font-size: 1rem;
        }
        .button:hover {
            background-color: #ff2f2f;
        }
    </style>
</head>
<body>
    <div class="container">
        <i class="fas fa-ban icon"></i>
        <h1>Accès Interdit</h1>
        <p>Vous n'êtes pas autorisé à accéder à cette page.</p>
        <button class="button" onclick="window.location.href='https://www.example.com';">Retour à la page d'accueil</button>
    </div>
</body>
</html>
