<?php
$id = $_GET['id'];
$options = [
    'http' => [
        'method' => 'DELETE'
    ]
];
$context = stream_context_create($options);

$result = file_get_contents("https://product-crud-express.vercel.app/api/products/{$id}", false, $context);

if ($result === FALSE) {
    die('Fel vid radering av produkten.');
}

header('Location: index.php');
exit();
?>