<?php
namespace naspersclassifieds\realestate\openapi\tests;


use GuzzleHttp\Psr7\Response;
use naspersclassifieds\realestate\openapi\exceptions\OpenApiException;
use naspersclassifieds\realestate\openapi\tests\utils\Fixtures;

class OpenApiClientDictionariesTest extends OpenApiTestCase
{

    public function testShouldRetrieveRootCategories(){

        $this->client->addResponse(new Response(200, [], Fixtures::load('categories.response.json')));

        $categories = $this->openApi->getDictionaries()->getCategories();

        $this->assertEquals(3, count($categories));
        $this->assertRequest('categories');
        $this->assertEquals("Wynajem", $categories[1]->names->pl);
    }

    public function testShouldRetrieveOneCategory(){

        $this->client->addResponse(new Response(200, [], Fixtures::load('categories.101.response.json')));

        $category = $this->openApi->getDictionaries()->getCategory(101);

        $this->assertRequest('categories/101');
        $this->assertEquals("Mieszkania", $category->names->pl);
        $this->assertEquals("cena", $category->parameters[0]->labels->pl);
    }

    public function testShouldNotRetrieveNonExistingCategory(){

        $this->client->addResponse(new Response(404, []));

        try {
            $this->openApi->getDictionaries()->getCategory(999);
            $this->fail();
        } catch (OpenApiException $e) {
            $this->assertEquals(404, $e->getCode());
        }
        $this->assertRequest('categories/999');
    }

    public function testShouldRetrieveCities(){

        $this->client->addResponse(new Response(200, [], Fixtures::load('cities.response.json')));

        $cities = $this->openApi->getDictionaries()->getCities();

        $this->assertEquals(81, count($cities));
        $this->assertRequest('cities');
        $this->assertEquals("Poznań", $cities[1]->name);
    }

    public function testShouldRetrieveOneCity(){

        $this->client->addResponse(new Response(200, [], Fixtures::load('cities.1.response.json')));

        $city = $this->openApi->getDictionaries()->getCity(1);

        $this->assertRequest('cities/1');
        $this->assertEquals("Poznań", $city->name);
    }

    public function testShouldRetrieveRegions(){

        $this->client->addResponse(new Response(200, [], Fixtures::load('regions.response.json')));

        $regions = $this->openApi->getDictionaries()->getRegions();

        $this->assertEquals(16, count($regions));

        $this->assertRequest('regions');
        $this->assertEquals("kujawsko-pomorskie", $regions[1]->name->pl);
    }

    public function testShouldRetrieveOneRegion(){

        $this->client->addResponse(new Response(200, [], Fixtures::load('regions.1.response.json')));

        $region = $this->openApi->getDictionaries()->getRegion(1);

        $this->assertRequest('regions/1');
        $this->assertEquals("dolnośląskie", $region->name->pl);
    }

    public function testShouldRetrieveSubregions(){

        $this->client->addResponse(new Response(200, [], Fixtures::load('subregions.response.json')));

        $subregions = $this->openApi->getDictionaries()->getSubRegions();

        $this->assertEquals(380, count($subregions));

        $this->assertRequest('subregions');
        $this->assertEquals("bartoszycki", $subregions[2]->name->pl);
    }

    public function testShouldRetrieveOneSubrregion(){

        $this->client->addResponse(new Response(200, [], Fixtures::load('subregions.1.response.json')));

        $subregion = $this->openApi->getDictionaries()->getSubregion(1);

        $this->assertRequest('subregions/1');
        $this->assertEquals("chodzieski", $subregion->name->pl);
    }
}
