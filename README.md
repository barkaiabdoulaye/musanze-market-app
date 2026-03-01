# Musanze Market Order System

## 📋 Description
Application full-stack pour la gestion des commandes de pommes de terre à Musanze.
Développé pour les agrégateurs et points de collecte.

## 🚀 Fonctionnalités
- Authentification admin
- Gestion des fermiers (CRUD)
- Gestion des commandes avec calcul automatique
- Génération de reçus imprimables
- Dashboard avec statistiques
- Recherche en temps réel
- Interface responsive

## 🛠️ Technologies
- **Frontend:** HTML5, CSS3, JavaScript (Vanilla)
- **Backend:** PHP 7.4+ (MySQLi)
- **Base de données:** MySQL
- **Version Control:** Git/GitHub
- **Hébergement:** InfinityFree

## 📦 Installation

### Prérequis
- PHP 7.4+
- MySQL 5.7+
- Serveur web (Apache/Nginx)
- Git

### Étapes
```bash
# Cloner le projet
git clone https://github.com/votre-username/musanze-market-app.git
cd musanze-market-app

# Configurer la base de données
mysql -u root -p < database/schema.sql
mysql -u root -p < database/seed.sql

# Configurer l'application
cp app/config/config.php.sample app/config/config.php
# Éditez app/config/config.php avec vos identifiants

# Lancer l'application
# Pointez votre serveur web vers le dossier /public