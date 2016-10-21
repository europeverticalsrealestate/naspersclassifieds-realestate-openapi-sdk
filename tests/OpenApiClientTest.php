<?php
namespace naspersclassifieds\realestate\openapi\tests;


use GuzzleHttp\Psr7\Response;
use naspersclassifieds\realestate\openapi\OpenApiClient;
use naspersclassifieds\realestate\openapi\tests\utils\Constants;
use naspersclassifieds\realestate\openapi\tests\utils\Fixtures;
use naspersclassifieds\realestate\openapi\tests\utils\HttpClientMocker;

class OpenApiClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var HttpClientMocker
     */
    private $mocker;

    protected function setUp()
    {
        $this->mocker = new HttpClientMocker();
    }

    public function testShouldRetrieveRootCategories(){

        $this->mocker->addResponse(new Response(200, [], Fixtures::load('categories.response.json')));

        $client = new OpenApiClient(Constants::API_URL, $this->mocker->getClient());
        $categories = $client->getCategories();

        $this->assertEquals(3, count($categories));
        $this->assertEquals(Constants::API_URL . 'categories', (string)$this->mocker->getLastRequest()->getUri());
        $this->assertEquals("Wynajem", $categories[1]->names->pl);
    }

    public function testShouldRetrieveOneCategory(){

        $this->mocker->addResponse(new Response(200, [], Fixtures::load('categories.101.response.json')));

        $client = new OpenApiClient(Constants::API_URL, $this->mocker->getClient());
        $category = $client->getCategory(101);

        $this->assertEquals(Constants::API_URL . 'categories/101', (string)$this->mocker->getLastRequest()->getUri());
        $this->assertEquals("Mieszkania", $category->names->pl);
        $this->assertEquals("cena", $category->parameters[0]->labels->pl);
    }

    public function testShouldRetrieveCities(){

        $this->mocker->addResponse(new Response(200, [], Fixtures::load('cities.response.json')));

        $client = new OpenApiClient(Constants::API_URL, $this->mocker->getClient());
        $cities = $client->getCities();

        $this->assertEquals(81, count($cities));
        $this->assertEquals(Constants::API_URL . 'cities', (string)$this->mocker->getLastRequest()->getUri());
        $this->assertEquals("Poznań", $cities[1]->name);
    }

    public function testShouldRetrieveOneCity(){

        $this->mocker->addResponse(new Response(200, [], Fixtures::load('cities.1.response.json')));

        $client = new OpenApiClient(Constants::API_URL, $this->mocker->getClient());
        $city = $client->getCity(1);

        $this->assertEquals(Constants::API_URL . 'cities/1', (string)$this->mocker->getLastRequest()->getUri());
        $this->assertEquals("Poznań", $city->name);
    }
}
