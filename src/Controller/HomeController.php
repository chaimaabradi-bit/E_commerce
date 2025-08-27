<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Product;
use Psr\Log\LoggerInterface;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(HttpClientInterface $client,EntityManagerInterface $em,LoggerInterface $logger): Response
    {
        //Appel à l'api https://fakestoreapi.com/products
        $response = $client->request('GET', 'https://dummyjson.com/products');
         $logger->info('Test de log Symfony en mode dev');
        try{
            $arrayResponse = json_decode($response->getContent());
            //Insertion en base de donnée
            foreach($arrayResponse->products as $objProduct) {
                $product = new Product();
                $product->setTitle($objProduct->title);
                $product->setPrice($objProduct->price);
                $product->setDescription($objProduct->description);
                $product->setCategory($objProduct->category);
                $product->setImage($objProduct->images[0] ?? null);
                $em->persist($product);
            }
            $em->flush();
       } catch (\Exception $e) {
        echo "Erreur : " . $e->getMessage();
        }

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'arrayResponse' => $arrayResponse->products
        ]);
    }
}
