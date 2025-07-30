<?php

class DataLayer
{
    // Propriété privée pour stocker la connexion PDO
    private $connexion;

    // Constructeur pour initialiser la connexion
    public function __construct()
    {
        try {
            // Utilisation des constantes définies dans le fichier config.php
            $this->connexion = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
                DB_USER,
                DB_PASSWORD
            );

            // Configuration des options PDO
            $this->connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  // Activer les exceptions
            $this->connexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);  // Mode tableau associatif

            return NULL;
        } catch (PDOException $e) {
            echo "Erreur de connexion : " . $e->getMessage();
        }
    }

    // FONCTION D'INSCRIPTION CITOYEN

    function userRegister($nom, $email, $telephone, $motdepasse)
    {
        try {
            // Vérification si l'e-mail est déjà utilisé
            $verif = $this->connexion->prepare("SELECT id FROM utilisateur WHERE email = :email");
            $verif->bindParam(':email', $email);
            $verif->execute();

            if ($verif->rowCount() > 0) {
                // L'email existe déjà
                return false;
            }

            // Hachage du mot de passe
            $hash = password_hash($motdepasse, PASSWORD_DEFAULT);

            // Insertion dans la base de données
            $stmt = $this->connexion->prepare("INSERT INTO utilisateur (nom, email, telephone, mot_de_passe) VALUES (:nom, :email, :telephone, :motdepasse)");
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':telephone', $telephone);
            $stmt->bindParam(':motdepasse', $hash);

            $stmt->execute();
            // INSCRIPTION REUSSI
            return true;
        } catch (PDOException $e) {
            // ERREUR D'INSCRIPTION
            return false;
        }
    }

    // FONCTION CONNEXION CITOYEN

    function userLogin($email, $motdepasse)
    {
        try {
            // Rechercher le citoyen par email
            $stmt = $this->connexion->prepare("SELECT * FROM utilisateur WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($motdepasse, $user['mot_de_passe'])) {
                // Connexion réussi
                return $user;
            } else {
                return null;
            }
        } catch (PDOException $e) {
            return null;
        }
    }


    // FONCTION POUR ENVOYER UNE DEMANDE D'ACTE
    function envoyerDemande($data)
    {
        try {
            $sql = "INSERT INTO demande (
            utilisateur_id,
            type_acte_id,
            nombre_copies,
            motif,
            nom_enfant,
            date_heure_naissance,
            nomCompletPere,
            nomCompletMere,
            numero_extrait,
            nomComplet_mariee,
            nomComplet_marie,
            extrait_defunt,
            moyen_paiement,
            numero_depot,
            reference_paiement,
            montant_paye
        ) VALUES (
            :utilisateur_id, :type_acte_id, :nombre_copies, :motif, :nom_enfant, :date_heure_naissance,
            :nomCompletPere, :nomCompletMere, :numero_extrait, :nomComplet_mariee, :nomComplet_marie,
            :extrait_defunt, :moyen_paiement, :numero_depot, :reference_paiement, :montant_paye
        )";

            $stmt = $this->connexion->prepare($sql);
            $execution = $stmt->execute([
                ':utilisateur_id'       => $data['utilisateur_id'],
                ':type_acte_id'         => $data['type_acte_id'],
                ':nombre_copies'        => $data['nombre_copies'],
                ':motif'                => $data['motif'],
                ':nom_enfant'           => $data['nom_enfant'] ?? null,
                ':date_heure_naissance' => $data['date_heure_naissance'] ?? null,
                ':nomCompletPere'       => $data['nomCompletPere'] ?? null,
                ':nomCompletMere'       => $data['nomCompletMere'] ?? null,
                ':numero_extrait'       => $data['numero_extrait'] ?? null,
                ':nomComplet_mariee'       => $data['nomComplet_mariee'] ?? null,
                ':nomComplet_marie'        => $data['nomComplet_marie'] ?? null,
                ':extrait_defunt'       => $data['extrait_defunt'] ?? null,
                ':moyen_paiement'       => $data['moyen_paiement'],
                ':numero_depot'         => $data['numero_depot'],
                ':reference_paiement'   => $data['reference_paiement'],
                ':montant_paye'         => $data['montant_paye']
            ]);

            if ($execution) {
                $lastId = $this->connexion->lastInsertId();
                $stmt2 = $this->connexion->prepare("SELECT * FROM demande WHERE id = :id");
                $stmt2->execute([':id' => $lastId]);
                $demandeActe = $stmt2->fetch(PDO::FETCH_ASSOC);
                return $demandeActe;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log("Erreur lors de l'envoi de la demande : " . $e->getMessage());
            return false;
        }
    }

    // FONCTION POUR RECUPERER LES DEMANDES D'ACTE D'UN UTILISATEUR
    function getDemandesByUtilisateur($utilisateur_id)
    {

        try {
            $stmt = $this->connexion->prepare("
            SELECT d.*, t.libelle AS type_acte
            FROM demande d
            JOIN type_acte t ON d.type_acte_id = t.id
            WHERE d.utilisateur_id = :id
            ORDER BY d.date_demande DESC
        ");
            $stmt->execute([':id' => $utilisateur_id]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur récupération demandes : " . $e->getMessage());
            return [];
        }
    }

    // FONCTION POUR RECUPERER TOUTE LES DEMANDES DE LA BASE DE DONNEES

    function getAllDemandes($filtreType = null, $limit = 10, $offset = 0)
    {
        try {
            $sql = "
            SELECT d.*, u.nom, u.prenom, t.libelle AS type_acte
            FROM demande d
            JOIN utilisateur u ON d.utilisateur_id = u.id
            JOIN type_acte t ON d.type_acte_id = t.id
        ";

            if (!empty($filtreType)) {
                $sql .= " WHERE t.libelle = :type_acte";
            }

            $sql .= " ORDER BY d.date_demande DESC LIMIT :limit OFFSET :offset";

            $stmt = $this->connexion->prepare($sql);
            if (!empty($filtreType)) {
                $stmt->bindParam(':type_acte', $filtreType, PDO::PARAM_STR);
            }
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }


    // FONCTION POUR GERER LA PAGINATION DES DEMANDES A AFFICHER PAR TYPE

    function countDemandes($filtreType = null)
    {
        try {
            $sql = "SELECT COUNT(*) FROM demande d JOIN type_acte t ON d.type_acte_id = t.id";
            if (!empty($filtreType)) {
                $sql .= " WHERE t.libelle = :type_acte";
            }

            $stmt = $this->connexion->prepare($sql);
            if (!empty($filtreType)) {
                $stmt->bindParam(':type_acte', $filtreType, PDO::PARAM_STR);
            }

            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            return 0;
        }
    }

    // FONCTION POUR AVOIR LE NOMBRE DE DEMANDE PAR STATUT
    function countDemandesByStatut($statut)
    {

        try {
            $stmt = $this->connexion->prepare("
            SELECT COUNT(*) AS total 
            FROM demande 
            WHERE statut = :statut
        ");
            $stmt->bindParam(':statut', $statut, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Erreur dans countDemandesByStatut : " . $e->getMessage());
            return 0;
        }
    }

    // FONCTION PERMETTANT DE RECUPERER LES DEMANDES PAR ID

    function getDemandeById($id)
    {
        try {

            // Préparation de la requête
            $stmt = $this->connexion->prepare("SELECT * FROM demande WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            // Exécution de la requête
            $stmt->execute();

            // Récupération des données
            $demande = $stmt->fetch(PDO::FETCH_ASSOC);

            // Retour du résultat ou tableau vide si non trouvé
            return $demande ? $demande : [];
        } catch (PDOException $e) {
            // Gestion d'erreur
            echo "Erreur : " . $e->getMessage();
            return [];
        }
    }

    // FONCTION POUR RECUPERER LES TYPES DE DEMANDE PAR ID

    function getTypeActeById($id)
    {
        try {
            $stmt = $this->connexion->prepare("SELECT libelle FROM type_acte WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                return $row['libelle'];
            } else {
                return null; // ou 'Inconnu'
            }
        } catch (PDOException $e) {
            // Loguer l'erreur si besoin
            return null;
        }
    }

    // FONCTION POUR RECUPERER UN UTILISATEUR EN FONCTION DE SON ID
    function getUserById($id)
    {
        try {
            $sql = "SELECT * FROM utilisateur WHERE id = :id LIMIT 1";
            $stmt = $this->connexion->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC); // Retourne les données utilisateur sous forme de tableau associatif
        } catch (PDOException $e) {
            return null; // En cas d'erreur
        }
    }

    // FONCTION POUR RECUPERER LES ACTES DELIVRES DE L'UTILISATEUR
    function getActesByUtilisateurId($utilisateur_id)
    {
        try {
            $stmt = $this->connexion->prepare("
            SELECT a.*, t.libelle AS type_acte 
            FROM acte a
            JOIN demande d ON d.id = a.demande_id
            JOIN type_acte t ON d.type_acte_id = t.id
            WHERE d.utilisateur_id = :utilisateur_id
            ORDER BY a.date_generation DESC
        ");
            $stmt->bindParam(':utilisateur_id', $utilisateur_id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

// FONCTION POUR VALIDER UNE DEMANDE D'ACTE
function validerDemande($demande_id, $chemin_pdf, $acteur_id)
{
    try {
        // 1. Mettre à jour le statut de la demande
        $stmt = $this->connexion->prepare("UPDATE demande SET statut = 'validée' WHERE id = :id");
        $stmt->bindParam(':id', $demande_id);
        $stmt->execute();

        // 2. Ajouter dans la table acte
        $stmt2 = $this->connexion->prepare("
            INSERT INTO acte (demande_id, chemin_pdf, date_generation) 
            VALUES (:demande_id, :chemin_pdf, NOW())
        ");
        $stmt2->bindParam(':demande_id', $demande_id);
        $stmt2->bindParam(':chemin_pdf', $chemin_pdf);
        $stmt2->execute();

        // 3. Mettre à jour ou insérer dans l'historique
        $action = "Validation de la demande";

        $stmt3 = $this->connexion->prepare("
            UPDATE historique 
            SET action = :action, acteur_id = :acteur_id, date_action = NOW()
            WHERE demande_id = :demande_id
        ");
        $stmt3->bindParam(':demande_id', $demande_id);
        $stmt3->bindParam(':action', $action);
        $stmt3->bindParam(':acteur_id', $acteur_id);
        $stmt3->execute();

        if ($stmt3->rowCount() === 0) {
            // Aucun historique existant, on en crée un
            $stmtInsert = $this->connexion->prepare("
                INSERT INTO historique (demande_id, action, acteur_id, date_action)
                VALUES (:demande_id, :action, :acteur_id, NOW())
            ");
            $stmtInsert->bindParam(':demande_id', $demande_id);
            $stmtInsert->bindParam(':action', $action);
            $stmtInsert->bindParam(':acteur_id', $acteur_id);
            $stmtInsert->execute();
        }

        // 4. Récupérer l'email du citoyen
        $stmt4 = $this->connexion->prepare("
            SELECT email 
            FROM utilisateur u 
            JOIN demande d ON u.id = d.utilisateur_id 
            WHERE d.id = :id
        ");
        $stmt4->bindParam(':id', $demande_id);
        $stmt4->execute();
        $email = $stmt4->fetchColumn();

        // 5. Envoyer l'email de notification
        if ($email) {
            $sujet = "Validation de votre demande d'acte";
            $message = "
                <p>Bonjour,</p>
                <p>Votre demande d’acte a été <strong>validée</strong>.</p>
                <p>Vous pouvez la télécharger via votre espace citoyen.</p>
                <p><a href='https://e-actes.com/profil'>Accéder à mon espace</a></p>
                <br>
                <p>Cordialement,</p>
                <p><strong>L’équipe e-Actes</strong></p>
            ";
            $this->envoyerNotificationEmail($email, $sujet, $message);
        }

        return true;

    } catch (PDOException $e) {
        error_log("Erreur dans validerDemande : " . $e->getMessage());
        return false;
    }
}


// FONCTION POUR REFUSER UNE DEMANDE D'ACTE 
function refuserDemande($demande_id, $acteur_id)
{
    try {
        // 1. Vérifier le statut actuel
        $stmtCheck = $this->connexion->prepare("SELECT statut FROM demande WHERE id = :id");
        $stmtCheck->bindParam(':id', $demande_id);
        $stmtCheck->execute();
        $statut = $stmtCheck->fetchColumn();

        // Si déjà validée, on ne peut pas refuser
        if ($statut === 'validée') {
            return false;
        }

        // 2. Mise à jour du statut
        $stmt = $this->connexion->prepare("UPDATE demande SET statut = 'refusée' WHERE id = :id");
        $stmt->bindParam(':id', $demande_id);
        $stmt->execute();

        // 3. Mise à jour ou insertion dans historique
        $action = "Refus de la demande";

        $stmtHist = $this->connexion->prepare("
            UPDATE historique 
            SET action = :action, acteur_id = :acteur_id, date_action = NOW()
            WHERE demande_id = :demande_id
        ");
        $stmtHist->bindParam(':demande_id', $demande_id);
        $stmtHist->bindParam(':action', $action);
        $stmtHist->bindParam(':acteur_id', $acteur_id);
        $stmtHist->execute();

        if ($stmtHist->rowCount() === 0) {
            // Aucun historique existant, on insère
            $stmtInsert = $this->connexion->prepare("
                INSERT INTO historique (demande_id, action, acteur_id, date_action)
                VALUES (:demande_id, :action, :acteur_id, NOW())
            ");
            $stmtInsert->bindParam(':demande_id', $demande_id);
            $stmtInsert->bindParam(':action', $action);
            $stmtInsert->bindParam(':acteur_id', $acteur_id);
            $stmtInsert->execute();
        }

        // 4. Récupération email du citoyen
        $stmtUser = $this->connexion->prepare("
            SELECT email 
            FROM utilisateur u 
            JOIN demande d ON u.id = d.utilisateur_id 
            WHERE d.id = :id
        ");
        $stmtUser->bindParam(':id', $demande_id);
        $stmtUser->execute();
        $email = $stmtUser->fetchColumn();

        // 5. Envoi email
        if ($email) {
            $sujet = "Refus de votre demande d'acte";
            $message = "
                <p>Bonjour,</p>
                <p>Nous vous informons que votre demande d’acte a été <strong>refusée</strong>.</p>
                <p>Pour plus de détails, veuillez vous connecter à votre espace citoyen.</p>
                <p><a href='https://e-actes.com/profil'>Accéder à mon espace</a></p>
                <br>
                <p>Cordialement,</p>
                <p><strong>L’équipe e-Actes</strong></p>
            ";
            $this->envoyerNotificationEmail($email, $sujet, $message);
        }

        return true;

    } catch (PDOException $e) {
        error_log("Erreur dans refuserDemande : " . $e->getMessage());
        return false;
    }
}


    // FONCTION POUR ENVOYER DES MAIL DE NOTIFICATION AU CITOYEN
    function envoyerNotificationEmail($email, $sujet, $message)
    {
        // En-têtes de l'e-mail
        $headers = "From: no-reply@e-actes.com\r\n";
        $headers .= "Reply-To: support@e-actes.com\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        // Envoi de l'e-mail
        return mail($email, $sujet, $message, $headers);
    }
}
