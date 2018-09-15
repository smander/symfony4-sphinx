<?php
// src/Controller/DefaultController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
#se Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use App\Entity\Article;

class ArticleController extends AbstractController
{
    public function index()
    {
    
        $entityManager = $this->getDoctrine()->getManager();

        $product = new Article();
        $product->setText('Keyboard');
        $product->setUserid(1999);
        $product->setDate(\DateTime::createFromFormat('Y-m-d', "2018-09-09"));

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($product);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Saved new product with id '.$product->getId());
    }
    
   
}