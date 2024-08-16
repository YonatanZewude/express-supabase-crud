<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'name' => $_POST['name'],
        'price' => $_POST['price'],
        'image' => $_POST['image']
    ];
    $options = [
        'http' => [
            'header' => 'Content-type: application/json',
            'method' => 'POST',
            'content' => json_encode($data)
        ]
    ];
    $context = stream_context_create($options);
    $result = file_get_contents('https://product-crud-express.vercel.app/api/products', false, $context);

    if ($result === FALSE) {
        die('Fel vid skapandet av produkten.');
    }

    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="sv">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ny Produkt</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <h1>Ny Produkt</h1>
    <form method="POST" action="">
        <label for="name">Namn:</label>
        <input type="text" name="name" id="name" required><br>
        <label for="price">Pris:</label>
        <input type="text" name="price" id="price" required><br>
        <label for="image">Bild URL:</label>
        <input type="text" name="image" id="image"><br>
        <button type="submit">Lägg till</button>
    </form>
</body>

</html>