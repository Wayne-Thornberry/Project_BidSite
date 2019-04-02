<?php

namespace App\Controller;

use App\Entity\Bid;
use App\Entity\Book;
use App\Entity\Comment;
use App\Form\BidFormType;
use App\Form\BookFormType;
use App\Form\CommentFormType;
use App\Repository\BidRepository;
use App\Repository\BookRepository;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/book")
 */
class BookController extends AbstractController
{
    /**
     * @Route("/", name="book_index", methods={"GET"})
     */
    public function index(Request $request, BookRepository $bookRepository): Response
    {
        $bid = new Bid();
        $form = $this->createForm(BidFormType::class, $bid);
        $form->handleRequest($request);

        return $this->render('book/index.html.twig', [
            'books' => $bookRepository->findAll(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new", name="book_new", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function new(Request $request): Response
    {
        $user = $this->getUser();

        $book = new Book();
        $book->setUser($user);
        $form = $this->createForm(BookFormType::class, $book);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($book);
            $entityManager->flush();

            $bid = new Bid();
            $bid->setUser($user);
            $bid->setBook($book);
            $bid->setPrice($book->getPrice());

            $entityManager->persist($bid);
            $entityManager->flush();

            return $this->redirectToRoute('book_index');
        }

        return $this->render('book/new.html.twig', [
            'book' => $book,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="book_show", methods={"GET","POST"})
     */
    public function show(Request $request, CommentRepository $commentRepository, BidRepository $bidRepository, Book $book): Response
    {
        $user = $this->getUser();

        $bid = new Bid();
        $bidForm = $this->createForm(BidFormType::class, $bid);
        $bidForm->handleRequest($request);


        $comment = new Comment();
        $commentForm = $this->createForm(CommentFormType::class, $comment);
        $commentForm->handleRequest($request);

        if ($bidForm->isSubmitted() && $bidForm->isValid()) {
            if($bid->getPrice() > $book->getPrice()) {
                $bid->setUser($user);
                $bid->setBook($book);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($bid);
                $entityManager->flush();

                $book->setPrice($bid->getPrice());
                $this->getDoctrine()->getManager()->flush();
            }
            return $this->redirectToRoute('book_show', ["id" => $book->getId()]);
        }

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment->setUser($user);
            $comment->setBook($book);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('book_show', ["id" => $book->getId()]);
        }

        return $this->render('book/show.html.twig', [
            'book' => $book,
            'comments' => $commentRepository->findBy(array('Book' => $book)),
            'bids' => $bidRepository->findBy(array('Book' => $book), array('Price' => 'DESC')),
            'bidform' => $bidForm->createView(),
            'commentform' => $commentForm->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="book_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function edit(Request $request, Book $book): Response
    {
        $user = $this->getUser();
        if($user != $book->getUser() && !$this->isGranted('ROLE_ADMIN')) return $this->redirectToRoute("book_index");
        $form = $this->createForm(BookFormType::class, $book);
        $book->setUser($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('book_index', [
                'id' => $book->getId(),
            ]);
        }

        return $this->render('book/edit.html.twig', [
            'book' => $book,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="book_delete", methods={"DELETE"})
     * @IsGranted("ROLE_USER")
     */
    public function delete(Request $request, Book $book): Response
    {
        if ($this->isCsrfTokenValid('delete'.$book->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($book);
            $entityManager->flush();
        }

        return $this->redirectToRoute('book_index');
    }
}
