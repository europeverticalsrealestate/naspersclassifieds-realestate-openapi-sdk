<?php
namespace naspersclassifieds\realestate\openapi\tests;


use naspersclassifieds\realestate\openapi\exceptions\OpenApiException;
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
            ->setSortBy(Adverts::SORT_BY_LIST_POSITION)
            ->setSortDirection(Adverts::SORT_ASC)
            ->setCategory(101);

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
            ->setRegion(5);
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
            ->setDistance(5);

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
            ->setDistance(1);

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

    public function testShouldRetrieveAdsWithSpecifiedPriceFrom()
    {
        $this->logInIntoApi();

        $this->addResponse(200, 'adverts.filtered.4.response.json');
        $query = (new Adverts())
            ->setCategory(101)
            ->setFromParam('price', 200000);

        $adverts = $this->openApi->getSearch()->getAdverts($query);

        $this->assertAuthorizedRequest('adverts?fq={"category_id":101,"params":{"price":["from",200000]}}');
        $this->assertEquals(32, count($adverts->results));
        $this->assertEquals(46, $adverts->results[1]->id);
    }

    public function testShouldRetrieveAdsWithSpecifiedPriceTo()
    {
        $this->logInIntoApi();

        $this->addResponse(200, 'adverts.filtered.4.response.json');
        $query = (new Adverts())
            ->setCategory(101)
            ->setToParam('price', 20000000);

        $this->openApi->getSearch()->getAdverts($query);

        $this->assertAuthorizedRequest('adverts?fq={"category_id":101,"params":{"price":["to",20000000]}}');
    }

    public function testShouldRetrieveAdsWithSpecifiedPriceRange()
    {
        $this->logInIntoApi();

        $this->addResponse(200, 'adverts.filtered.4.response.json');
        $query = (new Adverts())
            ->setCategory(101)
            ->setRangeParam('price', 200000, 20000000);

        $this->openApi->getSearch()->getAdverts($query);

        $this->assertAuthorizedRequest('adverts?fq={"category_id":101,"params":{"price":["range",200000,20000000]}}');
    }

    public function testShouldRetrieveAdsWithSpecifiedType()
    {
        $this->logInIntoApi();

        $this->addResponse(200, 'adverts.filtered.5.response.json');
        $query = (new Adverts())
            ->setCategory(101)
            ->setParam('building_material', "reinforced_concrete");

        $adverts = $this->openApi->getSearch()->getAdverts($query);

        $this->assertAuthorizedRequest(
            'adverts?fq={"category_id":101,"params":{"building_material":"reinforced_concrete"}}'
        );
        $this->assertEquals(5, count($adverts->results));
        $this->assertEquals(5, $adverts->total_elements);
    }

    public function testShouldRetrieveAdsWithInternetAndTv()
    {
        $this->logInIntoApi();

        $this->addResponse(200, 'adverts.filtered.3.response.json');
        $query = (new Adverts())
            ->setCategory(302)
            ->setMultiOptionParam('media_types', ["internet", "cable-television"]);

        $adverts = $this->openApi->getSearch()->getAdverts($query);

        $this->assertAuthorizedRequest(
            'adverts?fq={"category_id":302,"params":{"media_types":["all","internet","cable-television"]}}'
        );
        $this->assertEquals(1, count($adverts->results));
        $this->assertEquals(1, $adverts->total_elements);
    }

    public function testShouldRetrieveOneAd()
    {
        $this->logInIntoApi();

        $this->addResponse(200, 'adverts.51.response.json');
        $advert = $this->openApi->getSearch()->getAdvert(51);

        $this->assertAuthorizedRequest('adverts/51');
        $this->assertEquals(500, $advert->params['price'][1]);
        $this->assertEquals(302, $advert->category_id);
        $this->assertEquals(51, $advert->id);
    }

    public function testShouldNotRetrieveAdsWithParametersFromOtherCategory()
    {
        $this->logInIntoApi();

        $this->addResponse(400, 'adverts.bad.request.response.json');

        $query = (new Adverts())
            ->setCategory(102)
            ->setMultiOptionParam('media_types', ["internet", "cable-television"])
        ;

        try {
            $this->openApi->getSearch()->getAdverts($query);
            $this->fail();
        } catch (OpenApiException $e) {
            $this->assertEquals('Can\'t search using parameter media_types', $e->getMessage());
            $this->assertEquals(400, $e->getCode());
        }

        $this->assertAuthorizedRequest(
            'adverts?fq={"category_id":102,"params":{"media_types":["all","internet","cable-television"]}}'
        );
    }

    public function testShouldNotRetrieveAdsIfNotLoggedIn()
    {
        $this->addResponse(403, 'token.invalid.token.response.json');
        try {
            $this->openApi->getSearch()->getAdverts();
            $this->fail();
        } catch (OpenApiException $e) {
            $this->assertEquals('Token is invalid and/or expired', $e->getMessage());
        }
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
