<?php

namespace App\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\ApplicationTester;

class WeatherCommandsTest extends KernelTestCase
{
    public function testWeatherCommandsShowAllForecastFields(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        /** @var EntityManagerInterface $em */
        $em = $container->get(EntityManagerInterface::class);
        $schemaTool = new SchemaTool($em);
        $metadata = $em->getMetadataFactory()->getAllMetadata();
        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);

        $location = new \App\Entity\Location();
        $location->setCity('Szczecin')->setCountry('PL')
            ->setLatitude('53.4289400')->setLongitude('14.5530200');
        $em->persist($location);
        $forecast = new \App\Entity\Forecasts();
        $forecast->setLocation($location)->setDate(new \DateTime('2027-01-15'))
            ->setTemperature('8')->setHumidity(81)->setWind(5)->setPressure(1008);
        $em->persist($forecast);
        $em->flush();

        $app = new Application(self::$kernel);
        $app->setAutoExit(false);
        $tester = new ApplicationTester($app);
        $tester->run(['command' => 'weather:location', 'id' => (string) $location->getId()]);
        $display = $tester->getDisplay();
        foreach (['Date', 'Temperature', 'Humidity', 'Wind', 'Pressure', '2027-01-15', '81', '1008'] as $needle) {
            self::assertStringContainsString($needle, $display);
        }

        $tester->run(['command' => 'weather:city', 'country-code' => 'PL', 'city-name' => 'Szczecin']);
        $display = $tester->getDisplay();
        foreach (['Temperature', 'Humidity', 'Wind', 'Pressure'] as $needle) {
            self::assertStringContainsString($needle, $display);
        }
    }
}
