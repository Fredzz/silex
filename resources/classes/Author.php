<?php

/**
 * Author class
 *
 * @author Fred
 */
class Author {

    private $author_id;
    private $author_name;
    private $email;
    private $bio;
    
    /**
     * 
     * Instantiates object by passing an array with params (matching DB field names)
     * @param array $params
     * @return boolean true in case of success or false if no array is specified
     */
    public function __construct($params = array())
    {
        if (!is_array($params) || empty($params)) {
            return false;
        }

        // Existing user
        if (isset($params['author_id'])) {
            $this->author_id = $params['author_id'];
        }
        
        $this->author_name = $params['author_name'];
        $this->email = $params['email'];
        $this->bio = $params['bio'];

        return true;
    }

    // Getters and setters
    
    public function getAuthor_id()
    {
        return $this->author_id;
    }

    public function setAuthor_id($author_id)
    {
        $this->author_id = $author_id;
    }

    public function getAuthor_name()
    {
        return $this->author_name;
    }

    public function setAuthor_name($author_name)
    {
        $this->author_name = $author_name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getBio()
    {
        return $this->bio;
    }

    public function setBio($bio)
    {
        $this->bio = $bio;
    }
    
    public function setPost_count($postCount)
    {
        $this->post_count = (int)$postCount;
    }

}

?>
