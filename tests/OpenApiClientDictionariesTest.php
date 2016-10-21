<?php
namespace naspersclassifieds\realestate\openapi\tests;


use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Response;
use naspersclassifieds\realestate\openapi\tests\utils\Fixtures;

class OpenApiClientDictionariesTest extends OpenApiTestCase
{

    public function testShouldRetrieveRootCategories(){

        $this->mocker->addResponse(new Response(200, [], Fixtures::load('categories.response.json')));

        $categories = $this->openApiClient->getDictionaries()->getCategories();

        $this->assertEquals(3, count($categories));
        $this->assertRequest('categories');
        $this->assertEquals("Wynajem", $categories[1]->names->pl);
    }

    public function testShouldRetrieveOneCategory(){

        $this->mocker->addResponse(new Response(200, [], Fixtures::load('categories.101.response.json')));

        $category = $this->openApiClient->getDictionaries()->getCategory(101);

        $this->assertRequest('categories/101');
        $this->assertEquals("Mieszkania", $category->names->pl);
        $this->assertEquals("cena", $category->parameters[0]->labels->pl);
    }

    public function testShouldNotRetrieveNonExistingCategory(){

        $this->mocker->addResponse(new Response(404, []));

        try {
            $this->openApiClient->getDictionaries()->getCategory(999);
            $this->fail();
        } catch (ClientException $e) {
            $this->assertEquals(404, $e->getCode());
        }
        $this->assertRequest('categories/999');
    }

    public function testShouldRetrieveCities(){

        $this->mocker->addResponse(new Response(200, [], Fixtures::load('cities.response.json')));

        $cities = $this->openApiClient->getDictionaries()->getCities();

        $this->assertEquals(81, count($cities));
        $this->assertRequest('cities');
        $this->assertEquals("Poznań", $cities[1]->name);
    }

    public function testShouldRetrieveOneCity(){

        $this->mocker->addResponse(new Response(200, [], Fixtures::load('cities.1.response.json')));

        $city = $this->openApiClient->getDictionaries()->getCity(1);

        $this->assertRequest('cities/1');
        $this->assertEquals("Poznań", $city->name);
    }

    public function testShouldRetrieveRegions(){

        $this->mocker->addResponse(new Response(200, [], Fixtures::load('regions.response.json')));

        $regions = $this->openApiClient->getDictionaries()->getRegions();

        $this->assertEquals(16, count($regions));

        $this->assertRequest('regions');
        $this->assertEquals("kujawsko-pomorskie", $regions[1]->name->pl);
    }

    public function testShouldRetrieveOneRegion(){

        $this->mocker->addResponse(new Response(200, [], Fixtures::load('regions.1.response.json')));

        $region = $this->openApiClient->getDictionaries()->getRegion(1);

        $this->assertRequest('regions/1');
        $this->assertEquals("dolnośląskie", $region->name->pl);
    }

    public function testShouldRetrieveSubregions(){

        $this->mocker->addResponse(new Response(200, [], Fixtures::load('subregions.response.json')));

        $subregions = $this->openApiClient->getDictionaries()->getSubRegions();

        $this->assertEquals(380, count($subregions));

        $this->assertRequest('subregions');
        $this->assertEquals("bartoszycki", $subregions[2]->name->pl);
    }

    public function testShouldRetrieveOneSubrregion(){

        $this->mocker->addResponse(new Response(200, [], Fixtures::load('subregions.1.response.json')));

        $subregion = $this->openApiClient->getDictionaries()->getSubregion(1);

        $this->assertRequest('subregions/1');
        $this->assertEquals("chodzieski", $subregion->name->pl);
    }
}