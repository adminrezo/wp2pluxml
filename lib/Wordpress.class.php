<?php
/**
 * Classe Wordpress
 *
 * Correspond à un billet PluXml avec les données de Wordpress
 *
 * @category   wp2pluxml
 * @author     Nicolas Lœuillet <nicolas.loeuillet@gmail.com>
 * @copyright  2010-2013
 * @license    http://www.opensource.org/licenses/mit-license.php  MIT License
 */

class Wordpress
{
    /**
     * Identifiant du billet sur 4 caractères (0000)
     * @var string
     */
    private $id;
    /**
     * Titre du billet
     * @var string
     */
    private $title;
    /**
     * Permet de savoir si on peut poster des commentaires. Si $allow_com vaut
     * 1, on peut poster. S'il vaut 0, on ne peut pas.
     * @var string
     */
    private $allow_com;
    /**
     * Chapeau du billet au format HTML
     * @var string
     */
    private $chapo;
    /**
     * Contenu du billet au format HTML
     * @var string
     */
    private $content;
    /**
     * URL du billet pour le nom du fichier
     * @var string
     */
    private $url_file;
    /**
     * URL du billet
     * @var string
     */
    private $url;
    /**
     * Date de publication du billet
     * @var string
     */
    private $date;
    /**
     * Nom de l'auteur des billets
     * @var string
     */
    private $author;
    /**
     * URL de la rubrique
     * @var string
     */
    private $rubrique_url;
    /**
     * Identifiant de la rubrique qui contient le billet sur 3 caractères (000)
     * @var string
     */
    private $rubrique_id;
    /**
     * Liste des commentaires du billet
     * @var array
     */
    private $comments;

    /**
     * Constructeur
     *
     * @access public
     * @param SimpleXMLElement $item
     */
    public function  __construct(SimpleXMLElement $item)
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
        $this->allow_com = ($comment_status == 'open') ? '1' : '0';
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
     * Formate la date pour être utilisée dans le nom du fichier
     *
     * @access private
     * @return string
     */
    private function getDateForFilename()
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
        return $this->id . '.' . $this->getRubriqueId() . '.001.' .
            $this->getDateForFilename() . '.' . $this->url_file . '.xml';
    }
}