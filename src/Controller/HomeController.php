<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(HttpClientInterface $client): Response
    {
        //Appel à l'api https://fakestoreapi.com/products
        $response = $client->request('GET', 'https://dummyjson.com/products');

        //Faire un try catch 
        try{
            $arrayResponse = json_decode($response->getContent());
            dump($arrayResponse->products);
        } catch (Exception $e) {
            echo 'Exception: ',  $e->getMessage(), "\n";
        }

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'arrayResponse' => $arrayResponse->products
        ]);
    }
}
