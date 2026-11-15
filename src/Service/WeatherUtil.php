<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Forecasts;
use App\Entity\Location;
use App\Repository\ForecastsRepository;
use App\Repository\LocationRepository;

class WeatherUtil
{
    public function __construct(
        private readonly LocationRepository $locationRepository,
        private readonly ForecastsRepository $forecastsRepository,
    ) {
    }

    /**
     * @return Forecasts[]
     */
    public function getWeatherForLocation(Location $location): array
    {
        $measurements = $this->forecastsRepository->findByLocation($location);

        return $measurements;
    }

    /**
     * @return Forecasts[]
     */
    public function getWeatherForCountryAndCity(string $countryCode, string $city): array
    {
        $location = $this->locationRepository->findOneBy([
            'country' => $countryCode,
            'city' => $city,
        ]);

        if ($location === null) {
            return [];
        }

        $measurements = $this->getWeatherForLocation($location);

        return $measurements;
    }
}
