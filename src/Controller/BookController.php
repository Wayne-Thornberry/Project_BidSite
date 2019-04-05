<?php

namespace App\Controller;

use App\Entity\Bid;
use App\Entity\Book;
use App\Entity\Comment;
use App\Form\BidFormType;
use App\Form\BookFormType;
use App\Form\CommentFormType;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;

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
        return $this->render('book/index.html.twig', [
            'books' => $bookRepository->findAll(),
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
        $bid = new Bid();

        $bookForm = $this->createForm(BookFormType::class, $book);
        $bookForm->handleRequest($request);

        $bidForm = $this->createForm(BidFormType::class, $bid);
        $bidForm->handleRequest($request);

        if ($bookForm->isSubmitted() && $bookForm->isValid()) {

            $book->setUser($user);
            $book->setDateSubmitted(new \DateTime());
            $book->setIsOpen(true);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($book);
            $entityManager->flush();

            $bid->setUser($user);
            $bid->setBook($book);
            $bid->setPrice($book->getStartingBid());

            $entityManager->persist($bid);
            $entityManager->flush();

            return $this->redirectToRoute('book_show', ["id" => $book->getId()]);
        }

        return $this->render('book/new.html.twig', [
            'book' => $book,
            'bookform' => $bookForm->createView(),
            'bidform' => $bidForm->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="book_show", methods={"GET","POST"})
     */
    public function show(Request $request, Book $book): Response
    {
        $user = $this->getUser();

        $bid = new Bid();
        $bidForm = $this->createForm(BidFormType::class, $bid);
        $bidForm->handleRequest($request);

        $comment = new Comment();
        $commentForm = $this->createForm(CommentFormType::class, $comment);
        $commentForm->handleRequest($request);

        if ($bidForm->isSubmitted() && $bidForm->isValid()) {
            if($bid->getPrice() > $book->getBids()->last()->getPrice()) {
                $bid->setUser($user);
                $bid->setBook($book);
                $bid->setDatePosted(new \DateTime());

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($bid);
                $entityManager->flush();

                $this->getDoctrine()->getManager()->flush();
            }
            return $this->redirectToRoute('book_show', ["id" => $book->getId()]);
        }

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment->setUser($user);
            $comment->setBook($book);
            $comment->setDatePosted(new \DateTime());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('book_show', ["id" => $book->getId()]);
        }

        return $this->render('book/show.html.twig', [
            'book' => $book,
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
        $bookForm = $this->createForm(BookFormType::class, $book);
        $book->setUser($user);
        $bookForm->handleRequest($request);

        if ($bookForm->isSubmitted() && $bookForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('book_index', [
                'id' => $book->getId(),
            ]);
        }

        return $this->render('book/edit.html.twig', [
            'book' => $book,
            'bookform' => $bookForm->createView(),
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

    /**
     * @Route("/{bookid}/edit/comment/delete/{id}", name="book_comment_delete", methods={"DELETE"})
     * @IsGranted("ROLE_USER")
     */
    public function deleteComment(Request $request, Comment $comment): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($comment);
            $entityManager->flush();
        }
        return $this->redirectToRoute('book_edit', ["id"=>$request->get('bookid')]);
    }

    /**
     * @Route("/{bookid}/edit/bid/delete/{id}", name="book_bid_delete", methods={"DELETE"})
     * @IsGranted("ROLE_USER")
     */
    public function deleteBid(Request $request, Bid $bid): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bid->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($bid);
            $entityManager->flush();
        }
        return $this->redirectToRoute('book_edit', ["id"=>$request->get('bookid')]);
    }
}
