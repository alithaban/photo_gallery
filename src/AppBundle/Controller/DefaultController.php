<?php

namespace AppBundle\Controller;

use AppBundle\Utils\phpFlickr;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // Get all the categories
        $repository = $this->getDoctrine()->getRepository('AppBundle:Category');

        $categories = $repository->findAll();

        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/get-photos", name="get-photos")
     */
    public function getPhotosAction(Request $request){
        // Check if the request is AJAX
        if($request->isXmlHttpRequest()) {
            // Access flickr
            $flickr = $this->get('app.flickr');

            $photos = $flickr->photos_search(array('tags'=>$request->get('category_name')));

            $response = new Response(json_encode($photos));
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
    }

    /**
     * @Route("/get-photo", name="get-photo")
     */
    public function getPhotoAction(Request $request){
        // Check if the request is AJAX
        if($request->isXmlHttpRequest()) {
            // Access flickr
            $flickr = $this->get('app.flickr');

            $photos = $flickr->photos_getInfo($request->get('photo_id'));

            $response = new Response(json_encode($photos));
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
    }
}
