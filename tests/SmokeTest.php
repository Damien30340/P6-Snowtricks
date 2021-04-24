<?php

// tests/ApplicationAvailabilityFunctionalTest.php
namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SmokeTest extends WebTestCase
{
    /**
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful($url)
    {
        $client = self::createClient();
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function urlProvider()
    {
        yield ['/'];
        yield ['/nouvelle-figure'];
        yield ['/connexion'];
        yield ['/inscription'];
        yield ['/confirmation-compte'];
        yield ['/espace-membre'];
        yield ['/mdp-oublie'];
        yield ['/mdp-reinitialise'];

        // ...
    }
}
