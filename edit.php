<?php
$id = $_GET['id'];
$product = json_decode(file_get_contents("https://product-crud-express.vercel.app/api/products/{$id}"), true);

if ($product === null) {
    die('Fel vid hämtning av produkten från API.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'name' => $_POST['name'],
        'price' => $_POST['price'],
        'image' => $_POST['image']
    ];
    $options = [
        'http' => [
            'header' => 'Content-type: application/json',
            'method' => 'PUT',
            'content' => json_encode($data)
        ]
    ];
    $context = stream_context_create($options);
    $result = file_get_contents("https://product-crud-express.vercel.app/api/products/{$id}", false, $context);

    if ($result === FALSE) {
        die('Fel vid uppdatering av produkten.');
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
    <title>Redigera Produkt</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <h1>Redigera Produkt</h1>
    <form method="POST" action="">
        <label for="name">Namn:</label>
        <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($product['name']); ?>" required><br>
        <label for="price">Pris:</label>
        <input type="text" name="price" id="price" value="<?php echo htmlspecialchars($product['price']); ?>"
            required><br>
        <label for="image">Bild URL:</label>
        <input type="text" name="image" id="image" value="<?php echo htmlspecialchars($product['image']); ?>"><br>
        <button type="submit">Spara</button>
    </form>
</body>

</html>