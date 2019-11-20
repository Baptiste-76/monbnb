<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController {

    /**
     * Affiche la page d'accueil
     * 
     * @Route("/", name="homepage")
     *
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
