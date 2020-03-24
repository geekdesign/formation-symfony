<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    /**
     * @Route("/hello", name="hello_base")
     * @Route("/hello/{prenom}", name="hello_prenom")
     * @Route("/hello/{prenom}/age/{age}", name="hello_age")
     * Montre la page qui dit bonjour
     * $prenom = "Anonyme", $age = 0
     * @return void 
     */
    public function hello($prenom, $age)
    {
        return $this->render('home/hello.html.twig', [
            'prenom' => $prenom,
            'age' => $age,
        ]);
    }

    /**
     * @Route("/", name="homepage")
     */
    public function index()
    {
        $prenoms = ["P-A" => 36, "Clara" => 12, "Patricia" => 44, "Jules" => 2];
        
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'titre' => 'Mon super titre',
            'age' => 19,
            'tableau' => $prenoms,
        ]);
    }
}
