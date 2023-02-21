<?php

namespace App\Controller;

use App\Entity\Equipe;
use App\Entity\Joueur;
use App\Repository\EquipeRepository;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/client/equipe")
 */
class ClientEquipeController extends AbstractController
{
    /**
     * @Route("/{page<\d+>}", name="app_client_equipe")
     */
    public function index(EquipeRepository $equipeRepository, int $page = 1): Response
    {
        $equipes = $equipeRepository->findJoueursByEquipe();
//        dd($equipes);
        $pagerfanta = new Pagerfanta(new QueryAdapter($equipes));
        $pagerfanta->setMaxPerPage(1);
        $pagerfanta->setCurrentPage($page);
        return $this->render('client_equipe/index.html.twig', [
            'controller_name' => 'ClientEquipeController',
            'equipes' => $pagerfanta
        ]);
    }
    /**
     * @Route("/joueur/{id}/{ide}", name="app_client_equipe_joueur")
     */
    public function profile(EquipeRepository $equipeRepository, Joueur $joueur, int $ide): Response
    {
        $equipe = $equipeRepository->findOneBy(['idEquipe'=>$ide]);
        return $this->render('client_equipe/profile.html.twig', [
            'controller_name' => 'ClientEquipeController',
            'joueur' => $joueur,
            'equipe' => $equipe
        ]);
    }
}
