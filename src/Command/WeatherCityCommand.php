<?php

namespace App\Command;

use App\Service\WeatherUtil;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'weather:city',
    description: 'Pobiera prognozę pogody dla kodu kraju i nazwy miasta',
)]
class WeatherCityCommand
{
    public function __construct(
        private readonly WeatherUtil $weatherUtil,
    ) {
    }

    public function __invoke(
        SymfonyStyle $io,
        #[Argument('Country code [eg. PL]')] string $countryCode,
        #[Argument('City name [eg. Szczecin]')] string $cityName,
    ): int {
        $measurements = $this->weatherUtil->getWeatherForCountryAndCity($countryCode, $cityName);
        $io->writeln(sprintf('Location: %s', $cityName));
        $io->table(['Date', 'Temperature', 'Humidity', 'Wind', 'Pressure'], array_map(fn($m) => [
            $m->getDate()->format('Y-m-d'),
            $m->getTemperature(),
            $m->getHumidity(),
            $m->getWind(),
            $m->getPressure(),
        ], $measurements));

        return Command::SUCCESS;
    }
}
