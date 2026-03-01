<?php
// Fichier: reset_to_123.php
// À exécuter UNE SEULE FOIS, puis à SUPPRIMER

require_once __DIR__ . '/app/config/config.php';
require_once __DIR__ . '/app/config/database.php';

$db = Database::getInstance();
$conn = $db->getConnection();

$mon_mdp = "123";
$hash = password_hash($mon_mdp, PASSWORD_DEFAULT);

$stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = 'admin'");
$stmt->bind_param("s", $hash);

if ($stmt->execute()) {
    echo "✅ Succès !<br>";
    echo "Nom d'utilisateur: <strong>admin</strong><br>";
    echo "Nouveau mot de passe: <strong>123</strong><br><br>";
    
    // Vérification
    $check = $conn->query("SELECT password FROM users WHERE username = 'admin'");
    $user = $check->fetch_assoc();
    
    if (password_verify("123", $user['password'])) {
        echo "✅ Le mot de passe fonctionne !<br>";
        echo "<a href='public/index.php?page=login'>Aller à la page de connexion</a>";
    }
} else {
    echo "❌ Erreur: " . $conn->error;
}

// Supprimer ce fichier automatiquement après utilisation (optionnel)
// unlink(__FILE__);
?>