<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Post
 *
 * @author Fred
 */
class Post {

    private $post_id;
    private $owner_id;
    private $title;
    private $message;

    public function __construct($params)
    {
        if (!is_array($params)) {
            return false;
        }

        $this->title = $params['title'];
        $this->message = $params['message'];

        if (isset($params['post_id'])) {
            $this->post_id = $params['post_id'];
        }

        if ((isset($params['owner_id']))) {
            $this->owner_id = $params['owner_id'];
        }

        return true;
    }

    public function getPost_id()
    {
        return $this->post_id;
    }

    public function setPost_id($post_id)
    {
        $this->post_id = $post_id;
    }

    public function getOwner_id()
    {
        return $this->owner_id;
    }

    public function setOwner_id($owner_id)
    {
        $this->owner_id = $owner_id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

}



?>
