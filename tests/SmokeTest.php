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
        yield ['/register'];
        yield ['/login'];
        yield ['/logout'];
        yield ['/confirm-account'];
        yield ['/forgot-password'];
        yield ['/reset-password'];
        yield ['/deleteAccount'];
        yield ['/member_area'];
        yield ['/trick/show'];
        yield ['/trick/edit'];
        yield ['trick/delete'];
    }
}
