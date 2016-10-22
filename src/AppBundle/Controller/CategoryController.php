<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 17/10/16
 * Time: 11:11 PM
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller{

    private $user;

    /**
     * @Route("admin/categories", name="categories")
     */
    public function listAction(){
        return $this->render('admin/categories.html.twig', array(
            'title' => 'Categories List'
        ));
    }

    /**
     * @Route("admin/get-categories", name="get-categories")
     */
    public function getCategoriesAction(){
        return $this->datatable()->execute();
    }

    /**
     * @Route("admin/add-category", name="add-category")
     */
    public function addAction(Request $request){
        // Check if the request is AJAX
        if($request->isXmlHttpRequest()) {
            try{
                // Get the category name

                $categoryName = $request->get('category_name');

                if(empty(trim($categoryName)))
                    throw new Exception('No name was provided.');

                $category = new Category();

                $category->setName($categoryName);
                $category->setDateCreated(date('Y-m-d h:i:s'));
                $category->setDateUpdated(date('Y-m-d h:i:s'));

                $em = $this->getDoctrine()->getManager();

                $em->persist($category);

                $em->flush();

                // Send a success message
                $response = new Response(json_encode(array('success'=>'true','message'=>'New category added.')));
                $response->headers->set('Content-Type', 'application/json');

                return $response;
            }
            catch (Exception $e) {
                // Send a fail message
                // Send a success message
                $response = new Response(json_encode(array('success' => 'false', 'message' => $e->getMessage())));
                $response->headers->set('Content-Type', 'application/json');

                return $response;
            }
        }
    }

    /**
     * @Route("admin/update-category", name="update-category")
     */
    public function updateAction(Request $request){
        // Check if the request is AJAX
        if($request->isXmlHttpRequest()) {
            try{
                // Get the category name id

                $categoryName = $request->get('category_name');
                $categoryID = $request->get('category_id');

                if(empty(trim($categoryName)))
                    throw new Exception('No name was provided.');

                $category = $this->getDoctrine()->getRepository('AppBundle:Category')->find($categoryID);

                if(!$category)
                    throw new Exception('No category found.');

                $category->setName($categoryName);
                $category->setDateUpdated(date('Y-m-d h:i:s'));

                $em = $this->getDoctrine()->getManager();

                $em->persist($category);

                $em->flush();

                // Send a success message
                $response = new Response(json_encode(array('success'=>'true','message'=>'Category name updated.')));
                $response->headers->set('Content-Type', 'application/json');

                return $response;
            }
            catch (Exception $e) {
                // Send a fail message
                // Send a success message
                $response = new Response(json_encode(array('success' => 'false', 'message' => $e->getMessage())));
                $response->headers->set('Content-Type', 'application/json');

                return $response;
            }
        }

    }

    /**
     * @Route("admin/delete-category", name="delete-category")
     */
    public function deleteAction(Request $request){
        // Check if the request is AJAX
        if($request->isXmlHttpRequest()) {
            try{
                // Get the category id
                $categoryID = $request->get('category_id');

                $category = $this->getDoctrine()->getRepository('AppBundle:Category')->find($categoryID);

                if(!$category)
                    throw new Exception('No category found.');

                $em = $this->getDoctrine()->getManager();

                $em->remove($category);

                $em->flush();

                // Send a success message
                $response = new Response(json_encode(array('success'=>'true','message'=>'Category removed.')));
                $response->headers->set('Content-Type', 'application/json');

                return $response;
            }
            catch (Exception $e) {
                // Send a fail message
                // Send a success message
                $response = new Response(json_encode(array('success' => 'false', 'message' => $e->getMessage())));
                $response->headers->set('Content-Type', 'application/json');

                return $response;
            }
        }

    }

    private function datatable() {
        return $this->get('datatable')
            ->setEntity("AppBundle:Category", "category")
            ->setFields(
                array(
                    "_identifier_"          => 'category.id',
                    "Name"                  => 'category.name',
                    "Created At"            => 'category.date_created',
                    "Updated At"            => 'category.date_updated',
                    "Edit"                  => 'category.id')
            )
            ->setOrder("category.id", "desc");
    }

}