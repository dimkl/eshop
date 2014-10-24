<?php

/**
 * Description of CommentModel
 *
 * @author dimkl
 */
class CommentModel extends Model {

    protected static $table = 'Comment';
    protected $publicProperties = ['id', 'userid', 'productid', 'content', 'creationDatetime', 'rating'];
    private $id;
    private $userid;
    private $productid;
    private $content;
    private $creationDatetime;
    private $rating;

    /**
     * Create comment to database
     * @return bool false if insert is unsuccessfull and true if successfull
     */
    public function create() {
        $values = [
            "userid" => $this->getUserid(),
            "productid" => $this->getProductid(),
            "content" => $this->getContent(),
            "rating" => $this->getRating()
        ];
        $table = static::getTable();

        try {
            $result = static::$db->setQuerySql('Insert into '
                            . $table . '(userid,productid,content,rating) '
                            . 'Values(:userid,:productid,:content,:rating)')
                    ->setQueryData($values)
                    ->executeInsert()
                    ->getResult();
            $this->setId(static::$db->getLastInsertId());
            return $result;
        } catch (Exception $e) {
            return false;
        }
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        if (!is_string($id) && !is_int($id)) {
            throw new Exception('$id must be a int');
        }
        if (is_null($this->id)) {
            $this->id = intval($id);
        }
        return $this;
    }

    public function getUserid() {
        return $this->userid;
    }

    public function setUserid($userid) {
        if (!is_string($userid) && !is_int($userid)) {
            throw new Exception('$userid must be a int');
        }
        $this->userid = intval($userid);
        return $this;
    }

    public function getProductid() {
        return $this->productid;
    }

    public function setProductid($productid) {
        if (!is_string($productid) && !is_int($productid)) {
            throw new Exception('$productid must be a int');
        }
        $this->productid = intval($productid);
        return $this;
    }

    public function getContent() {
        return $this->content;
    }

    public function setContent($content) {
        if (!is_string($content)) {
            throw new Exception('$content must be a string');
        }
        $this->content = $content;
        return $this;
    }

    public function getCreationDatetime() {
        return $this->creationDatetime;
    }

    public function setCreationDatetime($creationDatetime) {
        if (!is_string($creationDatetime)) {
            throw new Exception('$creationDatetime must be a datetime');
        }
        if (empty($this->creationDatetime)) {
            $this->creationDatetime = date('Y-m-d H:i:s');
        }
        return $this->creationDatetime;
    }

    public function getRating() {
        return $this->rating;
    }

    public function setRating($rating) {
        if (!is_numeric($rating)) {
            throw new Exception('$rating must be a numeric');
        }
        $this->rating = $rating;
        return $this;
    }

}
