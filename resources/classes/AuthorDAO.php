<?php

/**
 * DAO for Author class - Handles all database operations for Author class
 *
 * @author Fred
 */
use \Symfony\Component\Form\Exception\Exception as Exception;
use Symfony\Component\HttpFoundation\JsonResponse as JsonResponse;

class AuthorDAO {

    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * 
     * Gets a result from the database by passing an author id
     * @param int $author_id Id of the author to fetch
     * @return Author Object
     */
    public function getById($author_id)
    {
        $sql = "SELECT * FROM authors WHERE author_id = ?";
        $result = $this->db->fetchAssoc($sql, array((int) $author_id));

        $author = new Author($result);

        if ($author->getAuthor_id()) {
            return $author;
        } else {
            return false;
        }
    }

    /**
     * 
     * Gets all authors
     * @return Author[] Array with author objects
     */
    public function getAll()
    {
        $sql = "SELECT * FROM authors";
        $results = $this->db->fetchAll($sql);
        $authors = array();

        foreach ($results as $result) {
            $authors[] = new Author($result);
        }

        return $authors;
    }

    /**
     * 
     * Gets a result from the database by passing an author name
     * @param string $author_name name of the author to fetch
     * @return Author Object
     */
    public function getByName($author_name)
    {
        $sql = "SELECT * FROM authors WHERE author_name = ?";
        $result = $this->db->fetchAssoc($sql, array((int) $author_name));

        $author = new Author($result);
        return $author;
    }

    /**
     * Handles the process of adding a new user
     * @param array $params containing the required fields for Author class
     * @return JsonResponse array['message'] with status
     */
    public function addNew($params)
    {

        // Instantiates new Author object by passed $params array
        $author = new Author($params);

        // Verify if email already exists in DB
        if ($this->isEmailExist($author->getEmail())) {
            return new JsonResponse(array('message' => "Error: email already exists."));
        }

        // Try to save to DB
        $saved = $this->save($author);

        // Check if saved and output correct Response
        if ($saved) {
            return new JsonResponse(array('message' => "Author created with ID {$author->getAuthor_id()} "));
        } else {
            return new JsonResponse(array('message' => "Could not add user"));
        }
    }

    /**
     * Processes saving the new user to the database
     * @param Author $author
     * @return boolean
     */
    private function save(Author $author)
    {
        try {
            $this->db->insert('authors', array('author_name' => $author->getAuthor_name(), 'email' => $author->getEmail(), 'bio' => $author->getBio()));
            $author->setAuthor_id($this->db->lastInsertId());
        } catch (PDOException $e) {
            return $e->getMessage();
        }

        return true;
    }

    /**
     * Verifies if an email address is already registered
     * @param string $email
     * @return boolean true if it exists or false otherwise
     */
    public function isEmailExist($email)
    {
        $sql = "SELECT author_id FROM authors WHERE email = ?";
        return $this->db->fetchAssoc($sql, array($email)) ? true : false;
    }

    /**
     * Delete a given Author by it's ID
     * @param int $author_id
     * @return JsonResponse returns json with status => 'deleted' or status => 'error'
     */
    public function deleteById($author_id)
    {
        if ($this->db->delete('authors', array('author_id' => $author_id))) {
            return new Symfony\Component\HttpFoundation\JsonResponse(array('status' => 'deleted'));
        } else {
            return new Symfony\Component\HttpFoundation\JsonResponse(array('status' => 'error'));
        }
    }

}

?>
