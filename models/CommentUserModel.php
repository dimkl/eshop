<?php

/**
 * Required libraries
 */
require_once './models/CommentModel.php';
require_once './models/UserModel.php';

/**
 * CommentUserModel is class that is used to process data from and to table 'Comment' 
 * joined with data of table User
 *
 * @author dimkl
 */
class CommentUserModel extends CommentModel {

    /**
     * @see Model
     */
    protected static $table = 'Comment join User on User.id=Comment.userid';

    /**
     * @see Model
     */
    protected $publicProperties = ['id', 'userid', 'productid', 'content', 'creationDatetime', 'rating', 'user'];

    /**
     * $user property is used to store user model data as an object for easier handling of data
     * 
     * @var UserModel 
     */
    private $user;

    public function __construct($dataArray = array()) {
        parent::__construct($dataArray);
    }

    /**
     * allBy method is used to get all Comments with their User information 
     *  with the use of $column and $id as criteria
     * 
     * @param string $column
     * @param string $value
     * @return array Returns array of CommentUserModel
     */
    public static function allBy($column, $value) {
        if (!is_string($column) && !is_numeric($value) && !is_string($value)) {
            return [];
        }
        try {
            $table = static::$table;

            $commentUsersAssoc = static::$db->setQuerySql('Select Comment.*,User.* from '
                            . 'Comment join User on User.id=Comment.userid where Comment.'
                            . $column . '=? order by Comment.id desc;')
                    ->setQueryData([$value])
                    ->executeSelect()
                    ->getResult();
            if (empty($commentUsersAssoc)) {
                return [];
            }
            //transform to commentUser
            $commentUsers = [];
            foreach ($commentUsersAssoc as $k => $commentUserAssoc) {
                array_push($commentUsers, static::fromExtendedComment($commentUserAssoc));
            }
            return $commentUsers;
        } catch (Exception $ex) {
            throw new ModelException($ex);
        }
    }

    /**
     * Getter method for getting user information of this instance
     * 
     * @return UserModel
     */
    public function getUser() {
        return $this->user;
    }

    public function setUser(UserModel $user) {
        $this->user = $user;
    }

    /**
     * fromExtendedComment private static method is used for supporting the convertion 
     * in the allBy method. Genarally it takes an array of comment and user tables 
     * join and returns a commentUserModel
     * 
     * @param type $extendedComment
     * @return \CommentUserModel
     */
    private static function fromExtendedComment($extendedComment) {
        if (!is_array($extendedComment)) {
            throw new ModelException('$extendedComment must be type of array');
        }
        $user = new UserModel($extendedComment);
        $commentUser = new CommentUserModel($extendedComment);
        $commentUser->setUser($user);
        return $commentUser;
    }

}
