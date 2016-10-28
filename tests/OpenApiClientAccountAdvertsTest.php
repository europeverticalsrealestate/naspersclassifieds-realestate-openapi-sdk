<?php
namespace naspersclassifieds\realestate\openapi\tests;


use naspersclassifieds\realestate\openapi\exceptions\OpenApiException;
use naspersclassifieds\realestate\openapi\model\Advert;
use naspersclassifieds\realestate\openapi\model\Agent;
use naspersclassifieds\realestate\openapi\query\AccountAdverts;
use naspersclassifieds\realestate\openapi\tests\utils\Constants;

class OpenApiClientAccountAdvertsTest extends OpenApiTestCase
{
    public function testShouldRetrieveAllUserAds()
    {
        $this->logInIntoApi();

        $this->addResponse(200, 'account.adverts.response.json');
        $adverts = $this->openApi->getAccount()->getAdvertsManager()->getAdverts();

        $this->assertAuthorizedRequest('account/adverts');
        $this->assertEquals(43, count($adverts->results));
        $this->assertEquals('Offer title', $adverts->results[1]->title);
        $this->assertEquals(192704, $adverts->results[42]->params['price'][1]);
    }

    public function testShouldRetrieveSecondPageOfUserUserAdsLimitingTo20()
    {
        $this->logInIntoApi();

        $this->addResponse(200, 'account.adverts.limit20.page2.response.json');
        $adverts = $this->openApi->getAccount()->getAdvertsManager()->getAdverts((new AccountAdverts())->setLimit(20)->setPage(2));

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
        $query = (new AccountAdverts())->setStatus(AccountAdverts::STATUS_REMOVED);
        $adverts = $this->openApi->getAccount()->getAdvertsManager()->getAdverts($query);

        $this->assertAuthorizedRequest('account/adverts?status=archive');
        $this->assertEquals(1, count($adverts->results));
        $this->assertEquals('Offer title', $adverts->results[0]->title);
        $this->assertEquals('removed_by_user', $adverts->results[0]->status);
    }

    public function testShouldRetrieveAllUserAdsSortingByCreatedAtDesc()
    {
        $this->logInIntoApi();

        $this->addResponse(200, 'account.adverts.response.json');
        $query = (new AccountAdverts())
            ->setSortBy(AccountAdverts::SORT_BY_CREATED_AT)
            ->setSortDirection(AccountAdverts::SORT_DESC)
        ;
        $adverts = $this->openApi->getAccount()->getAdvertsManager()->getAdverts($query);

        $this->assertAuthorizedRequest('account/adverts?sortby=created_at&sortdirection=desc');
        $this->assertEquals(43, count($adverts->results));
    }

    public function testShouldRetrieveOneUserAd()
    {
        $this->logInIntoApi();

        $this->addResponse(200, 'account.adverts.51.response.json');
        $advert = $this->openApi->getAccount()->getAdvertsManager()->getAdvert(51);

        $this->assertAuthorizedRequest('account/adverts/51');
        $this->assertEquals('Offer title', $advert->title);
        $this->assertEquals(500, $advert->params['price'][1]);
        $this->assertEquals(302, $advert->category_id);
        $this->assertEquals(51, $advert->id);
    }

    public function testShouldNotRetrieveAllUserAdsIfNotLoggedIn()
    {
        $this->addResponse(403, 'token.invalid.token.response.json');
        try {
            $this->openApi->getAccount()->getAdvertsManager()->getAdverts();
            $this->fail();
        } catch (OpenApiException $e) {
            $this->assertEquals('Token is invalid and/or expired', $e->getMessage());
        }
    }

    public function testShouldCreateImageCollection()
    {
        $this->logInIntoApi();

        $this->addResponse(200, 'account.images.post.response.json');

        $images = [
            'data:image/jpeg;base64,' . base64_encode($this->loadFixture('images/image.jpg')),
            'http://dummy.address/dummy.photo.jpg'
        ];

        $imageCollection = $this->openApi->getAccount()->getAdvertsManager()->createImageCollection($images);

        $expectedBody = json_encode(['1' => $images[0], '2' => $images[1]]);

        $this->assertAuthorizedRequest('imageCollections', 'POST', [], [], $expectedBody);
        $this->assertEquals(58, $imageCollection->id);
        $this->assertEquals(2, count($imageCollection->images));
    }

    public function testShouldGetImageCollection()
    {
        $this->logInIntoApi();

        $this->addResponse(200, 'account.images.58.response.json');

        $imageCollection = $this->openApi->getAccount()->getAdvertsManager()->getImageCollection(58);

        $this->assertAuthorizedRequest('imageCollections/58');
        $this->assertEquals(58, $imageCollection->id);
        $this->assertEquals(2, count($imageCollection->images));
    }

    public function testAddImageToImageCollection()
    {
        $this->logInIntoApi();

        $this->addResponse(200, 'account.images.put.response.json');

        $image = 'http://dummy.address/dummy.photo.jpg';

        $this->openApi->getAccount()->getAdvertsManager()->addToImageCollection(58, $image);

        $expectedBody = json_encode(['source' => $image]);
        $this->assertAuthorizedRequest('imageCollections/58/images', 'PUT', [], [], $expectedBody);
    }

    public function testUpdateImageInImageCollection()
    {
        $this->logInIntoApi();

        $this->addResponse(200, 'account.images.put.response.json');

        $image = 'http://dummy.address/dummy.photo.jpg';

        $this->openApi->getAccount()->getAdvertsManager()->updateInImageCollection(58, 3, $image);

        $expectedBody = json_encode(['source' => $image]);
        $this->assertAuthorizedRequest('imageCollections/58/images/3', 'PUT', [], [], $expectedBody);
    }

    public function testDeleteImageFromImageCollection()
    {
        $this->logInIntoApi();

        $this->addResponse(204);

        $this->openApi->getAccount()->getAdvertsManager()->deleteFromImageCollection(58, 2);

        $this->assertAuthorizedRequest('imageCollections/58/images/2', 'DELETE');
    }

    public function testShouldCreateAdvert()
    {
        $this->logInIntoApi();

        $this->addResponse(200, 'account.adverts.post.response.json');

        $advert = $this->getAdvert();
        $createdAdvert = $this->openApi->getAccount()->getAdvertsManager()->createAdvert($advert);

        $expectedBody = json_encode($advert);
        $this->assertAuthorizedRequest('account/adverts', 'POST', [], [], $expectedBody);
        $this->assertEquals(1234, $createdAdvert->id);
        $this->assertEquals($advert->description, $createdAdvert->description);
        $this->assertEquals($advert->agent->id, $createdAdvert->agent->id);
    }

    public function testShouldUpdateAdvert()
    {
        $this->logInIntoApi();

        $this->addResponse(200, 'account.adverts.put.response.json');

        $advert = $this->getAdvert();
        $advert->id = 1234;
        $advert->agent = new Agent();
        $advert->agent->id = false;
        $createdAdvert = $this->openApi->getAccount()->getAdvertsManager()->updateAdvert($advert);

        $expectedBody = json_encode($advert);
        $this->assertAuthorizedRequest('account/adverts/1234', 'PUT', [], [], $expectedBody);
        $this->assertEquals(1234, $createdAdvert->id);
        $this->assertEquals($advert->description, $createdAdvert->description);
        $this->assertEquals(null, $createdAdvert->agent->id);
    }

    public function testShouldActivateAdvert()
    {
        $this->logInIntoApi();

        $this->addResponse(200, 'account.adverts.activate.response.json');

        $this->openApi->getAccount()->getAdvertsManager()->activateAdvert(1234);

        $this->assertAuthorizedRequest('account/adverts/1234/activate', 'POST');
    }

    public function testShouldDeactivateAdvert()
    {
        $this->logInIntoApi();

        $this->addResponse(204);

        $reasonId = 13;
        $reasonDescription = "reason description";
        $this->openApi->getAccount()->getAdvertsManager()->deactivateAdvert(1234, $reasonId, $reasonDescription);

        $expectedBody = json_encode(['reason' => ['id' => $reasonId, 'description' => $reasonDescription]]);
        $this->assertAuthorizedRequest('account/adverts/1234/inactivate', 'POST', [], [], $expectedBody);
    }

    public function testShouldDeleteAdvert()
    {
        $this->logInIntoApi();

        $this->addResponse(204);

        $this->openApi->getAccount()->getAdvertsManager()->deleteAdvert(1234);

        $this->assertAuthorizedRequest('account/adverts/1234', 'DELETE');
    }

    /**
     * @return Advert
     */
    private function getAdvert()
    {
        $advert = new Advert();
        $advert->title = "Example ad added via OpenAPI";
        $advert->description = "Lorem <strong>ipsum dolor sit amet</strong>, consectetur adipiscing elit. Donec "
            . "urna at vulputate. Nullam porta odio quam, ac <em>rutrum nulla</em> commodo id. Maecenas dapibus "
            . "quis neque vel volutpat. Aliquam <u>erat volutpat</u>. Vivamus nec dui vulputate, facilisis augue "
            . "sit amet, aliquam nulla.\nSed feugiat sollicitudin varius.";
        $advert->external_id = "MYID-123";
        $advert->category_id = 101;       //flats for sale
        $advert->region_id = 1;           //wielkopolskie, Poland
        $advert->city_id = 1;             //Poznań, Poland
        $advert->district_id = 80;        //Jeżyce - a district in Poznań, Poland
        $advert->coordinates = [
            "latitude" => 44.79343,
            "longitude" => 23.16014,
            "radius" => 0,
            "zoom_level" => 12
        ];
        $advert->params = [
            "price" => [
                "0" => "price",     //always "price"
                "1" => 100000,
                "currency" => "PLN"
            ],
            "m" => 87,
            "rooms_num" => 3,
            "market" => "secondary"
        ];
        $advert->image_collection_id = 58;
        $advert->agent = new Agent();
        $advert->agent->id = 1;
        return $advert;
    }

}

