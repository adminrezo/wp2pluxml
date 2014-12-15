<?php
/**
 * Classe Spip
 *
 * Correspond à un billet PluXml avec les données de Spip
 *
 * @category   wp2pluxml
 * @author     Nicolas Lœuillet <nicolas.loeuillet@gmail.com>
 * @copyright  2010-2013
 * @license    http://www.wtfpl.net/ see COPYING file
 */

class Spip extends Billet
{
    /**
     * Identifiant SPIP
     * @var string
     */
    public $id_spip;

    /**
     * Constructeur
     *
     * @access public
     * @param SimpleXMLElement $item
     */
    public function  __construct(SimpleXMLElement $item, $data = NULL)
    {
        parent::__construct($item);
        $this->setIdSpip($item->id_article);

        $xml = $data['all_xml'];
        $this->setUrlFile($xml->spip_urls);
    }

    /**
     * Setter de l'identifiant SPIP
     *
     * @access public
     * @param integer $id
     */
    public function setIdSpip($id)
    {
        $this->id_spip = $id;
    }

    /**
     * Getter de l'identifiant SPIP
     *
     * @access public
     * @return string identifiant
     */
    public function getIdSpip()
    {
        return $this->id_spip;
    }

    /**
     * Setter de l'URL pour le nom du fichier
     *
     * @access public
     * @param array $urls
     */
    public function setUrlFile($urls)
    {
        $url_billet = '';
        foreach ($urls as $url) {
            if ((string) $url->id_objet == (string) $this->getIdSpip() && (string) $url->type == 'article') {
                $url_billet = (string)$url->url;
                break;
            }
        }

        $toReplace = array('%c2%ab', '%c2%bb', '%c3%b8', '%d0%b0', '%e2%80%a6',
            '%c2%a0', '%e2%80%a6', '%c2%a0a', '_', '/');
        $url_billet = str_replace($toReplace, '', $url_billet);

        $this->url_file = strtolower($url_billet);
    }

    /**
     * Permet de changer le chemin des médias du blog Wordpress avec
     * le bon chemin dans PluXml
     *
     * @access public
     * @todo ne pas laisser les chemins en dur
     * @param string $source export XML d'un blog Wordpress
     * @return string document XML avec les chemins des médias modifiés
     */
    public function convertPathMedias($source)
    {
        $source = str_replace("wp-content/uploads", "data/images", $source);

        return $source;
    }

    /**
     * Nettoie le XML généré par le plugin d'export de Wordpress
     *
     * @access public
     * @param string $source export XML d'un blog Wordpress
     * @return string document XML nettoyé
     */
    public function cleanXML($source)
    {
        $namespaces = array (
            'spip'           => 'channel',
            'spip_rubriques' => 'category',
            'spip_articles'  => 'item',
            'titre'          => 'title',
            'texte'          => 'content',
            'date'           => 'post_date',

            // 'wp:category'             => 'category',
            // 'wp:category_nicename'    => 'category_nicename',
            // 'wp:category_description' => 'category_description',
            // 'wp:cat_name'             => 'cat_name',
            // 'wp:post_name'            => 'post_name',
            // 'wp:post_date'            => 'post_date',
            // 'wp:status'               => 'status',
            // 'wp:comment_status'       => 'comment_status',
            // 'wp:comment'              => 'comment',
            // 'excerpt:encoded'         => 'excerpt',
            // 'content:encoded'         => 'content',
            // 'wp:comment_author'       => 'comment_author',
            // 'wp:comment_author_email' => 'comment_author_email',
            // 'wp:comment_author_url'   => 'comment_author_url',
            // 'wp:comment_author_IP'    => 'comment_author_IP',
            // 'wp:comment_date'         => 'comment_date',
            // 'wp:comment_content'      => 'comment_content',
            // 'wp:comment_approved'     => 'comment_approved',
        );

        foreach ($namespaces as $namespace => $replace) {
            $source = str_replace("<" . $namespace . ">",
                    "<" . $replace . ">", $source);
            $source = str_replace("</" . $namespace . ">",
                    "</" . $replace . ">", $source);
        }

        // $source = self::convertPathMedias($source);
        $source = simplexml_load_string($source);

        $data = array();
        $data['categories'] = $source->category;
        $data['items']      = $source->item;
        $data['all_xml']    = $source;

        return $data;
    }

    /**
     * Setter de allow_com (commentaires autorisés)
     *
     * @access public
     * @param string $comment_status
     */
    public function setAllowCom($comment_status)
    {
        $this->allow_com = ($comment_status == 'open') ? '1' : '0';
    }

    /**
     * Setter de l'URL de la première rubrique
     *
     * @access public
     * @param array $categories ensemble des catégories du billet
     */
    public function setRubriqueUrl($categories)
    {
        $rubrique = '';
        foreach ($categories as $category) {
            if ($category['domain'] == 'category') {
                $rubrique = $category['nicename'];
                break;
            }
        }
        $this->rubrique_url = $rubrique;
    }

    /**
     * Setter des commentaires
     *
     * @access public
     * @param array $comments
     */
    public function setComments($comments)
    {
        $tabComments = array();
        $count = 0;
        foreach ($comments as $comment) {
            if ($comment->comment_approved != 'spam') {
                $tabComments[$count]['comment_author']       = $comment->comment_author;
                $tabComments[$count]['comment_author_email'] = $comment->comment_author_email;
                $tabComments[$count]['comment_author_url']   = $comment->comment_author_url;
                $tabComments[$count]['comment_author_IP']    = $comment->comment_author_IP;
                $tabComments[$count]['comment_date']         = $comment->comment_date;
                $tabComments[$count]['comment_content']      = $comment->comment_content;
                $count ++;
            }
        }

        $this->comments = $tabComments;
    }

    /**
     * Crée le nom du fichier
     *
     * @access public
     * @return string
     */
    public function getFilename()
    {
        $filename = '';

        # Billet en attente de validation
        if ($this->getStatus() == 'pending') {
            $filename .= '_';
        }

        $filename .= $this->id . '.';

        # Le billet est-il un brouillon ou était-il à la corbeille ?
        if ($this->getStatus() == 'draft' || $this->getStatus() == 'auto-draft' || $this->getStatus() == 'trash') {
            $filename .= 'draft,';
        }

        $filename .= $this->getRubriqueId() . '.001.' .
            $this->getDateForFilename() . '.' . $this->url_file . '.xml';

        return $filename;
    }
}