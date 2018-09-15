<?php
// src/Controller/DefaultController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use SphinxClient;

class DefaultController extends AbstractController
{

    //Search for Keyword or Get All based on search
    public function index()
    {

      $sphinx = new SphinxClient();
      $sphinx->SetServer('localhost', 9312);
      $sphinx->SetArrayResult(true);
      
      $searchQuery = "Keyboard";
      $searchResults = $sphinx->Query( $searchQuery, 'ShowNewArticles' );


      //Error on Sphinx Side
      if ($error = $sphinx->getLastError()) {
        return new JsonResponse($error,400);
      }
      
      $serializedEntity = $this->container->get('serializer')->serialize($searchResults, 'json');
      return new JsonResponse($searchResults,200);

    }
    
    
    public function store()
    {
        $entityManager = $this->getDoctrine()->getManager();
        
        $product = new Article();
        $product->setText('Keyboard');
        $product->setUserid(mt_rand(1,100));
        $product->setDate(\DateTime::createFromFormat('Y-m-d', "2018-09-09"));
        $product->setNOfViews(mt_rand(1,100));
        $product->setNOfComments(mt_rand(1,100));

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($product);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Saved new product with id '.$product->getId());
      
    }
    
    
    public function get_list()
    {
    
        $sphinx = new SphinxClient();
        $sphinx->SetServer('localhost', 9312);
        $sphinx->SetArrayResult(true);
        
        $searchQuery = "";
        $searchResults = $sphinx->Query( $searchQuery, 'ShowNewArticles' );
        
      
        //	key = rowPrimaryKey, not number
        $arr_arr__MySQL_row_result	=$searchResults['matches'];
        
        $arr_arr__MySQL_grouped_by_user	=[];
        
        $int__quantity	=count($arr_arr__MySQL_row_result);
        
        //	group by user
        for($int__index =0; $int__index <$int__quantity; $int__index++)
        {
          $arr_arr__MySQL_grouped_by_user[ $arr_arr__MySQL_row_result[$int__index]['attrs']['userid'] ][]	=$arr_arr__MySQL_row_result[$int__index];
        }
        
        $arr_arr__highest_row_id_per_user	=[];
        //	search highest per user
        foreach($arr_arr__MySQL_grouped_by_user as $var__value)
        {		
          foreach($var__value as $var__value_)
          {
          //	get only first - highest
            $arr_arr__highest_row_id_per_user[ $var__value_['attrs']['userid'] ]	=$var__value_['id'];
            break;
          }
        }
        
        $arr_arr__result	=[];
        
        foreach($arr_arr__highest_row_id_per_user as $row_id)
        {
          $arr_arr__result[$row_id]	=$arr_arr__MySQL_row_result[$row_id];
          unset($arr_arr__MySQL_row_result[$row_id]);
        }
        
        $arr_arr__result	=$arr_arr__result +$arr_arr__MySQL_row_result;


      //Error on Sphinx Side
      if ($error = $sphinx->getLastError()) {
        return new JsonResponse($error,400);
      }
      
      $serializedEntity = $this->container->get('serializer')->serialize($arr_arr__result, 'json');
      return new JsonResponse($arr_arr__result,200);
      
    }
    
}