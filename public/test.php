<?php
// public/test_connexion.php
require_once '../app/config/database.php';
require_once '../app/config/config.php';

echo "<h1>🔌 TEST DE CONNEXION</h1>";

try {
    $database = new Database();
    $conn = $database->connect();
    
    if ($conn) {
        echo "<p style='color:green'>✅ Connexion à MySQL réussie!</p>";
        
        // Tester la base de données
        $result = $conn->query("SELECT DATABASE() as db");
        $row = $result->fetch_assoc();
        echo "<p>📊 Base de données: <strong>" . $row['db'] . "</strong></p>";
        
        // Compter les tables
        $result = $conn->query("SHOW TABLES");
        echo "<p>📋 Tables trouvées: " . $result->num_rows . "</p>";
        
        // Afficher les tables
        echo "<ul>";
        while($row = $result->fetch_array()) {
            echo "<li>" . $row[0] . "</li>";
        }
        echo "</ul>";
        
        // Tester la table users
        $result = $conn->query("SELECT COUNT(*) as count FROM users");
        $row = $result->fetch_assoc();
        echo "<p>👤 Utilisateurs: " . $row['count'] . "</p>";
        
        if ($row['count'] > 0) {
            echo "<p style='color:green'>✅ Table users OK</p>";
        }
        
        // Tester la table farmers
        $result = $conn->query("SELECT COUNT(*) as count FROM farmers");
        $row = $result->fetch_assoc();
        echo "<p>👨‍🌾 Farmers: " . $row['count'] . "</p>";
        
        // Tester la table orders
        $result = $conn->query("SELECT COUNT(*) as count FROM orders");
        $row = $result->fetch_assoc();
        echo "<p>📦 Commandes: " . $row['count'] . "</p>";
        
    } else {
        echo "<p style='color:red'>❌ Échec de la connexion à MySQL</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color:red'>❌ Erreur: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h2>Prochaines étapes:</h2>";
echo "<ul>";
echo "<li><a href='index.php'>➡️ Aller à l'application</a></li>";
echo "<li><a href='login.php'>➡️ Aller à la page de login</a></li>";
echo "</ul>";
?>