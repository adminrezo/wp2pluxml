<?php
/**
 * Configuration de wp2pluxml
 *
 * @category   wp2pluxml
 * @author     Nicolas Loeuillet <nicolas.loeuillet@gmail.com>
 * @copyright  2010
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 */

error_reporting(0);
# Configuration avancée #
define('PLX_ROOT', '../');
define('PLX_CORE', PLX_ROOT.'core/');
define('PLX_CONF', PLX_ROOT.'data/configuration/parametres.xml');

# On vérifie que PluXml n'est pas déjà installé
if(!file_exists(PLX_CONF)) {
    header('Content-Type: text/plain charset=UTF-8');
    echo 'PluXml n\'est pas encore installé !';
    exit;
}

# On inclut les librairies nécessaires
include_once PLX_ROOT.'config.php';
include_once PLX_CORE.'lib/class.plx.utils.php';

# Echappement des caractères
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_POST = plxUtils::unSlash($_POST);
}

# Configuration de base
$config = array(
    'url_blog'            => 'http://localhost/wordpress', // sans le slash final
    'racine'              => plxUtils::getRacine(),
    'allow_com'           => 1,
    'bypage'              => 5,
    'tri'                 => 'desc',
    'menu'                => 'oui',
    'author'              => 'admin',
    'documents'           => PLX_ROOT.'data/documents/',
    'racine_articles'     => PLX_ROOT.'data/articles/',
    'racine_commentaires' => PLX_ROOT.'data/commentaires/',
    'categories'          => PLX_ROOT.'data/configuration/categories.xml',
    'has_shp_plugin'      => TRUE, // Si le plugin SyntaxHighlighterPlus était actif
);

include_once 'lib/SimpleXMLExtend.class.php';
include_once 'lib/Wordpress.class.php';
include_once 'lib/Tools.class.php';
include_once 'lib/oolog.class.php';

if(!empty($_POST['file'])) {
    if (!Tools::convert2pluxml($_POST, $config)) {
        die('Une erreur a &eacute;t&eacute; rencontr&eacute;e durant la conversion. Consultez les logs.');
    }
    header('Location: '.PLX_ROOT.'index.php');
    exit;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
<title>wp2pluxml - Configuration</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo strtolower(PLX_CHARSET) ?>" />
<link rel="stylesheet" type="text/css" href="../core/admin/admin.css" media="screen" />
</head>

<body>

<div id="main">
    <div id="header">
        <br />
        <h1>wp2pluxml</h1>
        <br />
    </div>
    <div id="content">
        <form action="index.php" method="post">
        <fieldset>
            <h2>Présentation</h2>
            <p>
                <a href="http://code.google.com/p/wp2pluxml/">wp2pluxml</a> vous permet de convertir le contenu de <strong>votre blog Wordpress en un blog PluXml</strong>.<br />
                <b>Attention</b>, ce script est à exécuter en local et non en environnement de production
                (selon la taille de votre blog Wordpress, la génération peut prendre du temps).
            </p>
            <p>
                Quelques liens utiles :
                <ul>
                    <li><a href="http://code.google.com/p/wp2pluxml/issues/list">le gestionnaire de bugs de wp2pluxml</a>, pour nous remonter un bug si jamais vous en croisez un</li>
                    <li><a href="http://code.google.com/p/wp2pluxml/downloads/list">ai-je la dernière version de wp2pluxml ?</a> Ou comment télécharger la dernière version de l'outil</li>
                </ul>
            </p>
            <p>
                Assez de bla bla, passons maintenant à <b>la conversion de votre blog</b> !
            </p>
            <h2>Pré-requis</h2>
            <p>
                Avant toute chose, sachez que ce script a besoin de PHP 5 pour fonctionner (en effet, nous utilisons <a href="http://fr.php.net/manual/fr/book.simplexml.php">l'extension SimpleXML</a>).<br />
                Ce script est compatible à partir de PluXml 4.3.1. Je n'ai pas testé avec des versions antérieures, impossible donc de dire s'il est compatible avec ces versions.
            </p>
            <p>Avant de faire quoique ce soit, ouvrez le fichier index.php de wp2pluxml avec votre éditeur préféré pour modifier le tableau de configuration, notamment <strong>url_blog</strong> et <strong>author</strong>.</p>
            <h2>Export de vos données Wordpress</h2>
            <p>
                Pour générer un export XML depuis votre blog Wordpress, il faut installer et activer
                le plugin <a href="http://wordpress.org/extend/plugins/advanced-export-for-wp-wpmu/">Advanced Export for WP & WPMU</a>
                (dernière version testée, la 2.8.3). L'utilisation de ce plugin n'étant pas très complexe, nous nous passerons d'une documentation détaillée.<br />
                Copiez l'export XML dans le même répertoire que ce script. Actualisez la page pour afficher votre export dans la liste déroulante ci-dessous.
            </p>
            <p>Concernant vos médias (images et documents) de Wordpress, copiez-les dans le répertoire data/images de votre blog PluXml. wp2pluxml se charge de modifier les chemins dans vos billets.</p>
            <h2>Go !</h2>
            <p>
                Sélectionnez votre export XML dans la liste déroulante et validez pour lancer la conversion. Allez boire un café. Revenez.
            </p>
            <p>
                <?php plxUtils::printSelect('file', Tools::displayListXmlWordpressFiles(), $selected, $readonly, $class) ?>
                <input type="submit" value="Lancer la conversion" />
            </p>
            <p>
                Vous êtes l'heureux possesseur d'un blog PluXml :) !<br />Enjoy.
            </p>
        </fieldset>
        </form>
    </div>
</div>
<p class="auth_return">G&eacute;n&eacute;r&eacute; par <a href="http://code.google.com/p/wp2pluxml/">wp2pluxml</a></p>

</body>
</html>
