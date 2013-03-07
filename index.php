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

$demarrer_installation = TRUE;

# On vérifie que PluXml est installé
$pluxml_actif = TRUE;
if(!file_exists(path('XMLFILE_PARAMETERS'))) {
    $pluxml_actif = FALSE;
    $demarrer_installation = FALSE;
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

include_once 'lib/SimpleXMLExtend.class.php';
include_once 'lib/Wordpress.class.php';
include_once 'lib/Tools.class.php';
include_once 'lib/oolog.class.php';

# Configuration de base
$config = array(
    'url_blog'            => '', // URL de votre site, sans le slash final
    'racine'              => plxUtils::getRacine(),
    'author'              => '',
    'documents'           => PLX_ROOT.'data/documents/',
    'racine_articles'     => PLX_ROOT.'data/articles/',
    'racine_commentaires' => PLX_ROOT.'data/commentaires/',
    'categories'          => PLX_ROOT.'data/configuration/categories.xml',
);

# On vérifie que SimpleXML est installée
$simplexml_actif = TRUE;
if (!extension_loaded('simplexml')) {
    $simplexml_actif = FALSE;
    $demarrer_installation = FALSE;
}

# On vérifie qu'il y a bien un ou plusieurs fichiers d'export dans le répertoire de wp2pluxml
$xml_presents = TRUE;
if (($liste_fichiers = Tools::displayListXmlWordpressFiles()) == array()) {
    $xml_presents = FALSE;
    $demarrer_installation = FALSE;
}

# On vérifie que l'URL du blog est bien définie
$url_definie = TRUE;
if (!$config['url_blog']) {
    $url_definie = FALSE;
    $demarrer_installation = FALSE;
}

# On vérifie que le nom de l'auteur est bien défini
$auteur_defini = TRUE;
if (!$config['author']) {
    $auteur_defini = FALSE;
    $demarrer_installation = FALSE;
}

# Echappement des caractères
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_POST = plxUtils::unSlash($_POST);
}

if(!empty($_POST['file'])) {
    # Conversion !
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
<link rel="stylesheet" href="assets/knacss.css" media="all">
<link rel="stylesheet" href="assets/style.css" media="all">
</head>
<body class="w960p mtl ml3">

    <header class="col w150p mr2">
        <h1><img src="assets/logo_wp2pluxml.jpg" alt="wp2pluxml" /></h1>
    </header>

    <div id="wrap" class="col">
        <h2>wp2pluxml</h2>

        <div>
            <b>wp2pluxml</b> vous permet de convertir le contenu de votre blog Wordpress en un blog PluXml. Très simplement, les billets, les pages, les commentaires et les auteurs pourront être récupérés sur votre tout nouveau PluXml fraichement installé.
        </div>

        <h2>Pré-requis</h2>

        <div>
            <ul id="prerequis" class="w400p">
                <li class="<?php echo ($url_definie ? 'actif' : 'inactif'); ?>">Modifier $config['url_blog'] (ligne 50 de ce fichier)</li>
                <li class="<?php echo ($auteur_defini ? 'actif' : 'inactif'); ?>">Modifier $config['author'] (ligne 52 de ce fichier)</li>
                <li class="<?php echo ($pluxml_actif ? 'actif' : 'inactif'); ?>">PluXml installé dans le répertoire parent</li>
                <li class="<?php echo ($simplexml_actif ? 'actif' : 'inactif'); ?>">Extension SimpleXML installée</li>
                <li class="<?php echo ($xml_presents ? 'actif' : 'inactif'); ?>">Fichier(s) d'export présent(s)</li>

            </ul>
        </div>

        <h2>Go !</h2>

        <?php if(!$demarrer_installation) : ?>
        <div class="erreur">
            Toutes les conditions ne sont pas réunies pour démarrer la conversion. Merci de relire les pré-requis ci-dessus.
        </div>
        <?php else: ?>

        <div>
            Sélectionnez votre export XML dans la liste déroulante et validez pour lancer la conversion. Allez boire un café. Revenez.
        </div>
        <div>
            <form action="index.php" method="post">
            <?php plxUtils::printSelect('file', $liste_fichiers) ?>
                <input type="submit" value="Lancer la conversion" />
            </form>
        </div>

        <div>
            Vous êtes l'heureux possesseur d'un blog PluXml :) ! Enjoy.
        </div>

        <?php endif; ?>

        <h2>Quelques liens</h2>
        <ul>
            <li><a href="https://github.com/nicosomb/wp2pluxml/issues/new">Rapporter un bug</a></li>
            <li><a href="https://github.com/nicosomb/wp2pluxml/wiki">Documentation</a></li>
            <li><a href="http://www.cdetc.fr/wp2pluxml/">Le blog du projet</a></li>
        </ul>

    </div>

    <footer class="right small">
        <a href="https://github.com/nicosomb/wp2pluxml/">wp2pluxml</a>, par <a href="http://www.cdetc.fr">Nicolas Lœuillet</a>
    </footer>

</body>
</html>
