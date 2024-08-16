<?php
$apiUrl = 'https://product-crud-express.vercel.app/api/products';
$products = json_decode(file_get_contents($apiUrl), true);

if ($products === null) {
    die('Fel vid hämtning av produkter från API.');
}
?>
<!DOCTYPE html>
<html lang="sv">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produkter</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <h1>Produktlista</h1>
    <a href="create.php">Ny Produkt</a>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Namn</th>
                <th>Pris</th>
                <th>Bild</th>
                <th>Åtgärder</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?php echo $product['id']; ?></td>
                    <td><?php echo $product['name']; ?></td>
                    <td><?php echo $product['price']; ?></td>
                    <td><?php echo $product['image']; ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $product['id']; ?>">Redigera</a>
                        <a href="delete.php?id=<?php echo $product['id']; ?>">Radera</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <br />
    <a href="https://product-crud-express.vercel.app/api/export/xml" download>Exportera till XML</a>
    <a href="https://product-crud-express.vercel.app/api/export/csv" download>Exportera till CSV</a>
</body>

</html>