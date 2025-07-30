<?php
// Démarrage de la session

session_start();

// IMPORTATION DU FICHIER INCLUDE.PHP

require "source/includes.php";

// GESTION DE L'URL

$url = trim($_SERVER['REQUEST_URI'],"/");
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode("/", $url);


// GESTION DES REQUETES

$action = "accueil";

if(isset($url[1])){
    $action = $url[1];
}


// LES ROUTES DU SITE

$routes = ["accueil","register","login","contact","blog","faq","profil","dashboard_agent","dashboard_admin","actionIncription","actionConnexion","actionDemandeActe","actionLogoutUser","detailsdemande","actionGenererPDFDemande","actionValiderDemande","actionRefuserDemande"];

// LE CONCTROLLER

if(isset($action) && in_array($action, $routes)){
    $title = ucfirst($action);
    $function = "display" . ucwords($action);
    $content = $function();
    require TEMPLATES.SP."partials.php";

}
else{
    $title = "ERROR";
    $fonction = "displayError";
    $content = $fonction();
    require TEMPLATES.SP."erreur.php";

}