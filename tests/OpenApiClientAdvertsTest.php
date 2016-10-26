<?php
namespace naspersclassifieds\realestate\openapi\tests;


use naspersclassifieds\realestate\openapi\query\AccountAdverts;
use naspersclassifieds\realestate\openapi\query\Adverts;
use naspersclassifieds\realestate\openapi\tests\utils\Constants;

class OpenApiClientAdvertsTest extends OpenApiTestCase
{
    public function testShouldRetrieveFirstPageOfAds()
    {
        $this->logInIntoApi();

        $this->addResponse(200, 'adverts.filtered.response.json');
        $query = (new Adverts())
            ->setLimit(10)
            ->setSortBy(Adverts::SORT_BY_CREATED_AT)
            ->setSortDirection(Adverts::SORT_ASC)
            ->setCategory(101)
        ;

        $adverts = $this->openApi->getSearch()->getAdverts($query);

        $this->assertAuthorizedRequest('adverts?limit=10&sortby=created_at&sortdirection=asc&fq={"category_id":101}');
        $this->assertEquals(10, count($adverts->results));
        $this->assertEquals(41, $adverts->total_elements);
        $this->assertEquals('Offer 2016-10-12 13:18:41', $adverts->results[0]->title);
        $this->assertEquals(192704, $adverts->results[0]->params['price'][1]);
    }

    public function testShouldRetrieveAdsFromParticularCityAndRegion()
    {
        $this->logInIntoApi();

        $this->addResponse(200, 'adverts.filtered.2.response.json');
        $query = (new Adverts())
            ->setCategory(101)
            ->setCity(30266)
            ->setRegion(5)
        ;
        $adverts = $this->openApi->getSearch()->getAdverts($query);

        $this->assertAuthorizedRequest('adverts?fq={"category_id":101,"city_id":30266,"region_id":5}');
        $this->assertEquals(1, count($adverts->results));
        $this->assertEquals(1, $adverts->total_elements);
    }

    public function testShouldRetrieveAdsIn5kmFromCity()
    {
        $this->logInIntoApi();

        $this->addResponse(200, 'adverts.filtered.3.response.json');
        $query = (new Adverts())
            ->setCity(1)
            ->setDistance(5)
        ;

        $adverts = $this->openApi->getSearch()->getAdverts($query);

        $this->assertAuthorizedRequest('adverts?fq={"city_id":1,"distance":5}');
        $this->assertEquals(1, count($adverts->results));
        $this->assertEquals(1, $adverts->total_elements);
    }

    public function testShouldRetrieveAdsIn1kmFromPoint()
    {
        $this->logInIntoApi();

        $this->addResponse(200, 'adverts.filtered.3.response.json');
        $query = (new Adverts())
            ->setLatLng(52.387, 16.86)
            ->setDistance(1)
        ;

        /**
         *                 case "user_id":
         */

        $adverts = $this->openApi->getSearch()->getAdverts($query);

        $this->assertAuthorizedRequest('adverts?fq={"latitude":52.387,"longitude":16.86,"distance":1}');
        $this->assertEquals(1, count($adverts->results));
        $this->assertEquals(1, $adverts->total_elements);
    }

    public function testShouldRetrieveAllUserAds()
    {
        $this->logInIntoApi();

        $this->addResponse(200, 'account.adverts.response.json'); // response is the same as for ads from account
        $query = (new Adverts())->setUser(1);

        $adverts = $this->openApi->getSearch()->getAdverts($query);

        $this->assertAuthorizedRequest('adverts?fq={"user_id":1}');
        $this->assertEquals(43, count($adverts->results));
    }

    private function logInIntoApi()
    {
        $this->addResponse(200, 'token.response.json');
        $this->openApi->logIn(
            Constants::AUTH_KEY,
            Constants::AUTH_SECRET,
            Constants::USER_LOGIN,
            Constants::USER_PASSWORD
        );
    }
}

