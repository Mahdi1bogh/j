<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\ApprouverReclamationType;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/reclamation")
 */
class AdminReclamtaionController extends AbstractController
{
    private UserRepository $userRepository;
    private ReclamationRepository $reclamationRepository;

    public function __construct(UserRepository $userRepository, ReclamationRepository $reclamationRepository)
    {

        $this->userRepository = $userRepository;
        $this->reclamationRepository = $reclamationRepository;
    }


    /**
     * @Route("/", name="app_admin_reclamtaion_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $reclamations = $entityManager
            ->getRepository(Reclamation::class)
            ->findAll();

        return $this->render('admin_reclamtaion/index.html.twig', [
            'reclamations' => $reclamations,
        ]);
    }

    /**
     * @Route("/new", name="app_admin_reclamtaion_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reclamation->setDateReclam();
            $entityManager->persist($reclamation);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_reclamtaion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_reclamtaion/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idReclam}", name="app_admin_reclamtaion_show", methods={"GET"})
     */
    public function show(Reclamation $reclamation): Response
    {
        return $this->render('admin_reclamtaion/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    /**
     * @Route("/{idReclam}/edit", name="app_admin_reclamtaion_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_reclamtaion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_reclamtaion/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idReclam}/approuver", name="app_admin_reclamtaion_approve", methods={"GET", "POST"})
     */
    public function approuver(MailerInterface $mailer, Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ApprouverReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($reclamation->getEtatReclam()=='refuser'){
                $user =  $this->userRepository->findOneBy(['id_user'=>$reclamation->getIdUser()->getIdUser()]);
               // dd($user);
                $email = (new TemplatedEmail())
                    ->from('primeleagueesports@gmail.com')
                    ->to($user->getEmailUser())
                    ->subject('Welcome To PLE sports')
                    ->htmlTemplate('user_email/refuser.html.twig')
                    ->context([
                        'user'=>$user,
                        'reclamation' =>$reclamation
                    ])
                ;
                $mailer->send($email);

                $this->addFlash('success', ''.$reclamation->getIdReclam());
                $entityManager->remove($reclamation);
                $entityManager->flush();
                return $this->redirectToRoute('app_admin_reclamtaion_index', [], Response::HTTP_SEE_OTHER);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_reclamtaion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_reclamtaion/approuver.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/pdf/{idReclam}", name="app_pdf_reclamation")
     */
    public function listj(Request $request, Reclamation $reclamation): Response
    {   $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
//        $joueurs = $this->getDoctrine()->getManager()->getRepository(Reclamation::class)->findBy(['idReclam']);
        $rec = $this->reclamationRepository->findReclamationWithUser($reclamation->getIdReclam());
//        dd($rec);
        // Retrieve the HTML generated in our twig file
        $html = $this->render('admin_pdf/reclamation.html.twig',['rec'=>$rec]);

        // Load HTML to Dompdf
        $html .= '<link type="text/css" href="C:/pdf.css" rel="stylesheet" />';
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => false
        ]);



    }

    /**
     * @Route("/{idReclam}", name="app_admin_reclamtaion_delete", methods={"POST"})
     */
    public function delete(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getIdReclam(), $request->request->get('_token'))) {
            $entityManager->remove($reclamation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_reclamtaion_index', [], Response::HTTP_SEE_OTHER);
    }
}
