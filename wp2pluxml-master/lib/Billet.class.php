<?php
/**
 * Classe Billet
 *
 * Correspond à un billet
 *
 * @category   wp2pluxml
 * @author     Nicolas Lœuillet <nicolas.loeuillet@gmail.com>
 * @copyright  2010-2013
 * @license    http://www.wtfpl.net/ see COPYING file
 */

class Billet
{
    /**
     * Identifiant du billet sur 4 caractères (0000)
     * @var string
     */
    public $id;
    /**
     * Titre du billet
     * @var string
     */
    public $title;
    /**
     * Permet de savoir si on peut poster des commentaires. Si $allow_com vaut
     * 1, on peut poster. S'il vaut 0, on ne peut pas.
     * @var string
     */
    public $allow_com;
    /**
     * Chapeau du billet au format HTML
     * @var string
     */
    public $chapo;
    /**
     * Contenu du billet au format HTML
     * @var string
     */
    public $content;
    /**
     * URL du billet pour le nom du fichier
     * @var string
     */
    public $url_file;
    /**
     * URL du billet
     * @var string
     */
    public $url;
    /**
     * Date de publication du billet
     * @var string
     */
    public $date;
    /**
     * Nom de l'auteur des billets
     * @var string
     */
    public $author;
    /**
     * URL de la rubrique
     * @var string
     */
    public $rubrique_url;
    /**
     * Identifiant de la rubrique qui contient le billet sur 3 caractères (000)
     * @var string
     */
    public $rubrique_id;
    /**
     * Description de la rubrique
     * @var string
     */
    public $category_description;
    /**
     * Liste des commentaires du billet
     * @var array
     */
    public $comments;
    /**
     * Status du billet
     * @var string
     */
    public $status;


    /**
     * Constructeur
     *
     * @access public
     * @param SimpleXMLElement $item
     */
    public function  __construct(SimpleXMLElement $item, $data = NULL)
    {
        $this->setTitle($item->title);
        $this->setAllowCom($item->comment_status);
        $this->setChapo($item->excerpt);
        $this->setContent($item->content);
        $this->setUrl($item->link);
        $this->setUrlFile($item->post_name);
        $this->setDate($item->post_date);
        $this->setRubriqueUrl($item->category);
        $this->setComments($item->comment);
        $this->setStatus($item->status);
    }

    /**
     * Setter de l'identifiant
     *
     * @access public
     * @param integer $id
     */
    public function setId($id)
    {
        $id = sprintf("%04s", $id);
        $this->id = $id;
    }

    /**
     * Getter de l'identifiant
     *
     * @access public
     * @return string identifiant sur 4 caractères (0000)
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Setter de l'identifiant
     *
     * @access public
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Getter du titre
     *
     * @access public
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Setter de allow_com (commentaires autorisés)
     *
     * @access public
     * @param string $comment_status
     */
    public function setAllowCom($comment_status)
    {
        $this->allow_com = $comment_status;
    }

    /**
     * Getter de allow_com
     *
     * @access public
     * @return integer
     */
    public function getAllowCom()
    {
        return $this->allow_com;
    }

    /**
     * Setter du chapeau
     *
     * @access public
     * @param string $chapo
     */
    public function setChapo($chapo)
    {
        $this->chapo = nl2br($chapo);
    }

    /**
     * Getter du chapeau
     *
     * @access public
     * @return string
     */
    public function getChapo()
    {
        return $this->chapo;
    }

    /**
     * Setter du contenu
     *
     * @access public
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = nl2br($content);
    }

    /**
     * Getter du contenu
     *
     * @access public
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Setter de l'URL pour le nom du fichier
     *
     * @access public
     * @param string $post_name
     */
    public function setUrlFile($post_name)
    {
        $toReplace = array('%c2%ab', '%c2%bb', '%c3%b8', '%d0%b0', '%e2%80%a6',
            '%c2%a0', '%e2%80%a6', '%c2%a0a', '_', '/');
        $post_name = str_replace($toReplace, '', $post_name);
        $this->url_file = $post_name;
    }

    /**
     * Getter de l'URL pour le nom du fichier
     *
     * @access public
     * @return string
     */
    public function getUrlFile()
    {
        return $this->url_file;
    }

    /**
     * Setter de l'URL
     *
     * @access public
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * Getter de l'URL
     *
     * @access public
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Setter de la date
     *
     * @access public
     * @param string $post_date
     */
    public function setDate($post_date)
    {
        $this->date = $post_date;
    }

    /**
     * Getter de la date
     *
     * @access public
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Setter de l'auteur
     *
     * @access public
     * @param string $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * Getter de l'auteur
     *
     * @access public
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Setter de l'URL de la première rubrique
     *
     * @access public
     * @param string $rubrique_url
     */
    public function setRubriqueUrl($rubrique_url)
    {
        $this->rubrique_url = $rubrique_url;
    }

    /**
     * Getter de l'identifiant
     *
     * @access public
     * @return string
     */
    public function getRubriqueUrl()
    {
        return $this->rubrique_url;
    }

    /**
     * Setter de l'identifiant de la rubrique sur 3 caractères (000)
     *
     * @access public
     * @param string $rubrique_id
     */
    public function setRubriqueId($rubrique_id)
    {
        $this->rubrique_id = $rubrique_id;
    }

    /**
     * Getter de l'identifiant
     *
     * @access public
     * @return string
     */
    public function getRubriqueId()
    {
        return $this->rubrique_id;
    }

    /**
     * Setter de la description de la rubrique
     *
     * @access public
     * @param string $description
     */
    public function setCategoryDescription($category_description)
    {
        $this->date = $category_description;
    }

    /**
     * Getter de la description de la rubrique
     *
     * @access public
     * @return string
     */
    public function getCategoryDescription()
    {
        return $this->category_description;
    }

    /**
     * Setter des commentaires
     *
     * @access public
     * @param array $comments
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
    }

    /**
     * Getter des commentaires
     *
     * @access public
     * @return array
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Getter du statut
     *
     * @access public
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Setter du statut
     *
     * @access public
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Formate la date pour être utilisée dans le nom du fichier
     *
     * @access public
     * @return string
     */
    public function getDateForFilename()
    {
        return substr($this->date, 0, 4) . substr($this->date, 5, 2) .
            substr($this->date, 8, 2) . substr($this->date, 11, 2) .
            substr($this->date, 14, 2);
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
        if ($this->getStatus() == 'attente') {
            $filename .= '_';
        }

        $filename .= $this->id . '.';

        # Le billet est-il un brouillon ou était-il à la corbeille ?
        if ($this->getStatus() == 'brouillon' || $this->getStatus() == 'corbeille') {
            $filename .= 'draft,';
        }

        $filename .= $this->getRubriqueId() . '.001.' .
            $this->getDateForFilename() . '.' . $this->url_file . '.xml';

        return $filename;
    }

    /**
     * Permet de changer le chemin des médias du site avec
     * le bon chemin dans PluXml
     *
     * @access public
     * @param string $source export XML
     * @return string document XML avec les chemins des médias modifiés
     */
    public function convertPathMedias($source)
    {

        return $source;
    }

    /**
     * Nettoie le XML
     *
     * @access public
     * @param string $source export XML
     * @return string document XML nettoyé
     */
    public function cleanXML($source)
    {
        $source = self::convertPathMedias($source);

        return $source;
    }
}