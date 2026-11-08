<?php

namespace App\Controller;

use App\Entity\Forecasts;
use App\Form\ForecastsType;
use App\Repository\ForecastsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/forecasts')]
final class ForecastsController extends AbstractController
{
    #[Route(name: 'app_forecasts_index', methods: ['GET'])]
    #[IsGranted('ROLE_FORECASTS_INDEX')]
    public function index(ForecastsRepository $forecastsRepository): Response
    {
        return $this->render('forecasts/index.html.twig', [
            'forecasts' => $forecastsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_forecasts_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_FORECASTS_NEW')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $forecast = new Forecasts();
        $form = $this->createForm(ForecastsType::class, $forecast, [
            'validation_groups' => ['new'],
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($forecast);
            $entityManager->flush();

            return $this->redirectToRoute('app_forecasts_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('forecasts/new.html.twig', [
            'forecast' => $forecast,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_forecasts_show', methods: ['GET'])]
    #[IsGranted('ROLE_FORECASTS_SHOW')]
    public function show(Forecasts $forecast): Response
    {
        return $this->render('forecasts/show.html.twig', [
            'forecast' => $forecast,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_forecasts_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_FORECASTS_EDIT')]
    public function edit(Request $request, Forecasts $forecast, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ForecastsType::class, $forecast, [
            'validation_groups' => ['edit'],
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_forecasts_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('forecasts/edit.html.twig', [
            'forecast' => $forecast,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_forecasts_delete', methods: ['POST'])]
    #[IsGranted('ROLE_FORECASTS_DELETE')]
    public function delete(Request $request, Forecasts $forecast, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$forecast->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($forecast);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_forecasts_index', [], Response::HTTP_SEE_OTHER);
    }
}
