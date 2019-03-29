<?php

namespace App\Controller;

use App\Entity\Bid;
use App\Form\AdminBidType;
use App\Repository\BidRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("admin/bid")
 * @IsGranted("ROLE_ADMIN")
 */
class AdminBidController extends AbstractController
{
    /**
     * @Route("/", name="admin_bid_index", methods={"GET"})
     */
    public function index(BidRepository $bidRepository): Response
    {
        return $this->render('admin/bid/index.html.twig', [
            'bids' => $bidRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_bid_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $bid = new Bid();
        $form = $this->createForm(AdminBidType::class, $bid);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($bid);
            $entityManager->flush();

            return $this->redirectToRoute('bid_index');
        }

        return $this->render('admin/bid/new.html.twig', [
            'bid' => $bid,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_bid_show", methods={"GET"})
     */
    public function show(Bid $bid): Response
    {
        return $this->render('admin/bid/show.html.twig', [
            'bid' => $bid,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_bid_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Bid $bid): Response
    {
        $form = $this->createForm(AdminBidType::class, $bid);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_bid_index', [
                'id' => $bid->getId(),
            ]);
        }

        return $this->render('admin/bid/edit.html.twig', [
            'bid' => $bid,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_bid_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Bid $bid): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bid->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($bid);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_bid_index');
    }
}
