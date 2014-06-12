<?php
/**
 * Classe Tools
 *
 * Contient les outils nécessaires à la création des fichiers XML qui seront
 * intégrés à PluXml
 *
 * @category   wp2pluxml
 * @author     Nicolas Lœuillet <nicolas.loeuillet@gmail.com>
 * @copyright  2010-2013
 * @license    http://www.wtfpl.net/ see COPYING file
 */

class Tools
{
    /**
     * Crée un fichier XML PluXml en fonction du billet
     *
     * @access private
     * @param Billet $billet correspond à un billet
     * @return SimpleXMLExtend fichier xml correspondant au billet
     */
    private function generateXmlForExport(Billet $billet)
    {
        $output = "<?xml version='1.0' encoding='UTF-8'?>" . "\n" .
                    "<document></document>";

        $xml = new SimpleXMLExtend($output);

        $xml->addCData("title", $billet->getTitle());
        $xml->addChild("allow_com", $billet->getAllowCom());
        $xml->addCData("template", 'article.php');
        $xml->addCData("chapo", $billet->getChapo());
        $xml->addCData("content", $billet->getContent());
        $xml->addCData("tags", '');
        $xml->addCData("meta_description", '');
        $xml->addCData("meta_keywords", '');

        return $xml->asXML();
    }

    /**
     * Crée un fichier XML PluXml pour un commentaire
     *
     * @access private
     * @param array $comment correspond à un commentaire
     * @return SimpleXMLExtend fichier xml correspondant au commentaire
     */
    private function generateXmlCommentsForExport($comment)
    {
        $output = "<?xml version='1.0' encoding='UTF-8'?>" . "\n" .
                    "<comment></comment>";

        $xml = new SimpleXMLExtend($output);

        $xml->addCData("author", $comment['comment_author']);
        $xml->addChild("type", 'normal');
        $xml->addChild("ip", $comment['comment_author_IP']);
        $xml->addCData("mail", $comment['comment_author_email']);
        $xml->addCData("site", $comment['comment_author_url']);
        $xml->addCData("content", $comment['comment_content']);

        return $xml->asXML();
    }

    /**
     * Écrit du contenu XML dans un fichier
     *
     * @param string $xml contenu XML à écrire
     * @param string $output chemin du fichier
     * @param string $mode mode d'ouverture du fichier
     * @return boolean
     */
    private function writeXMLinFile($xml, $output, $mode = 'w')
    {
        if (!$handle = fopen($output, $mode)) {
            Tools::log('Erreur lors de l\'ouverture du fichier '. $output, __LINE__);
            return FALSE;
        }

        if (!fwrite($handle, $xml)) {
            Tools::log('Erreur lors de l\'écriture dans le fichier '. $output, __LINE__);
            return FALSE;
        }

        fclose($handle);

        return TRUE;
    }

    /**
     * Écrit dans le fichier XML le contenu du billet
     *
     * @access private
     * @param Billet $billet correspond à un billet
     * @param array $config configuration de wp2pluxml
     * @return Boolean TRUE si tout se passe bien, FALSE sinon
     */
    private function generateXmlFile(Billet $billet, $config)
    {
        $output = $config['racine_articles'] . $billet->getFilename();
        $xml    = self::generateXmlForExport($billet);

        if (!self::writeXMLinFile($xml, $output)) {
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Écrit dans le fichier XML le contenu du commentaire
     *
     * @access private
     * @param Billet $billet correspond à un billet
     * @param array $config configuration de wp2pluxml
     * @return Boolean TRUE si tout se passe bien, FALSE sinon
     */
    private function generateXmlCommentsFile(Billet $billet, $config)
    {
        foreach ($billet->getComments() as $comment) {
            $output = $config['racine_commentaires'] .
                $billet->getId() . '.' . self::getCommentDateForFilename($comment) . '-1.xml';
            $xml    = self::generateXmlCommentsForExport($comment);

            if (!self::writeXMLinFile($xml, $output)) {
                return FALSE;
            }
        }

        return TRUE;
    }

    /**
     * Formate la date du commentaire pour être utilisée dans le nom du fichier
     * au format timestamp
     *
     * @access private
     * @return string
     */
    private function getCommentDateForFilename($comment)
    {
        $timestamp = mktime(substr($comment['comment_date'], 11, 2),
                substr($comment['comment_date'], 14, 2), 0,
                substr($comment['comment_date'], 5, 2),
                substr($comment['comment_date'], 8, 2),
                substr($comment['comment_date'], 0, 4)
                );

        return $timestamp;
    }

    /**
     * Teste si un fichier a une extension autorisée
     *
     * @access private
     * @param string $file nom complet du fichier
     * @return boolean TRUE si l'extension du fichier est autorisée, FALSE sinon
     */
    private function isAvailableExtension($file)
    {
        $available_extensions = array('xml');

        return (in_array(substr(strrchr($file,'.'), 1), $available_extensions) ? TRUE : FALSE);
    }

    /**
     * Génère un tableau contenant le nom des fichiers XML dans le répertoire
     * principal de wp2pluxml
     *
     * @access private
     * @return Array tableau avec les noms des fichiers XML
     */
    private function getXmlFiles()
    {
        $files = array();

        if (!$handle = opendir(".")) {
            Tools::log('Erreur lors du parcours du répertoire principal.', __LINE__);
            return FALSE;
        }
        else {
            while (FALSE !== ($filename = readdir($handle))) {
                if (Tools::isAvailableExtension($filename)) {
                    $files[] = $filename;
                }
            }
        }

        return $files;
    }

    /**
     * Retourne la liste des fichiers XML présents dans le répertoire de
     * wp2pluxml
     *
     * @access public
     * @return array
     */
    public function displayListXmlFiles()
    {
        $liste = array();
        $files = Tools::getXmlFiles();
        foreach ($files as $file) {
            $liste[$file] = $file;
        }

        return $liste;
    }

    /**
     * Vérifie si le nom du fichier est correct :
     * - le fichier existe
     * - l'extension est autorisée
     * - le fichier n'est pas le nom du répertoire courant ou précédent
     *
     * @access private
     * @param string $filename nom du fichier à vérifier
     * @return boolean TRUE si le nom du fichier est correct, FALSE sinon
     */
    private function checkFilename($filename)
    {
        if (!Tools::isAvailableExtension($filename)) {
            Tools::log('Extension pas disponible pour le fichier ' . $filename, __LINE__);
            return FALSE;
        }

        if (!file_exists($filename)) {
            Tools::log('Le fichier ' . $filename . 'n\'existe pas.', __LINE__);
            return FALSE;
        }

        if ('.' == $filename || '..' == $filename) {
            Tools::log('Le nom du fichier n\'est pas correct.', __LINE__);
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Crée un fichier XML pour les catégories
     *
     * @access private
     * @param array $items correspond aux catégories
     * @param array $config configuration de wp2pluxml
     * @return SimpleXMLExtend fichier xml correspondant au commentaire
     */
    private function generateXmlCategoriesForExport($items, $config)
    {
        $output = "<?xml version='1.0' encoding='UTF-8'?>" . "\n" .
                    "<document></document>";

        $xml = new SimpleXMLExtend($output);

        $count = 1;
        foreach ($items as $item) {
            $number = sprintf("%03s", $count);

            $categorie = $xml->addChild('categorie');
            $categorie->addAttribute('number', $number);
            $categorie->addAttribute('active', '1');
            $categorie->addAttribute('homepage', '1');
            $categorie->addAttribute('tri', 'desc');
            $categorie->addAttribute('bypage', '5');
            $categorie->addAttribute('menu', 'oui');
            $categorie->addAttribute('url', $item->category_nicename);
            $categorie->addAttribute('template', 'categorie.php');

            $categorie->addCData("name", $item->cat_name);
            $categorie->addCData("description", $item->category_description);
            $categorie->addCData("meta_description", $item->category_description);
            $categorie->addCData("meta_keywords", '');
            $categorie->addCData("title_htmltag", '');

            // if (!Tools::addCategorieToHtaccess($item, $number, $config)) {
            //     return FALSE;
            // }

            $count ++;
        }

        return $xml->asXML();
    }

    /**
     * Écrit dans le fichier XML les catégories
     *
     * @access private
     * @param array $items correspond aux catégories
     * @param array $config configuration de wp2pluxml
     * @return Boolean TRUE si tout se passe bien, FALSE sinon
     */
    private function generateXmlCategoriesFile($items, $config)
    {
        $xml = self::generateXmlCategoriesForExport($items, $config);

        if (!self::writeXMLinFile($xml, $config['categories'])) {
            return FALSE;
        }

        return $xml;
    }

    /**
     * Retrouve l'identifiant d'une rubrique en fonction de son URL
     *
     * @access private
     * @param string $categories XML des catégories
     * @param string $rubrique_url URL d'une rubrique
     * @return string
     */
    private function getRubriqueIdByUrl($categories, $rubrique_url)
    {
        $xml      = @simplexml_load_string($categories);
        $elements = $xml->categorie;

        foreach ($elements as $element) {
            if ((string) $element['url'] == $rubrique_url)
            {
                return $element['number'];
            }
        }

        return '001';
    }

    /**
     * Vérifie si le fichier XML de l'export est correct
     *
     * @param string $file URL du fichier XML à parser
     * @return boolean
     */
    private function checkExport($file)
    {
        if ('' == $file) {
            Tools::log('Le fichier XML de l\'export n\'est pas correct', __LINE__);
            return FALSE;
        }

        if (!self::checkFilename($file)) {
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Convertit le fichier XML en un objet XML
     *
     * @param string $file URL du fichier XML à parser
     * @return SimpleXMLElement
     */
    private function getXmlFromExport($file, $type_site)
    {
        $data = file_get_contents($file);

        if (!$data) {
            Tools::log('Erreur lors de la récupération du contenu du fichier XML', __LINE__);
            return NULL;
        }

        $data = $type_site::cleanXML($data);

        if (!$data) {
            Tools::log('Erreur lors du nettoyage du fichier XML', __LINE__);
            return NULL;
        }

        return $data;
    }

    /**
     * Lance la conversion des catégories
     *
     * @param SimpleXMLElement $categories noeud XML correspondant aux catégories
     * @param array $config configuration de wp2pluxml
     * @return object
     */
    public function convertCategories($categories, $config)
    {
        $items = $categories;

        if (count($items) == 0) {
            Tools::log('Le tableau $items des catégories est vide', __LINE__);
            return NULL;
        }

        if (!($categories = self::generateXmlCategoriesFile($items, $config))) {
            return NULL;
        }

        return $categories;
    }

    /**
     * Génère la ligne à ajouter dans le .htaccess pour les catégories
     *
     * @param SimpleXMLElement $categorie noeud XML correspondant à la catégorie
     * @param integer $number identifiant de la catégorie sous PluXml
     * @param array $config configuration de wp2pluxml
     * @return boolean
     */
    private function addCategorieToHtaccess($categorie, $number, $config)
    {
        $content = 'RedirectPermanent /category/'. $categorie->category_nicename . ' /?categorie' .
            $number . '/' . $categorie->category_nicename . "\r\n";

        if (!Tools::writeXMLinFile($content, '../.htaccess', 'a')) {
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Génère la ligne à ajouter dans le .htaccess pour les billets
     *
     * @param Billet $billet correspond à un billet
     * @param array $config configuration de wp2pluxml
     * @return boolean
     */
    private function addBilletToHtaccess(Billet $billet, $config)
    {
        // $url_originale = str_replace($config['url_blog'], '', $billet->getUrl());
        $url_originale = $billet->getUrl();
        $content       = 'RedirectPermanent '. $url_originale . ' /?article' .
            $billet->getId() . '/' . $billet->getUrlFile() . "\r\n";

        if (!Tools::writeXMLinFile($content, '../.htaccess', 'a')) {
            return FALSE;
        }

        return TRUE;
    }

    /**
     * Lance la conversion des billets et des commentaires
     *
     * @param array $data tableau comprenant les billets, les catégories et tout le xml
     * @return boolean
     */
    public function convertPostsAndComments($data, $categories, $config, $type_site)
    {
        $id = 1;

        $items = $data['items'];

        if (count($items) == 0) {
            Tools::log('Le tableau $items pour les billets est vide', __LINE__);
            return FALSE;
        }

        foreach ($items as $item) {
            $billet = new $type_site($item, $data);
            $billet->setId($id);
            // FIXME remplacer ici par l'identifiant de l'auteur du billet
            // $billet->setAuthor($config['author']);

            $rubrique_id = self::getRubriqueIdByUrl($categories, $billet->getRubriqueUrl());
            $billet->setRubriqueId($rubrique_id);

            if (!self::generateXmlFile($billet, $config)) {
                return FALSE;
            }

            if (!self::generateXmlCommentsFile($billet, $config)) {
                return FALSE;
            }

            // if (!Tools::addBilletToHtaccess($billet, $config)) {
            //     return FALSE;
            // }

            $id ++;
        }

        return TRUE;
    }

    /**
     * Permet de logger un texte dans le ficheir de log de wp2pluxml
     *
     * @param string $text texte à logger
     * @param integer $line numéro de la ligne
     */

    public function log($text, $line)
    {
        $log =& new oolog("wp2pluxml.log", FILE | DEBUG);
        $log->log($text, DEBUG, false, $line);
        $log->closelog();
    }

    public function verificationInstallation($demarrer_installation, $config)
    {
        $verifications = array(
            'demarrer_installation' => $demarrer_installation,
            'simplexml_actif'       => TRUE,
            'xml_presents'       => TRUE,
            );

        # On vérifie que SimpleXML est installée
        if (!extension_loaded('simplexml')) {
            $verifications['simplexml_actif'] = FALSE;
            $verifications['demarrer_installation'] = FALSE;
        }

        # On vérifie qu'il y a bien un ou plusieurs fichiers d'export dans le répertoire de wp2pluxml
        if (self::displayListXmlFiles() == array()) {
            $verifications['xml_presents'] = FALSE;
            $verifications['demarrer_installation'] = FALSE;
        }

        return $verifications;
    }

    /**
     * Lancement de la conversion vers PluXml
     *
     * @param mixed $post Données $_POST passées sur le script PluXml
     * @param array $config Tableau de configuration pour wp2pluxml
     */
    public function convert2pluxml($post, $config)
    {
        $filename  = (isset($post['file']) ? $post['file'] : '');
        $type_site = (isset($post['type_site']) ? $post['type_site'] : 'Wordpress');

        if (!self::checkExport($filename)) {
            return FALSE;
        }

        if (!($xml = Tools::getXmlFromExport($filename, $type_site))) {
            return FALSE;
        }

        if (!($categories = self::convertCategories($xml['categories'], $config))) {
            return FALSE;
        }

        if (!self::convertPostsAndComments($xml, $categories, $config, $type_site)) {
            return FALSE;
        }

        return TRUE;
    }
}