<?php

require_once './models/CommentModel.php';

/**
 * Description of CommentUserModel
 *
 * @author dimkl
 */
class CommentUserModel extends CommentModel {

    protected static $table = 'Comment';
    protected $publicProperties = ['id', 'userid', 'productid', 'content', 'creationDateTime', 'rating', 'user'];
    private $user;

    /**
     * Overide default findBy
     * 
     * @param type $column
     * @param type $value
     * @return array
     */
    public static function findBy($column, $value) {
        if (!is_string($column) && !is_numeric($value) && !is_string($value)) {
            return [];
        }
        try {

            $table = static::$table;
            $statement = static::$db->pdo->prepare('Select Comment.*,User.* from Comment join User on User.id=Comment.userid where `' . $column . '`=?;');
            $statement->execute([$value]);
            $commentUsersAssoc = $statement->fetchAll(PDO::FETCH_ASSOC);
            if (empty($commentUsersAssoc)) {
                return [];
            }
            $commentUsers = [];
            foreach ($commentUsersAssoc as $k => $commentUserAssoc) {
                $commentUserAssoc["user"] = new UserModel($commentUserAssoc);

                $commentUser = new CommentUserModel($commentUserAssoc);
                array_push($commentUsers, $commentUser);
            }
            return $commentUsers;
        } catch (Exception $e) {
            return [];
        }
    }

    public function getUser() {
        return $this->user;
    }

    public function setUser(UserModel $user) {
        $this->user = $user;
    }

}
