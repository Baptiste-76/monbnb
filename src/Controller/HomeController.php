<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController {

    
    /**
     * @Route("/bonjour/{prenom}/age/{age}", name = "hello")
     * @Route("/salut", name = "hello_base")
     * @Route("/bonjour/{prenom}", name = "hello_prenom")
     * 
     * Montre la page qui dit Bonjour
     * 
     * @return void
     */
    public function hello($prenom = "Anonyme", $age = 0) {

        return $this->render(
            'hello.html.twig', 
            [
                'prenom' => $prenom,
                'age' => $age
            ]
            );
    }

    /**
     * @Route("/", name = "homepage")
     * Affiche la page d'accueil
     * @return twig
     */
    public function home() {
        $personnes = ["Baptiste" => 33, "Elise" => 34, "Jade" => 2];

        return $this->render('home.html.twig',[
            'tableau' => $personnes,
        ]);
    }
}

?>
