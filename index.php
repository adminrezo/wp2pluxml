<?php
/**
 * Configuration de wp2pluxml
 *
 * @category   wp2pluxml
 * @author     Nicolas Lœuillet <nicolas.loeuillet@gmail.com>
 * @copyright  2010-2013
 * @license    http://www.wtfpl.net/ see COPYING file
 */

error_reporting(E_ALL);
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
include_once 'lib/Billet.class.php';
include_once 'lib/Wordpress.class.php';
include_once 'lib/Spip.class.php';
include_once 'lib/Tools.class.php';
include_once 'lib/oolog.class.php';

# Configuration de base
$config = array(
    'racine'              => plxUtils::getRacine(),
    'documents'           => PLX_ROOT.'data/documents/',
    'racine_articles'     => PLX_ROOT.'data/articles/',
    'racine_commentaires' => PLX_ROOT.'data/commentaires/',
    'categories'          => PLX_ROOT.'data/configuration/categories.xml',
    'auteurs'             => PLX_ROOT.'data/configuration/users.xml',
);

# Vérifications à effectuer avant toute conversion
$verifications = Tools::verificationInstallation($demarrer_installation, $config);

# Echappement des caractères
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_POST = plxUtils::unSlash($_POST);
}

if(!empty($_POST['file'])) {
    # Conversion !
    if (!Tools::convert2pluxml($_POST, $config)) {
        die('Une erreur a &eacute;t&eacute; rencontr&eacute;e durant la conversion. Consultez le fichier wp2pluxml.log pour en savoir plus.');
    }
    header('Location: '.PLX_ROOT.'index.php');
    exit;
}

?>
<!DOCTYPE HTML>
<html>
<head>
<title>wp2pluxml - Passez à PluXml en toute simplicité</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="assets/knacss.css" media="all">
<link rel="stylesheet" href="assets/style.css" media="all">
<link rel="shortcut icon" type="image/png" href="assets/favicon.png" />
</head>
<body class="w960p mtl ml3">

    <header class="col w150p mr2">
        <h1><img src="assets/logo_wp2pluxml.jpg" alt="wp2pluxml" /></h1>
    </header>

    <div id="wrap" class="col">
        <h2>wp2pluxml</h2>

        <div>
            <b>wp2pluxml</b> vous permet de convertir le contenu de votre site (WordPress, SPIP, etc.) en un blog PluXml.
        </div>

        <h2>Pré-requis</h2>

        <div>
            <ul id="prerequis" class="w400p">
                <li class="<?php echo ($pluxml_actif ? 'actif' : 'inactif'); ?>">PluXml installé dans le répertoire parent</li>
                <li class="<?php echo ($verifications['simplexml_actif'] ? 'actif' : 'inactif'); ?>">Extension SimpleXML installée</li>
                <li class="<?php echo ($verifications['xml_presents'] ? 'actif' : 'inactif'); ?>">Fichier(s) d'export présent(s)</li>

            </ul>
        </div>

        <h2>Go !</h2>

        <?php if(!$verifications['demarrer_installation']) : ?>
        <div class="erreur">
            Toutes les conditions ne sont pas réunies pour démarrer la conversion. Merci de relire les pré-requis ci-dessus.
        </div>
        <?php else: ?>

        <div>
            Sélectionnez votre export XML dans la liste déroulante et validez pour lancer la conversion. Allez boire un café. Non, je plaisante, vous n'aurez pas le temps.
        </div>
        <div>
            <form action="index.php" method="post">
                <ul id="liste_formulaire">
                    <li>Type de site à convertir :
                        <ul>
                            <li><label for="type_wp">WordPress</label> <input type="radio" id="type_wp" name="type_site" value="Wordpress" checked="checked" /></li>
                            <li><label for="type_spip">SPIP</label> <input type="radio" id="type_spip" name="type_site" disabled="true" value="Spip" /></li>
                            <li><label for="type_dc">Dotclear</label> <input type="radio" id="type_dc" name="type_site" value="Dotclear" /></li>
                        </ul>
                    </li>
                    <li><?php plxUtils::printSelect('file', Tools::displayListXmlFiles()) ?></li>
                    <li><input type="submit" value="Lancer la conversion" /></li>
                </ul>
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
        </ul>

    </div>

    <footer class="right small">
        <a href="https://github.com/nicosomb/wp2pluxml/">wp2pluxml</a>, par <a href="http://www.cdetc.fr">Nicolas Lœuillet</a> - Do What the Fuck You Want to Public License <a href="http://www.wtfpl.net/"><img src="assets/wtfpl.png" width="80" height="15" alt="WTFPL" /></a>
    </footer>

</body>
</html>
