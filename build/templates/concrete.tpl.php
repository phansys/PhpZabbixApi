<?php

/**
 * This file is part of Zabbix PHP SDK package.
 *
 * (c) The Nubity Development Team <dev@nubity.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ZabbixApi;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;

/**
 * Class for the Zabbix API.
 */
class <CLASSNAME_CONCRETE>
{
    /**
     * @var array
     */
    private static $anonymousFunctions = [
        'apiinfo.version',
    ];

    /**
     * @var bool
     */
    private $printCommunication = false;

    /**
     * API URL.
     */
    private $apiUrl = '';

    /**
     * @var array
     */
    private $defaultParams = [];

    /**
     * @var int
     */
    private $id = 0;

    /**
     * @var array
     */
    private $payload = [];

    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * @var string
     */
    private $responseDecoded;

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var array
     */
    private $requestOptions = [];

    /**
     * Class constructor.
     *
     * @param string $apiUrl         API url (e.g. http://FQDN/zabbix/api_jsonrpc.php)
     * @param string $user           Username for Zabbix API
     * @param string $password       Password for Zabbix API
     * @param string $httpUser       Username for HTTP basic authorization
     * @param string $httpPassword   Password for HTTP basic authorization
     * @param string $authToken      Already issued auth token (e.g. extracted from cookies)
     * @param null|ClientInterface $client
     * @param array $clientOptions
     */
    public function __construct($apiUrl = '', $user = '', $password = '', $httpUser = '', $httpPassword = '', $authToken = '', ClientInterface $client = null, array $clientOptions = [])
    {
        if ($client && $clientOptions) {
            throw new \InvalidArgumentException('If argument 7 is provided, argument 8 must be omitted or passed with an empty array as value');
        }

        if ($apiUrl) {
            $this->setApiUrl($apiUrl);
        }
        $clientOptions['base_uri'] = $apiUrl;

        if ($httpUser && $httpPassword) {
            $this->setBasicAuthorization($httpUser, $httpPassword);
        }

        $this->client = $client ?: new Client($clientOptions);
        if ($authToken) {
            $this->setAuthToken($authToken);
        } elseif ($user && $password) {
            $this->userLogin(['user' => $user, 'password' => $password]);
        }
    }

    /**
     * Returns the API url for all requests.
     *
     * @return string  API url
     */
    public function getApiUrl()
    {
        return $this->apiUrl;
    }

    /**
     * Sets the API url for all requests.
     *
     * @param string $apiUrl     API url
     *
     * @return <CLASSNAME_CONCRETE>
     */
    public function setApiUrl($apiUrl)
    {
        $this->apiUrl = $apiUrl;

        return $this;
    }

    /**
     * Sets the API authorization ID.
     *
     * @param string $authToken     API auth ID
     *
     * @return <CLASSNAME_CONCRETE>
     */
    public function setAuthToken($authToken)
    {
        $this->authToken = $authToken;

        return $this;
    }

    /**
     * Sets the username and password for the HTTP basic authorization.
     *
     * @param string  $user       HTTP basic authorization username
     * @param string  $password   HTTP basic authorization password
     *
     * @return <CLASSNAME_CONCRETE>
     */
    public function setBasicAuthorization($user, $password)
    {
        if ($user && $password) {
            $this->requestOptions['auth'] = [$user, $password];
        }

        return $this;
    }

    /**
     * Returns the default params.
     *
     * @return array   Array with default params
     */
    public function getDefaultParams()
    {
        return $this->defaultParams;
    }

    /**
     * Sets the default params.
     *
     * @param mixed $defaultParams  Array with default params
     *
     * @throws Exception
     *
     * @return <CLASSNAME_CONCRETE>
     */
    public function setDefaultParams($defaultParams)
    {
        if (is_array($defaultParams)) {
            $this->defaultParams = $defaultParams;
        } else {
            throw new Exception('The argument defaultParams on setDefaultParams() has to be an array.');
        }

        return $this;
    }

    /**
     * Sets the flag to print communication requests/responses.
     *
     * @param bool $print  Boolean if requests/responses should be printed out
     *
     * @return <CLASSNAME_CONCRETE>
     */
    public function printCommunication($print = true)
    {
        $this->printCommunication = (bool) $print;

        return $this;
    }

    /**
     * Sends are request to the zabbix API and returns the response
     *          as object.
     *
     * @param string $method     name of the API method
     * @param mixed $params     additional parameters
     * @param mixed $resultArrayKey
     * @param bool $auth       enable authentication (default TRUE)
     *
     * @return \stdClass    API JSON response
     */
    public function request($method, $params = null, $resultArrayKey = '', $auth = true)
    {
        // sanity check and conversion for params array
        if (!$params) {
            $params = [];
        } elseif (!is_array($params)) {
            $params = [$params];
        }

        // generate ID
        $this->id = number_format(microtime(true), 4, '', '');

        // build request array
        $this->payload = [
            'jsonrpc' => '2.0',
            'method' => $method,
            'params' => $params,
            'id' => $this->id,
        ];

        // add auth token if required
        if ($auth) {
            $this->payload['auth'] = ($this->authToken ? $this->authToken : null);
        }

        try {
            $this->response = $this->client->request('POST', '', $this->requestOptions + [
                RequestOptions::HEADERS => ['Content-type' => 'application/json-rpc'],
                RequestOptions::JSON => $this->payload,
            ]);
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $this->response = $e->getResponse();

                throw new Exception($e->getMessage().': '.$this->response->getBody()->getContents(), $e->getCode());
            }

            throw new Exception($e->getMessage(), $e->getCode());
        }

        // debug logging
        if ($this->printCommunication) {
            echo $this->response."\n";
        }

        // response verification
        if (false === $this->response) {
            throw new Exception('Could not read data from "'.$this->getApiUrl().'"');
        }
        // decode response
        $this->responseDecoded = \GuzzleHttp\json_decode($this->response->getBody());

        // validate response
        if (!is_object($this->responseDecoded) && !is_array($this->responseDecoded)) {
            throw new Exception('Could not decode JSON response.');
        }
        if (array_key_exists('error', $this->responseDecoded)) {
            throw new Exception('API error '.$this->responseDecoded->error->code.': '.$this->responseDecoded->error->data);
        }
        // return response
        if ($resultArrayKey && is_array($this->responseDecoded->result)) {
            return $this->convertToAssociatveArray($this->responseDecoded->result, $resultArrayKey);
        }

        return $this->responseDecoded->result;
    }

    /**
     * Returns the last JSON API response.
     *
     * @return ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Login into the API.
     *
     * This will also retreive the auth Token, which will be used for any
     * further requests. Please be aware that by default the received auth
     * token will be cached on the filesystem.
     *
     * When a user is successfully logged in for the first time, the token will
     * be cached / stored in the $tokenCacheDir directory. For every future
     * request, the cached auth token will automatically be loaded and the
     * user.login is skipped. If the auth token is invalid/expired, user.login
     * will be executed, and the auth token will be cached again.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associatve instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. name, host,
     * hostid, graphid, screenitemid).
     *
     * @param array $params             parameters to pass through
     * @param string $arrayKeyProperty   object property for key of array
     * @param string $tokenCacheDir      path to a directory to store the auth token
     *
     * @throws  Exception
     *
     * @return \stdClass
     */
    final public function userLogin($params = [], $arrayKeyProperty = '', $tokenCacheDir = '/tmp')
    {
        // reset auth token
        $this->authToken = '';

        // build filename for cached auth token
        if ($tokenCacheDir && array_key_exists('user', $params) && is_dir($tokenCacheDir)) {
            $uid = function_exists('posix_getuid') ? posix_getuid() : -1;
            $tokenCacheFile = $tokenCacheDir.'/.zabbixapi-token-'.md5($params['user'].'|'.$uid);
        }

        // try to read cached auth token
        if (isset($tokenCacheFile) && is_file($tokenCacheFile)) {
            try {
                // get auth token and try to execute a user.get (dummy check)
                $this->authToken = file_get_contents($tokenCacheFile);
                $this->userGet();
            } catch (Exception $e) {
                // user.get failed, token invalid so reset it and remove file
                $this->authToken = '';
                unlink($tokenCacheFile);
            }
        }

        // no cached token found so far, so login (again)
        if (!$this->authToken) {
            // login to get the auth token
            $params = $this->getRequestParamsArray($params);
            $this->authToken = $this->request('user.login', $params, $arrayKeyProperty, false);

            // save cached auth token
            if (isset($tokenCacheFile)) {
                file_put_contents($tokenCacheFile, $this->authToken);
                chmod($tokenCacheFile, 0600);
            }
        }

        return $this->authToken;
    }

    /**
     * Logout from the API.
     *
     * This will also reset the auth Token.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associatve instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. name, host,
     * hostid, graphid, screenitemid).
     *
     * @param array $params             parameters to pass through
     * @param string $arrayKeyProperty   object property for key of array
     *
     * @throws  Exception
     *
     * @return \stdClass
     */
    final public function userLogout($params = [], $arrayKeyProperty = '')
    {
        $params = $this->getRequestParamsArray($params);
        $response = $this->request('user.logout', $params, $arrayKeyProperty);
        $this->authToken = '';

        return $response;
    }
<!START_API_METHOD>
    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method <API_METHOD>.
     *
     * The $params Array can be used, to pass parameters to the Zabbix API.
     * For more informations about these parameters, check the Zabbix API
     * documentation at https://www.zabbix.com/documentation/.
     *
     * The $arrayKeyProperty can be used to get an associatve instead of an
     * indexed array as response. A valid value for the $arrayKeyProperty is
     * is any property of the returned JSON objects (e.g. name, host,
     * hostid, graphid, screenitemid).
     *
     * @param mixed  $params             Zabbix API parameters
     * @param string $arrayKeyProperty   Object property for key of array
     *
     * @throws  Exception
     *
     * @return \stdClass
     */
    public function <PHP_METHOD>($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('<API_METHOD>', self::$anonymousFunctions, true);

        // request
        return $this->request('<API_METHOD>', $params, $arrayKeyProperty, $auth);
    }
<!END_API_METHOD>
    /**
     * Convertes an indexed array to an associative array.
     *
     * @param array $objectArray           indexed array with objects
     * @param string $useObjectProperty    object property to use as array key
     *
     * @return array An associative Array
     */
    private function convertToAssociatveArray(array $objectArray, $useObjectProperty)
    {
        // sanity check
        if (0 == count($objectArray) || !property_exists($objectArray[0], $useObjectProperty)) {
            return $objectArray;
        }
        // loop through array and replace keys
        $newObjectArray = [];
        foreach ($objectArray as $key => $object) {
            $newObjectArray[$object->{$useObjectProperty}] = $object;
        }

        // return associative array
        return $newObjectArray;
    }

    /**
     * Returns a params array for the request.
     *
     * This method will automatically convert all provided types into a correct
     * array. Which means:
     *
     *      - arrays will not be converted (indexed & associatve)
     *      - scalar values will be converted into an one-element array (indexed)
     *      - other values will result in an empty array
     *
     * Afterwards the array will be merged with all default params, while the
     * default params have a lower priority (passed array will overwrite default
     * params). But there is an Exception for merging: If the passed array is an
     * indexed array, the default params will not be merged. This is because
     * there are some API methods, which are expecting a simple JSON array (aka
     * PHP indexed array) instead of an object (aka PHP associative array).
     * Example for this behaviour are delete operations, which are directly
     * expecting an array of IDs '[ 1,2,3 ]' instead of '{ ids: [ 1,2,3 ] }'.
     *
     * @param mixed $params     params array
     *
     * @return array
     */
    private function getRequestParamsArray($params)
    {
        // if params is a scalar value, turn it into an array
        if (is_scalar($params)) {
            $params = [$params];
        }

        // if params isn't an array, create an empty one (e.g. for booleans, null)
        elseif (!is_array($params)) {
            $params = [];
        }

        // if array isn't indexed, merge array with default params
        if (0 == count($params) || array_keys($params) !== range(0, count($params) - 1)) {
            $params = array_merge($this->getDefaultParams(), $params);
        }

        // return params
        return $params;
    }
}
