<?php

require __DIR__ . '/../vendor/autoload.php'; // Charge l'autoloader de Composer

use Symfony\Component\Dotenv\Dotenv;
use Aws\S3\S3Client;

// Charger les variables d'environnement à partir de .env.local
$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/../.env.local'); // Charger le fichier .env.local

//$cacertPath = 'C:/Users/Flory/certificates/cacert.pem'; // Remplacez <VotreNom> par votre nom d'utilisateur.
//if (!file_exists($cacertPath)) {
//    die("Le fichier cacert.pem est introuvable à ce chemin : $cacertPath");
//} else {
//    echo "Le fichier cacert.pem est trouvé et accessible.";
//}
//dd($cacertPath);
//print_r($_ENV);  // Affiche toutes les variables d'environnement définies
//print_r($_SERVER); // Vérifie les variables globales SERVER
//die();

// Vérifier si les variables sont correctement chargées
$region = $_ENV['AWS_REGION'] ?? null;
$key = $_ENV['AWS_ACCESS_KEY_ID']?? null;
$secret = $_ENV['AWS_SECRET_ACCESS_KEY'] ?? null;





if (!$region || !$key || !$secret) {
    die('Les variables AWS_REGION, AWS_ACCESS_KEY_ID ou AWS_SECRET_ACCESS_KEY ne sont pas disponibles.');
}

// Création d'un client S3
$s3 = new S3Client([
    'region' => $region,
    'version' => 'latest',
    'credentials' => [
        'key' => $key,
        'secret' => $secret,
    ],
]);



// Liste des buckets S3
$result = $s3->listBuckets();

echo "Voici vos buckets S3 :\n";
foreach ($result['Buckets'] as $bucket) {
    echo $bucket['Name'] . "\n";
}


