<?php

namespace src\model; // Je renomme la classe en src\model\Database, ça sert à éviter les conflits en cas d'importation d'une librairie qui utilise aussi "new Database" par exemple
use PDO; // Importation de la classe "PDO" de Twig

class Database {

    // Déclaration des propriétés qui vont constituer la connexion à la base de données
    private $pdo;
    private $host = 'localhost';
    private $db_name = 'new_ventes_db';
    private $user = 'root';
    private $password = '';

    public function __construct()
    {
        // Instance mise à la ligne pour qu'elle soit plus lisible
        $this->pdo = new PDO(
            "mysql:host=$this->host;dbname=$this->db_name",
            $this->user,
            $this->password
        );
    }

    // Raccourci pour avoir ce type de commentaire : / + ** + Entrée
    /**
     * Retourne toutes les lignes (enregistrements) de la table
     *
     * @param string $table : Nom de la table
     * @return array : Cette fonction retourne un tableau
     */
    public function findAll($table)
    {
        // Revoir à quoi sert cette requête et pourquoi je la déclare comme ça
        return $this->pdo->query("SELECT * FROM $table")->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère une ligne (enregistrement) de la table
     *
     * @param int $id // Identifiant de la table
     * @param string $table // Nom de la table
     * @return mixed // Cette fonction retourne n'importe quel type de données : ça dépend du type de données
     */
    public function find($id, $table)
    {
        // Revoir à quoi servent ces requêtes et pourquoi je les déclares comme ça
        $statement = $this->pdo->prepare("SELECT * FROM $table WHERE id = :id");
        $statement->execute(["id" => $id]);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    // Cette classe me servira pour les requêtes d'insertion de données dans les tables (INSERT INTO)
    public function save($data, $table) {}

    // Cette classe me servira pour les requêtes de mise à jour de données dans les tables (UPDATE)
    public function update($id, $data, $table) {}

    // Cette classe me servira pour les requêtes de suppression de données dans les tables (DELETE)
    public function delete($id, $data, $table) {}
}