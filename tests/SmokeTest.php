<?php

// tests/ApplicationAvailabilityFunctionalTest.php
namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;


class SmokeTest extends WebTestCase
{

    /**
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful($url, $expectedStatus = 200)
    {
        $client = self::createClient();
        $client->request('GET', $url);

        $this->assertResponseStatusCodeSame($expectedStatus);
    }

    public function urlProvider()
    {
        yield "homepage" => ['/', Response::HTTP_OK];
        yield "register" => ['/register', Response::HTTP_OK];
        yield "login" => ['/login', Response::HTTP_OK];
        yield "logout" => ['/logout', Response::HTTP_FOUND]; //
        yield "confirm-account-with-token" => ['/confirm-account', Response::HTTP_NOT_FOUND]; //
        yield "forgot-pass" => ['/forgot-password', Response::HTTP_OK];
        yield "reset-pass-with-token" => ['/reset-password', Response::HTTP_NOT_FOUND]; //
        yield "delete-account" => ['/deleteAccount', Response::HTTP_FOUND]; //
        yield "member_area" => ['/member_area', Response::HTTP_FOUND]; //
        yield "trick-add" => ['/trick/add', Response::HTTP_FOUND];
        yield "show-trick-with-{id}" => ['/trick/show/1', Response::HTTP_OK];
        yield "trick-edit-with-{id}" => ['/trick/edit/1', Response::HTTP_FOUND];
        yield "-trick-delete-with-{id}" => ['/trick/delete/1', Response::HTTP_FOUND];
    }
}
