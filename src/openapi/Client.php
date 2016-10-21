<?php
namespace naspersclassifieds\realestate\openapi;


use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Uri;
use naspersclassifieds\realestate\openapi\exceptions\OpenApiException;
use stdClass;

class Client
{

    /**
     * @var array
     */
    private $options = ['headers' => ['Accept' => 'application/json']];

    /**
     * @var string
     */
    private $accessToken;

    /**
     * @var Uri
     */
    private $baseUri;

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * OpenApiClient constructor.
     * @param string $baseApiUrl base url of open api
     * @param ClientInterface $httpClient optional http client (used for tests)
     */
    public function __construct($baseApiUrl, ClientInterface $httpClient = null)
    {
        $this->baseUri = new Uri($baseApiUrl);
        $this->client = is_null($httpClient) ? new GuzzleHttpClient() : $httpClient;
    }

    /**
     * @param $resource
     * @return array
     * @throws OpenApiException
     */
    public function getFrom($resource)
    {
        try {
            $response = $this->client->get(Uri::resolve($this->baseUri, $resource), $this->options);
        } catch (RequestException $e) {
            throw new OpenApiException($e->getMessage(), $e->getCode(), $e);
        }
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param string $resource
     * @param string $class
     * @return array
     */
    public function getFromAsObjects($resource, $class)
    {
        $factory = new ObjectFactory($class);
        $results = $this->getFrom($resource)['results'];
        return $factory->buildMany($results);
    }

    /**
     * @param string $resource
     * @param integer $id
     * @param string $class
     * @return array
     */
    public function getFromAsObject($resource, $id, $class)
    {
        $factory = new ObjectFactory($class);
        $results = $this->getFrom($resource . '/' . $id);
        return $factory->build($results);
    }

    /**
     * @param string $key OpenApi key
     * @param string $secret OpenApi secret
     * @param string $login user login (e-mail)
     * @param string $password user password
     * @throws OpenApiException
     */
    public function logIn($key, $secret, $login, $password)
    {
        $this->accessToken = null;
        $uri = Uri::resolve($this->baseUri, 'oauth/token');
        $options = [
            'auth' => [$key, $secret],
            'form_params' => [
                'grant_type' => 'password',
                'username' => $login,
                'password' => $password
            ]
        ];

        try {
            $response = $this->client->post($uri, $options);

            $body = json_decode($response->getBody()->getContents());
            $this->accessToken = $body->access_token;
        } catch (RequestException $e) {
            if ($e->hasResponse()){
                $body = json_decode($e->getResponse()->getBody()->getContents());
                $message = isset($body->error_description)
                    ? $body->error_description
                    : (isset($body->error) ? $body->error : $e->getMessage());
            } else {
                $message = $e->getMessage();
            }

            throw new OpenApiException($message, $e->getCode(), $e);
        }
    }

    public function isLoggedIn()
    {
        return !empty($this->accessToken);
    }

    public function logOut()
    {
        $this->accessToken = null;
    }
}