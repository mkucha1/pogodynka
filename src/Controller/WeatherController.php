<?php

namespace App\Controller;

use App\Entity\Location;
use App\Repository\ForecastsRepository;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class WeatherController extends AbstractController
{
    #[Route('/weather/{city}/{country}', name: 'app_weather', requirements: ['id' => '\d+'])]
    public function city(#[MapEntity(mapping: ['city' => 'city', 'country' => 'country'])]
                         Location $location, ForecastsRepository $repository): Response
    {
        $forecasts = $repository->findByLocation($location);

        return $this->render('weather/city.html.twig', [
            'location' => $location,
            'forecasts' => $forecasts,
        ]);
    }
}
