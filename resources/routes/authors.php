<?php

/**
 * This file contains Author related routes
 */

use Symfony\Component\HttpFoundation\Request;

/**
 * List all authors
 */
$app->get('authors/', function() use ($app) {

            $authorDAO = new AuthorDAO($app['db']);
            $authors = $authorDAO->getAll();
            return $app['twig']->render('authors.twig', array('authors' => $authors));
        })->bind('authors');


/**
 * Creates new author
 */
$app->match('/authors/new', function (Request $request) use ($app) {

            // Creates form with 3 fields
            $form = $app['form.factory']->createBuilder('form')
                    ->add('author_name', 'text')
                    ->add('email', 'text')
                    ->add('bio', 'textarea')
                    ->getForm();

            // Verifies if method is correct
            if ('POST' == $request->getMethod()) {
                $form->bind($request);

                // Process adding new author
                if ($form->isValid()) {
                    $data = $form->getData();
                    $authorDAO = new AuthorDAO($app['db']);
                    $result = $authorDAO->addNew($data);
                    return $result;
                }
            }

            // display the form
            return $app['twig']->render('author_new.twig', array('form' => $form->createView()));
        })->bind('author_new');

/**
 * Deletes single author and their posts
 */
$app->match('authors/{author_id}/delete', function(Request $request, $author_id) use ($app) {

            $authorDAO = new AuthorDAO($app['db']);
            $postDAO = new PostDAO($app['db']);

            $author_id = (int) $author_id;
            
            // Fetch author
            $author = $authorDAO->getById($author_id);
            
            // Check if author exists and retrieve all posts
            if ($author) {
                $posts = $postDAO->getAll($author_id);

                // Check if author has any posts and remove them
                if ($posts) {
                    foreach ($posts as $post) {
                        $postDAO->deleteById($post->getPost_id());
                    }
                }
            }
            
            // Delete author
            $result = $authorDAO->deleteById($author_id);
            return $result;
        })->bind('author_delete');

/**
 * Lists single author
 */
$app->get('authors/{author_id}', function($author_id) use ($app) {

            $authorDAO = new AuthorDAO($app['db']);
            $postDAO = new PostDAO($app['db']);
            
            // Fetches author
            $author = $authorDAO->getById((int) $author_id);
            
            // Fetches all posts by author
            $posts_by_author = $postDAO->getAll((int) $author_id);
            
            // If there is no author return error template
            if (!$author) {
                return $app['twig']->render('error.twig');
            }
            
            // Render template with author object and all posts in array
            return $app['twig']->render('author_single.twig', array('author' => $author, 'posts' => $posts_by_author));
        })->bind('author_single');