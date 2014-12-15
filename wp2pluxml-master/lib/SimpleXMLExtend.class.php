<?php
/**
 * Classe SimpleXMLExtend
 *
 * Cette classe étend SimpleXMLElement pour permettre l'ajout de sections
 * CDATA dans un fichier XML
 *
 * @category   wp2pluxml
 * @author     Nicolas Lœuillet <nicolas.loeuillet@gmail.com>
 * @copyright  2010-2013
 * @license    http://www.wtfpl.net/ see COPYING file
 */

class SimpleXMLExtend extends SimpleXMLElement
{
    /**
     * Permet d'ajouter des sections CDATA dans un XML
     *
     * @author jonasmartinez at gmail dot com
     * @see http://fr.php.net/manual/fr/simplexmlelement.addChild.php#89616
     * @access public
     * @version 1.0.0
     * @param string $nodename nom du noeud
     * @param string $cdata_text contenu à insérer dans la section CDATA
     * @param array $attr liste des attributs à ajouter au noeud
     */
    public function addCData($nodename, $cdata_text, $attr = array())
    {
        $node = $this->addChild($nodename);

        if (!empty($attr)) {
            foreach ($attr as $key => $value) {
                $node->addAttribute($key, $value);
            }
        }

        $node = dom_import_simplexml($node);
        $no   = $node->ownerDocument;

        $node->appendChild($no->createCDATASection($cdata_text));
    }
}