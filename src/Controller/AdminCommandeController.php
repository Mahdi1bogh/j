<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Lignecommande;
use App\Entity\Produits;
use App\Form\LignecommandeType;
use App\Repository\LigneCommandeRepository;
use App\Repository\ProduitRepository;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use Knp\Component\Pager\PaginatorInterface;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Gregwar\CaptchaBundle\Type\CaptchaType;
/**
 * @Route("/admin/commande")
 */
class AdminCommandeController extends AbstractController
{
    /**
     * @Route("/", name="app_admin_commande_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $commandes = $entityManager
            ->getRepository(Commande::class)
            ->findAll();

        return $this->render('admin_commande/index.html.twig', [
            'commandes' => $commandes,
        ]);
    }

    /**
     * @Route("/trieadresse", name="trieradresse_admin")
     */

    public function Triadresse(Request $request, CommandeRepository $repository)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT c FROM App\Entity\Commande c 
            ORDER BY c.adresse'
        );
        $repository = $this->getDoctrine()->getRepository(Commande::class);
        $commande = $query->getResult();
        return $this->render('admin_commande/index.html.twig',
            array('commandes' => $commande));
    }

    /**
     * @Route("/triid", name="triid")
     */
    public function Triid(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->createQuery(
            'SELECT c FROM App\Entity\Commande c 
            ORDER BY c.dateCommande'
        );


        $rep = $query->getResult();

        return $this->render('admin_commande/index.html.twig',
            array('commandes' => $rep));

    }


    /**
     *
     * @Route("/statistique", name="app_admin_commande_stat")
     */
    public function Stat()
    {
        $repository = $this->getDoctrine()->getRepository(commande::class);
        $commande = $repository->findAll();


        $em = $this->getDoctrine()->getManager();
        $compteurEnCours = 0;
        $compteurEnAttente = 0;
        $compteurLivree = 0;

        foreach ($commande as $commande) {
            if ($commande->getEtat() == "En cours")  :

                $compteurEnCours += 1;
            elseif ($commande->getEtat() == "En attente"):

                $compteurEnAttente += 1;

            else  : $compteurLivree += 1;

            endif;

        }
        $pieChart = new PieChart();
        $pieChart->getData()->setArrayToDataTable(

            [['Etat Commande', 'nombre'],

                ['Commande en cours', $compteurEnCours],
                ['Commande en attente', $compteurEnAttente],
                ['Commande Livrée', $compteurLivree]
            ]
        );
        $pieChart->getOptions()->setTitle('Les états des commandes');
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(900);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);

        return $this->render('admin_commande/commande_stat.html.twig', array('piechart' => $pieChart));
    }


    /**
     * @Route("/new", name="app_admin_commande_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commande = new Commande();

        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($commande);
            $entityManager->flush();
            $this->addFlash(                'info',
                'Added successfully!'
            );

            return $this->redirectToRoute('app_admin_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_commande/new.html.twig', [
            'commande' => $commande,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/listcommandes", name="commandesliste", methods={"GET", "POST"})
     */
    public function new_list(CommandeRepository $commandeRepository,Request $request): Response
    {

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $commandes =  $commandeRepository->findAll();
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('admin_commande/list_commande.html.twig', [
            'commandes' => $commandes,
            'title' => "Welcome to our PDF Test"
        ]);


        $dompdf->loadHtml($html);


        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("listeCommande.pdf", [
            "Attachment" => false

        ]);
        return new Response("The PDF file has been succesfully generated !");
    }



// http://127.0.0.1:8000/admin/commande/all
    /**
     * @Route("/all/{page<\d+>}", name="app_admin_commandes_show_all")
     */
    public function showAll(CommandeRepository $commandeRepository, UserRepository $userRepository, int $page = 1): Response
    {
//        $user = $userRepository->findOneBy(['emailUser'=>'molka.hammed@esprit.tn']);
//        dd($user);
//        $commandes = $commandeRepository->findCommandeByUsers();
        $commandes = $commandeRepository->findCommandeByUsers();
//        dd($commandes);
        $pagerfanta = new Pagerfanta(new QueryAdapter($commandes));
        $pagerfanta->setMaxPerPage(4);
        $pagerfanta->setCurrentPage($page);
        return $this->render('admin_commande/showAllCommande.html.twig', [
            'commandes' => $pagerfanta
        ]);
    }

    /**
     * @Route("/{idCommande}", name="app_admin_commande_show", methods={"GET"})
     */
    public function show(Commande $commande): Response
    {
        return $this->render('admin_commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    /**
     * @Route("/{idCommande}/edit", name="app_admin_commande_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_commande/edit.html.twig', [
            'commande' => $commande,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idCommande}", name="app_admin_commande_delete", methods={"POST"})
     */
    public function delete(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commande->getIdCommande(), $request->request->get('_token'))) {
            $lignecommandes = $this->getDoctrine()->getRepository(Lignecommande::class)->findBy(['idCommande'=>$commande->getIdCommande()]);
            foreach ($lignecommandes as $l){
                $entityManager->remove($l);
            }
            $entityManager->remove($commande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_commande_index', [], Response::HTTP_SEE_OTHER);
    }


}
