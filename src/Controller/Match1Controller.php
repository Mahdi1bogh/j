<?php

namespace App\Controller;

use App\Entity\Match1;
use App\Form\MailPlaceType;
use App\Form\Match1Type;
use App\Repository\Match1Repository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;


class Match1Controller extends Controller
{
    /**
     * @Route("/match", name="app_match1_index", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(Match1Repository $match1Repository ,Request $request): Response
    {

        $matchs = $match1Repository->findAll();
        $nbrPage=count($matchs);
        $matchs  = $this->get('knp_paginator')->paginate(
            $matchs,
            $request->query->get('page', 1),
        3);

        dump($nbrPage);
        if($nbrPage%3==0)
        {
            $nbrPage=$nbrPage/3; 
        }
        else{
            $nbrPage=intval($nbrPage/3)+1;

        }
        dump($nbrPage);
        return $this->render('match1/index.html.twig', [
            'matchs' => $matchs,
            'nbrPage'=>$nbrPage
        ]);
    }

    /**
     * @Route("/match/new", name="app_match1_new", methods={"GET", "POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request, Match1Repository $match1Repository): Response
    {
        $match1 = new Match1();
        $form = $this->createForm(Match1Type::class, $match1);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $match1Repository->add($match1);

            return $this->redirectToRoute('app_match1_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('match1/new.html.twig', [
            'match1' => $match1,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/match/{id_match1}", name="app_match1_show", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function show(Match1 $match1): Response
    {
        return $this->render('match1/show.html.twig', [
            'match1' => $match1,
        ]);
    }

    /**
     * @Route("/match/{id_match1}/edit", name="app_match1_edit", methods={"GET", "POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Match1 $match1, Match1Repository $match1Repository): Response
    {
        $form = $this->createForm(Match1Type::class, $match1);
//        $match1->setResultatMatch1('undefined');

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $match1Repository->add($match1);
            return $this->redirectToRoute('app_match1_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('match1/edit.html.twig', [
            'match1' => $match1,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/match/{id_match1}", name="app_match1_delete", methods={"POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Match1 $match1, Match1Repository $match1Repository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$match1->getIdMatch1(), $request->request->get('_token'))) {
            $match1Repository->remove($match1);
        }

        return $this->redirectToRoute('app_match1_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/generatePDF", name="generatePDF")
     */
    public function generatePDF(Request $request, Match1Repository $match1Repository): Response
    {
    //    dd($startDate,$endDate);
$matchs = $match1Repository->findAll();



$inputFileName = '../public/template.xlsx';

    /** Load $inputFileName to a Spreadsheet Object  **/
$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);


    /* @var $sheet \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet */
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle("Month Resume");



$line = 13;
foreach ($matchs as $mtch) {

$sheet->setCellValue('A'.$line, $mtch->getNomEquipe1());
$sheet->setCellValue('B'.$line, $mtch->getNomEquipe2());
$sheet->setCellValue('C'.$line, $mtch->getDateMatch1());
$sheet->setCellValue('D'.$line, $mtch->getResultatMatch1());

$line = $line+1;
}



// Create your Office 2007 Excel (XLSX Format)
$writer = new Xlsx($spreadsheet);

// Create a Temporary file in the system
$fileName = 'resume.xlsx';
$temp_file = tempnam(sys_get_temp_dir(), $fileName);

// Create the excel file in the tmp directory of the system
$writer->save($temp_file);

// Return the excel file as an attachment
return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);

}
    /**
     * @Route("/place", name="place")
     */
    public function place(Request $request, Match1Repository $rep, MailerInterface $mailer, TokenGeneratorInterface $tokenGeneratorInterface)
    {
        $form = $this->createForm(MailPlaceType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $data = $form->getData();
            $match1 = $rep->findOneBy(['Email_lieu'=>$data['email']]);
            $email = (new TemplatedEmail())
                ->from('primeleagueesports@gmail.com')
                ->to($match1->getEmailLieu())
                ->subject('Demande de collaboration ')
                ->htmlTemplate('match1/template.html.twig')
                ;

            $mailer->send($email);
            return $this->redirectToRoute('app_match1_index');

        }

        return $this->render('match1/MailPlace.html.twig' , ['emailForm' => $form->createView()]);
    }
}
