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
include(PLX_ROOT.'config.php');
include(PLX_CORE.'lib/config.php');

# On verifie que PluXml est installé
if(!file_exists(path('XMLFILE_PARAMETERS'))) {
    header('Location: '.PLX_ROOT.'install.php');
    exit;
}

# On démarre la session
session_start();

# On inclut les librairies nécessaires
include(PLX_CORE.'lib/class.plx.date.php');
include(PLX_CORE.'lib/class.plx.glob.php');
include(PLX_CORE.'lib/class.plx.utils.php');
include(PLX_CORE.'lib/class.plx.capcha.php');
include(PLX_CORE.'lib/class.plx.erreur.php');
include(PLX_CORE.'lib/class.plx.record.php');
include(PLX_CORE.'lib/class.plx.motor.php');
include(PLX_CORE.'lib/class.plx.feed.php');
include(PLX_CORE.'lib/class.plx.show.php');
include(PLX_CORE.'lib/class.plx.encrypt.php');
include(PLX_CORE.'lib/class.plx.plugins.php');

# Echappement des caractères
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_POST = plxUtils::unSlash($_POST);
}

# Configuration de base
$config = array(
    'url_blog'            => 'http://localhost/pluxml', // URL de votre site, sans le slash final
    'racine'              => plxUtils::getRacine(),
    'author'              => 'admin',
    'documents'           => PLX_ROOT.'data/documents/',
    'racine_articles'     => PLX_ROOT.'data/articles/',
    'racine_commentaires' => PLX_ROOT.'data/commentaires/',
    'categories'          => PLX_ROOT.'data/configuration/categories.xml',
);

include_once 'lib/SimpleXMLExtend.class.php';
include_once 'lib/Wordpress.class.php';
include_once 'lib/Tools.class.php';
include_once 'lib/oolog.class.php';

if(!empty($_POST['file'])) {
    if (!Tools::convert2pluxml($_POST, $config)) {
        die('Une erreur a été rencontrée durant la conversion. Consultez les logs.');
    }
    header('Location: '.PLX_ROOT.'index.php');
    exit;
}

?>
<!DOCTYPE HTML>
<html>
<head>
<title>wp2pluxml - Configuration</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo strtolower(PLX_CHARSET) ?>" />
<link rel="stylesheet" href="knacss.css" media="all">
</head>
<body class="w90 mtl ml3">

    <header>
        <h1>wp2pluxml</h1>
    </header>

    <h2>Présentation</h2>
    <div>
        <a href="https://github.com/nicosomb/wp2pluxml">wp2pluxml</a> vous permet de convertir le contenu de <strong>votre blog Wordpress en un blog PluXml</strong>.
    </div>
    <div><b>Attention</b>, ce script est à exécuter en local et non en environnement de production
        (selon la taille de votre blog Wordpress, la génération peut prendre du temps).
    </div>

    <h2>Pré-requis</h2>
    <div>
        Avant toute chose, sachez que ce script a besoin de PHP 5 pour fonctionner (en effet, nous utilisons <a href="http://fr.php.net/manual/fr/book.simplexml.php">l'extension SimpleXML</a>).<br />
        Ce script est compatible à partir de PluXml 5.1.x et WordPress 3.5.x. Nous n'avons pas testé avec des versions antérieures, impossible donc de dire s'il est compatible avec ces versions. Il y a de fortes chances que ça ne fonctionne pas, PluXml ayant modifié le format de génération des fichiers XML.
    </div>

    <div>
        Avant de faire quoique ce soit, ouvrez le fichier index.php de wp2pluxml avec votre éditeur préféré pour modifier le tableau de configuration, à partir de la ligne 45 (<code># Configuration de base</code>). Copiez le dossier wp2pluxml dans le répertoire de votre PluXml fraichement installé.
    </div>

    <h2>Export de vos données Wordpress</h2>
    <div>
        Pour générer un export XML depuis votre blog Wordpress, il faut installer et activer
        le plugin <a href="http://wordpress.org/extend/plugins/advanced-export-for-wp-wpmu/">Advanced Export for WP & WPMU</a>
        (dernière version testée, la 2.9). L'utilisation de ce plugin n'étant pas très complexe, nous nous passerons d'une documentation détaillée. Nous vous conseillons tout de même de n'exporter que les billets publiés (les brouillons et les pages seront traités par wp2pluxml prochainement).
    </div>
    <div>
        Copiez l'export XML dans le même répertoire que celui de wp2pluxml. Actualisez la page pour afficher votre export dans la liste déroulante ci-dessous.
    </div>
    <div>
        Concernant vos médias (images et documents) de Wordpress, copiez-les dans le répertoire data/images de votre blog PluXml. wp2pluxml se charge de modifier les chemins dans vos billets.
    </div>

    <h2>Go !</h2>
    <div>
        Sélectionnez votre export XML dans la liste déroulante et validez pour lancer la conversion. Allez boire un café. Revenez.
    </div>
    <div>
        <form action="index.php" method="post">
        <?php plxUtils::printSelect('file', Tools::displayListXmlWordpressFiles(), $selected, $readonly, $class) ?>
        <input type="submit" value="Lancer la conversion" />
        </form>
    </div>
    <div>
        Vous êtes l'heureux possesseur d'un blog PluXml :) ! Enjoy.
    </div>

    <div class="right">
        <a href="https://github.com/nicosomb/wp2pluxml/">wp2pluxml</a>, par <a href="http://www.cdetc.fr">Nicolas Lœuillet</a>
    </div>

</body>
</html>
