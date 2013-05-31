<?php
use \Symfony\Component\Form\Exception\Exception as Exception;
use Symfony\Component\HttpFoundation\JsonResponse as JsonResponse;
/**
 * DAO for Post class - Handles all database operations for Post class
 *
 * @author Fred
 */
class PostDAO {

    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getById($post_id)
    {
        $sql = "SELECT * FROM posts WHERE post_id = ?";
        $result = $this->db->fetchAssoc($sql, array((int) $post_id));

        $post = new Post($result);
        return $post;
    }

    /**
     * 
     * Gets all posts from an author
     * @return Post[] Array with post objects
     */
    public function getAll($author_id)
    {
        $sql = "SELECT * FROM posts where owner_id = ?";
        $results = $this->db->fetchAll($sql, array($author_id));
        $posts = array();

        foreach ($results as $result) {
            $posts[] = new Post($result);
        }

        return $posts;
    }

    public function deleteById($postId)
    {
        if ($this->db->delete('posts', array('post_id' => $postId) ) ) {
            return new JsonResponse(array('status' => 'deleted'));
        } else {
            return new JsonResponse(array('status' => 'error'));
        }
    }

    public function addNew($params)
    {
        $newPost = new Post($params);
        $saved = $this->save($newPost);

        if ($saved) {
            return new Symfony\Component\HttpFoundation\JsonResponse(array('message' => "Post created successfuly with ID {$newPost->getPost_id()} "));
        } else {
            return new Symfony\Component\HttpFoundation\JsonResponse(array('message' => "Could not create new post."));
        }
    }

    public function save(Post $post)
    {
        try {
            $this->db->insert('posts', array('title' => $post->getTitle(), 'owner_id' => $post->getOwner_id(), 'message' => $post->getMessage()));
            $post->setPost_id($this->db->lastInsertId());
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }

        return true;
    }

}

?>