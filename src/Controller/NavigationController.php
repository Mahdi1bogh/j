<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NavigationController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('navigation/index.html.twig');
    }

    /**
     * @Route("/membre", name="membre")
     * @IsGranted("ROLE_USER")
     */
    public function membre()
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        return $this->render('navigation/membre.html.twig');
    }

    /**
     * @Route("/admin", name="admin")
     * @IsGranted("ROLE_ADMIN")
     */
    public function admin()
    {
        return $this->render('navigation/admin.html.twig');
    }
}
