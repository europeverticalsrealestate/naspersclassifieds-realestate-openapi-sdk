<?php
namespace naspersclassifieds\realestate\openapi;


use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Uri;
use naspersclassifieds\realestate\openapi\exceptions\OpenApiException;
use Psr\Http\Message\ResponseInterface;
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
     * @param string|array|null $class
     * @return mixed
     * @throws OpenApiException
     */
    public function get($resource, $class = null)
    {
        $resource = $this->appendAccessToken($resource);

        try {
            $response = $this->client->get(Uri::resolve($this->baseUri, $resource), $this->options);
            return $this->decodeResult($response, $class);
        } catch (RequestException $e) {
            $this->throwOpenApiException($e);
            return null;
        }
    }

    /**
     * @param $resource
     * @param mixed $object
     * @param null|string $class
     * @return mixed
     * @throws OpenApiException
     */
    public function update($resource, $object, $class = null)
    {
        $resource = $this->appendAccessToken($resource);

        try {
            $options = array_merge($this->options, ['json' => $object]);
            $response = $this->client->put(Uri::resolve($this->baseUri, $resource), $options);
            return $this->decodeResult($response, $class);
        } catch (RequestException $e) {
            $this->throwOpenApiException($e);
            return null;
        }
    }

    /**
     * @param $resource
     * @param mixed $object
     * @param null|string $class
     * @return mixed
     * @throws OpenApiException
     */
    public function post($resource, $object = null, $class = null)
    {
        $resource = $this->appendAccessToken($resource);

        try {
            $options = $this->options;
            if ($object) {
                $options = array_merge($options, ['json' => $object]);
            }
            $response = $this->client->post(Uri::resolve($this->baseUri, $resource), $options);
            return $this->decodeResult($response, $class);
        } catch (RequestException $e) {
            $this->throwOpenApiException($e);
            return null;
        }
    }

    /**
     * @param $resource
     * @return mixed
     * @throws OpenApiException
     */
    public function delete($resource)
    {
        $resource = $this->appendAccessToken($resource);

        try {
            $response = $this->client->delete(Uri::resolve($this->baseUri, $resource), $this->options);
            return $this->decodeResult($response);
        } catch (RequestException $e) {
            $this->throwOpenApiException($e);
            return null;
        }
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
            $response = $this->client->post($uri, array_merge($this->options, $options));

            $body = json_decode($response->getBody()->getContents());
            $this->accessToken = $body->access_token;
        } catch (RequestException $e) {
            $this->throwOpenApiException($e);
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

    /**
     * @param $e
     * @throws OpenApiException
     */
    protected function throwOpenApiException(RequestException $e)
    {
        $message = $e->getMessage();
        if ($e->hasResponse()) {
            $body = @json_decode($e->getResponse()->getBody()->getContents(), true);
            if (isset($body['error_description'])) {
                $message = $body['error_description'];
            } elseif (isset($body['error']) && is_scalar($body['error'])) {
                $message = $body['error'];
            } elseif (isset($body['error']['message'])) {
                $message = $body['error']['message'];
            }
        }
        throw new OpenApiException($message, $e->getCode(), $e);
    }

    /**
     * @param $resource
     * @return string
     */
    private function appendAccessToken($resource)
    {
        if ($this->isLoggedIn()) {
            $resource .= (strstr($resource, '?') ? '&' : '?') . 'access_token=' . $this->accessToken;
            return $resource;
        }
        return $resource;
    }

    /**
     * @param ResponseInterface $response
     * @param string|array|null $class
     * @return array|mixed
     */
    private function decodeResult(ResponseInterface $response, $class = null) {
        $contents = $response->getBody()->getContents();
        if (!$contents) {
            return null;
        }
        $results = json_decode($contents, true);
        if ($class) {
            return (new ObjectFactory($class))->build($results);
        }
        return $results;
    }
}