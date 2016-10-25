<?php
namespace naspersclassifieds\realestate\openapi\tests;


use naspersclassifieds\realestate\openapi\query\AccountAdvertsQuery;
use naspersclassifieds\realestate\openapi\tests\utils\Constants;

class OpenApiClientAdvertsTest extends OpenApiTestCase
{
    public function testShouldRetrieveAllUserAds()
    {
        $this->logInIntoApi();

        $this->addResponse(200, 'account.adverts.response.json');
        $adverts = $this->openApi->getAccount()->getAdverts();

        $this->assertAuthorizedRequest('account/adverts');
        $this->assertEquals(43, count($adverts->results));
        $this->assertEquals('Offer title', $adverts->results[1]->title);
        $this->assertEquals(192704, $adverts->results[42]->params['price'][1]);
    }

    public function testShouldRetrieveSecondPageOfUserUserAdsLimitingTo20()
    {
        $this->logInIntoApi();

        $this->addResponse(200, 'account.adverts.limit20.page2.response.json');
        $adverts = $this->openApi->getAccount()->getAdverts((new AccountAdvertsQuery())->setLimit(20)->setPage(2));

        $this->assertAuthorizedRequest('account/adverts?limit=20&page=2');
        $this->assertEquals(20, count($adverts->results));
        $this->assertEquals('Offer title', $adverts->results[0]->title);
        $this->assertEquals(850038, $adverts->results[0]->params['price'][1]);
        $this->assertEquals(23, $adverts->results[0]->id);
    }

    public function testShouldRetrieveAllRemovedOffers()
    {
        $this->logInIntoApi();

        $this->addResponse(200, 'account.adverts.inactive.response.json');
        $query = (new AccountAdvertsQuery())->setStatus(AccountAdvertsQuery::STATUS_REMOVED);
        $adverts = $this->openApi->getAccount()->getAdverts($query);

        $this->assertAuthorizedRequest('account/adverts?status=archive');
        $this->assertEquals(1, count($adverts->results));
        $this->assertEquals('Offer title', $adverts->results[0]->title);
        $this->assertEquals('removed_by_user', $adverts->results[0]->status);
    }

    public function testShouldRetrieveAllUserAdsSortingByCreatedAtDesc()
    {
        $this->logInIntoApi();

        $this->addResponse(200, 'account.adverts.response.json');
        $query = (new AccountAdvertsQuery())
            ->setSortBy(AccountAdvertsQuery::SORT_BY_CREATED_AT)
            ->setSortDirection(AccountAdvertsQuery::SORT_DESC)
        ;
        $adverts = $this->openApi->getAccount()->getAdverts($query);

        $this->assertAuthorizedRequest('account/adverts?sortby=created_at&sortdirection=desc');
        $this->assertEquals(43, count($adverts->results));
    }

    public function testShouldRetrieveOneUserAd()
    {
        $this->logInIntoApi();

        $this->addResponse(200, 'account.adverts.51.response.json');
        $advert = $this->openApi->getAccount()->getAdvert(51);

        $this->assertAuthorizedRequest('account/adverts/51');
        $this->assertEquals('Offer title', $advert->title);
        $this->assertEquals(500, $advert->params['price'][1]);
        $this->assertEquals(302, $advert->category_id);
        $this->assertEquals(51, $advert->id);
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

