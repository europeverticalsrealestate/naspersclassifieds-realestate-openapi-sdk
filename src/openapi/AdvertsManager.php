<?php
namespace naspersclassifieds\realestate\openapi;

use naspersclassifieds\realestate\openapi\model\Advert;
use naspersclassifieds\realestate\openapi\model\AdvertsResult;
use naspersclassifieds\realestate\openapi\model\ImageCollection;
use naspersclassifieds\realestate\openapi\query\AccountAdverts;

class AdvertsManager
{
    /**
     * @var Client
     */
    private $client;

    /**
     * AdvertsManagement constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param AccountAdverts $query
     * @return AdvertsResult
     */
    public function getAdverts(AccountAdverts $query = null)
    {
        return $this->client->get('account/adverts' . $query, AdvertsResult::class);
    }

    /**
     * @param integer $id
     * @return Advert
     */
    public function getAdvert($id)
    {
        return $this->client->get('account/adverts/' . (int)$id, Advert::class);
    }

    /**
     * @param array $images
     * @return ImageCollection
     */
    public function createImageCollection($images)
    {
        $images = (object)array_combine(range(1, count($images)), $images);
        return $this->client->post('imageCollections', $images, ImageCollection::class);
    }

    /**
     * @param integer $id
     * @return ImageCollection
     */
    public function getImageCollection($id)
    {
        $result = $this->client->get('imageCollections/' . (int)$id);
        $factory = new ObjectFactory(ImageCollection::class);
        return $factory->build(['id' => $id, 'images' => $result]);
    }

    /**
     * @param integer $id
     * @param string $image
     */
    public function addToImageCollection($id, $image)
    {
        $this->client->update('imageCollections/' . ((int)$id) . '/images', (object)['source' => $image]);
    }

    /**
     * @param integer $id
     * @param integer $no
     * @param string $image
     */
    public function updateInImageCollection($id, $no, $image)
    {
        $this->client->update('imageCollections/' . ((int)$id) . '/images/' . ((int)$no), (object)['source' => $image]);
    }

    /**
     * @param integer $id
     * @param integer $no
     */
    public function deleteFromImageCollection($id, $no)
    {
        $this->client->delete('imageCollections/' . ((int)$id) . '/images/' . ((int)$no));
    }

    /**
     * @param Advert $advert
     * @return Advert
     */
    public function createAdvert(Advert $advert)
    {
        return $this->client->post('account/adverts', $advert, Advert::class);
    }

    /**
     * @param Advert $advert
     * @return Advert
     */
    public function updateAdvert(Advert $advert)
    {
        return $this->client->update('account/adverts/' . (int)$advert->id, $advert, Advert::class);
    }

    /**
     * @param integer $id
     */
    public function activateAdvert($id)
    {
        $this->client->post('account/adverts/' . (int)$id . '/activate');
    }

    /**
     * @param integer $id
     * @param integer $reasonId
     * @param string $reasonDescription
     */
    public function deactivateAdvert($id, $reasonId, $reasonDescription = '')
    {
        $requestData = [
            'reason' => [
                'id' => $reasonId,
                'description' => $reasonDescription
            ]
        ];
        $this->client->post('account/adverts/' . (int)$id . '/inactivate', $requestData);
    }

    /**
     * @param integer $id
     */
    public function deleteAdvert($id)
    {
        $this->client->delete('account/adverts/' . (int)$id);
    }
}
