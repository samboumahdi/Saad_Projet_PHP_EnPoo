<?php
// Classe pour gérer la session utilisateur
class SessionManager {
    // Méthode pour démarrer la session
    public static function startSession() {
        session_start();
    }

    // Méthode pour détruire la session
    public static function destroySession() {
        $_SESSION = array(); // Vider le tableau de session pour supprimer toutes les données de session
        session_destroy(); // Détruire la session actuelle
    }
}

// Démarrer la session
SessionManager::startSession();

// Détruire la session
SessionManager::destroySession();

// Rediriger vers la page de connexion
header("location: login.php");
?>
