<?php

namespace App\Controller;

use App\Entity\Match1;
use App\Form\Match11Type;
use App\Repository\Match1Repository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class Match1FrontController extends AbstractController
{
    /**
     * @Route("/matchF", name="app_match1_front_index", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function index(Match1Repository $match1Repository): Response
    {
        return $this->render('match1_front/index.html.twig', [
            'match1s' => $match1Repository->findAll(),
        ]);
    }



    /**
     * @Route("/matchF/{id_match1}", name="app_match1_front_show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function show(Match1 $match1): Response
    {
        return $this->render('match1_front/show.html.twig', [
            'match1' => $match1,
        ]);
    }




}
