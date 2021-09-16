<?php

namespace App\Controller;

use App\Entity\Pharmacy;
use App\Form\PharmacyType;
use App\Repository\PharmacyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/pharmacy")
 */
class PharmacyController extends AbstractController
{
    /**
     * @Route("/", name="pharmacy_index", methods={"GET"})
     */
    public function index(PharmacyRepository $pharmacyRepository): Response
    {
        return $this->render('pharmacy/index.html.twig', [
            'pharmacies' => $pharmacyRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="pharmacy_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $pharmacy = new Pharmacy();
        $form = $this->createForm(PharmacyType::class, $pharmacy);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($pharmacy);
            $entityManager->flush();

            return $this->redirectToRoute('pharmacy_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pharmacy/new.html.twig', [
            'pharmacy' => $pharmacy,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="pharmacy_show", methods={"GET"})
     */
    public function show(Pharmacy $pharmacy): Response
    {
        return $this->render('pharmacy/show.html.twig', [
            'pharmacy' => $pharmacy,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="pharmacy_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Pharmacy $pharmacy): Response
    {
        $form = $this->createForm(PharmacyType::class, $pharmacy);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('pharmacy_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pharmacy/edit.html.twig', [
            'pharmacy' => $pharmacy,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="pharmacy_delete", methods={"POST"})
     */
    public function delete(Request $request, Pharmacy $pharmacy): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pharmacy->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($pharmacy);
            $entityManager->flush();
        }

        return $this->redirectToRoute('pharmacy_index', [], Response::HTTP_SEE_OTHER);
    }
}
