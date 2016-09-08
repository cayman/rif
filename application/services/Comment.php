<?php
/**
 * Сервис комментариев к узлам
 * User: Zakirov
 * Date: 27.09.2011
 * Time: 14:27:06
 */

class Site_Service_Comment extends Site_Service_Abstract  {


    /**
     * Return comment levels
     * @throws Site_Service_Exception
     * @return Site_Model_Level[]
     */
    public function getLevels() {
        try {
            $list = $this->getLevelMapper()->findAll();
            return $list;
        } catch (Zend_Exception $e) {
            throw new Site_Service_Exception("Method error",'getCommentLevels',3036,$e);
        }
    }

    /**
     * Return comment levels
     * @throws Site_Service_Exception
     * @return Site_Model_Level[]
     */
    public function getLevelsOptions(){
        $levelOptions=array();
        foreach ($this->getLevels() as $level) {
            $levelOptions[$level->id] = $level->name;
        }
        return $levelOptions;
    }

    /**
     * Return comment by ID
     * @throws Site_Service_Exception
     * @param  int $id
     * @return Site_Model_Comment
     */
    public function getComment($id) {
        try {
            $comment = $this->getCommentMapper()->find($id);
            if (empty($comment)) throw new Site_Service_Exception('Comment not found',$id, 3025);
            return $comment;
        } catch (Zend_Exception $e) {
            throw new Site_Service_Exception("Method error",'getComment',3026,$e);
        }
    }

     /**
     * Return comments by node
     * @throws Site_Service_Exception
     * @param  int $node
     * @param  string $order
     * @param bool $paginate
     * @return Site_Model_Comment[]
     */
    public function getComments($node,$order='date',$paginate=true) {
        try {
            if($node instanceof Site_Model_Node )
                $node = $node->id;
            if($paginate){
                $adapter = new Site_Model_CommentPaginator($this->getCommentMapper(), $node, null, $order);
                $list = new Zend_Paginator($adapter);
            }else
                $list = $this->getCommentMapper()->findByNode($node, null, $order);
            return $list;
        } catch (Zend_Exception $e) {
            throw new Site_Service_Exception("Method error",'getComments',3027,$e);
        }
    }

     /**
     * Return comments count
     * @throws Site_Service_Exception
     * @param  string $ip
     * @return Site_Model_Comment[]
     */
    public function countCommentsSpam($ip){
        try {
             return $this->getCommentMapper()->countByIp($ip,Site_Model_Comment::SPAM);
        } catch (Zend_Exception $e) {
            throw new Site_Service_Exception("Method error",'countCommentsSpam',3029,$e);
        }
    }

    /**
     * Append new comment
     * @throws Site_Service_Exception
     * @param  Site_Model_Comment $comment
     * @return int $id
     */
    public function addComment($comment) {
        try {
			if($this->countCommentsSpam($comment->ip)>0)
				throw new Site_Service_Exception('Comment is SPAM',null,3032);
            $id = $this->getCommentMapper()->create($comment);
            if (!is_numeric($id)) throw new Site_Service_Exception('Comment not saved',null,3030);
            return $id;
        } catch (Zend_Exception $e) {
            throw new Site_Service_Exception("Method error",'addComment',3031,$e);
        }
    }

    /**
     * Save change in comment
     * @throws Site_Service_Exception
     * @param  Site_Model_Comment $comment
     * @return null
     */
    public function editComment($comment) {
        try {
            $this->getCommentMapper()->modify($comment);
        } catch (Zend_Exception $e) {
            throw new Site_Service_Exception("Method error",'editComment',3033,$e);
        }
    }

    /**
     * Delete comment
     * @throws Site_Service_Exception
     * @param  Site_Model_Comment $comment
     * @return null
     */
    public function deleteComment($comment) {
        try {
            $comment->level=Site_Model_Comment::SPAM;
            $this->getCommentMapper()->modify($comment);
        } catch (Zend_Exception $e) {
            throw new Site_Service_Exception("Method error",'deleteComment',3035,$e);
        }
    }


    /**
     * @return string
     */
    public function about() {
        return 'Comment service';
    }



}
