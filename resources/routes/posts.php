<?php

/**
 * This file contains Post related routes
 */
use Symfony\Component\HttpFoundation\Request;

/**
 * Add a new post
 */
$app->match('/post/new', function (Request $request) use ($app) {

            $authorDAO = new AuthorDAO($app['db']);

            // Fetch all authors
            $authors = $authorDAO->getAll();

            // Create an array with authors 'id => name' for dropdown
            $authorList = array();
            foreach ($authors as $author) {
                $authorList[$author->getAuthor_id()] = $author->getAuthor_name();
            }

            // Build form and populate dropdown with author list
            $form = $app['form.factory']->createBuilder('form')
                    ->add('title', 'text')
                    ->add('message', 'textarea')
                    ->add('owner_id', 'choice', array(
                        'choices' => $authorList,
                        'label' => 'Author'))
                    ->getForm();

            // Check if request method is POST
            if ('POST' == $request->getMethod()) {
                $form->bind($request);

                // Process adding new user
                if ($form->isValid()) {
                    $data = $form->getData();
                    $postDAO = new PostDAO($app['db']);
                    $result = $postDAO->addNew($data);
                    return $result;
                }
            }

            // Print form
            return $app['twig']->render('post_new.twig', array('form' => $form->createView()));
        })->bind('post_new');

/**
 * Get single post by ID
 */
$app->get('post/{post_id}', function($post_id) use ($app) {

            $postDAO = new PostDAO($app['db']);
            $authorDAO = new AuthorDAO($app['db']);

            // Fetch post
            $post = $postDAO->getById($post_id);

            // If there is no post return error template
            if (!$post->getPost_Id()) {
                return $app['twig']->render('error.twig');
            }

            // Fetch owner
            $author = $authorDAO->getById($post->getOwner_id());

            return $app['twig']->render('post_single.twig', array('post' => $post, 'author' => $author));
        })->bind('post_single');

/**
 * Deletes single post by ID
 */
$app->match('post/{post_id}/delete', function(Request $request, $post_id) use ($app) {

            $postDAO = new PostDAO($app['db']);
            $post = $postDAO->getById($post_id);

            $result = $postDAO->deleteById((int) $post_id);
            return $result;
        })->bind('post_delete');
?>
