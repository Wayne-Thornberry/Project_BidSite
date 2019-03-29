<?php

namespace App\Controller;

use App\Entity\Bid;
use App\Entity\Book;
use App\Form\BidType;
use App\Form\BookType;
use App\Repository\BidRepository;
use App\Repository\BookRepository;
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
        $form = $this->createForm(BidType::class, $bid);
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
        $book = new Book();
        $user = $this->getUser();
        $book->setSubmitterName($user->getUsername());
        $book->setSubmitterId($user->getId());
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($book);
            $entityManager->flush();

            $bid = new Bid();
            $bid->setUserId($user->getId());
            $bid->setBookId($book->getId());
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
    public function show(Request $request, BidRepository $bidRepository, Book $book): Response
    {
        $bid = new Bid();
        $user = $this->getUser();
        $form = $this->createForm(BidType::class, $bid);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($bid->getPrice() > $book->getPrice()) {
                $bid->setUserId($user->getId());
                $bid->setBookId($book->getId());

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($bid);
                $entityManager->flush();

                if ($bid->getPrice() > $book->getPrice()) {
                    $book->setPrice($bid->getPrice());
                    $this->getDoctrine()->getManager()->flush();
                }
            }
            return $this->redirectToRoute('book_show', ["id" => $book->getId()]);
        }

        return $this->render('book/index.html.twig', [
            'book' => $book,
            'bid' => $bid,
            'bids' => $bidRepository->findBy(array('BookId' => $book->getId()), array('Price' => 'DESC')),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="book_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function edit(Request $request, Book $book): Response
    {
        $user = $this->getUser();
        if($user->getId() != $book->getSubmitterId()) return $this->redirectToRoute("book_index");
        $form = $this->createForm(BookType::class, $book);
        $book->setSubmitterName($user->getUsername());
        $book->setSubmitterId($user->getId());
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
