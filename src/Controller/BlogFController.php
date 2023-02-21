<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Form\Blog1Type;
use App\Repository\BlogRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/blogf")
 */
class BlogFController extends AbstractController
{
    /**
     * @Route("/", name="app_blog_f_index", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(BlogRepository $blogRepository): Response
    {
        return $this->render('blog_f/index.html.twig', [
            'blogs' => $blogRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_blog_f_new", methods={"GET", "POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request, BlogRepository $blogRepository): Response
    {
        $blog = new Blog();
        $form = $this->createForm(Blog1Type::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $blogRepository->add($blog);
            return $this->redirectToRoute('app_blog_f_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('blog_f/new.html.twig', [
            'blog' => $blog,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id_blog}", name="app_blog_f_show", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function show(Blog $blog): Response
    {
        return $this->render('blog_f/show.html.twig', [
            'blog' => $blog,
        ]);
    }

    /**
     * @Route("/{id_blog}/edit", name="app_blog_f_edit", methods={"GET", "POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Blog $blog, BlogRepository $blogRepository): Response
    {
        $form = $this->createForm(Blog1Type::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $blogRepository->add($blog);
            return $this->redirectToRoute('app_blog_f_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('blog_f/edit.html.twig', [
            'blog' => $blog,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id_blog}", name="app_blog_f_delete", methods={"POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Blog $blog, BlogRepository $blogRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$blog->getId_blog(), $request->request->get('_token'))) {
            $blogRepository->remove($blog);
        }

        return $this->redirectToRoute('app_blog_f_index', [], Response::HTTP_SEE_OTHER);
    }
}
