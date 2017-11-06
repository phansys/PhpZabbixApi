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
class ZabbixApi
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
     * @return ZabbixApi
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
     * @return ZabbixApi
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
     * @return ZabbixApi
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
     * @return ZabbixApi
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
     * @return ZabbixApi
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

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method api.tableName.
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
    public function apiTableName($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('api.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('api.tableName', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method api.pk.
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
    public function apiPk($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('api.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('api.pk', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method api.pkOption.
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
    public function apiPkOption($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('api.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('api.pkOption', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method action.get.
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
    public function actionGet($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('action.get', self::$anonymousFunctions, true);

        // request
        return $this->request('action.get', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method action.exists.
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
    public function actionExists($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('action.exists', self::$anonymousFunctions, true);

        // request
        return $this->request('action.exists', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method action.create.
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
    public function actionCreate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('action.create', self::$anonymousFunctions, true);

        // request
        return $this->request('action.create', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method action.update.
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
    public function actionUpdate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('action.update', self::$anonymousFunctions, true);

        // request
        return $this->request('action.update', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method action.delete.
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
    public function actionDelete($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('action.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('action.delete', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method action.validateOperationsIntegrity.
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
    public function actionValidateOperationsIntegrity($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('action.validateOperationsIntegrity', self::$anonymousFunctions, true);

        // request
        return $this->request('action.validateOperationsIntegrity', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method action.validateOperationConditions.
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
    public function actionValidateOperationConditions($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('action.validateOperationConditions', self::$anonymousFunctions, true);

        // request
        return $this->request('action.validateOperationConditions', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method action.validateCreate.
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
    public function actionValidateCreate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('action.validateCreate', self::$anonymousFunctions, true);

        // request
        return $this->request('action.validateCreate', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method action.validateUpdate.
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
    public function actionValidateUpdate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('action.validateUpdate', self::$anonymousFunctions, true);

        // request
        return $this->request('action.validateUpdate', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method action.tableName.
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
    public function actionTableName($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('action.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('action.tableName', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method action.pk.
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
    public function actionPk($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('action.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('action.pk', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method action.pkOption.
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
    public function actionPkOption($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('action.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('action.pkOption', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method alert.get.
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
    public function alertGet($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('alert.get', self::$anonymousFunctions, true);

        // request
        return $this->request('alert.get', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method alert.tableName.
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
    public function alertTableName($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('alert.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('alert.tableName', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method alert.pk.
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
    public function alertPk($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('alert.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('alert.pk', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method alert.pkOption.
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
    public function alertPkOption($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('alert.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('alert.pkOption', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method apiinfo.version.
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
    public function apiinfoVersion($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('apiinfo.version', self::$anonymousFunctions, true);

        // request
        return $this->request('apiinfo.version', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method apiinfo.tableName.
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
    public function apiinfoTableName($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('apiinfo.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('apiinfo.tableName', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method apiinfo.pk.
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
    public function apiinfoPk($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('apiinfo.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('apiinfo.pk', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method apiinfo.pkOption.
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
    public function apiinfoPkOption($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('apiinfo.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('apiinfo.pkOption', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method application.get.
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
    public function applicationGet($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('application.get', self::$anonymousFunctions, true);

        // request
        return $this->request('application.get', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method application.exists.
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
    public function applicationExists($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('application.exists', self::$anonymousFunctions, true);

        // request
        return $this->request('application.exists', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method application.checkInput.
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
    public function applicationCheckInput($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('application.checkInput', self::$anonymousFunctions, true);

        // request
        return $this->request('application.checkInput', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method application.create.
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
    public function applicationCreate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('application.create', self::$anonymousFunctions, true);

        // request
        return $this->request('application.create', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method application.update.
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
    public function applicationUpdate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('application.update', self::$anonymousFunctions, true);

        // request
        return $this->request('application.update', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method application.delete.
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
    public function applicationDelete($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('application.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('application.delete', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method application.massAdd.
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
    public function applicationMassAdd($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('application.massAdd', self::$anonymousFunctions, true);

        // request
        return $this->request('application.massAdd', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method application.tableName.
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
    public function applicationTableName($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('application.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('application.tableName', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method application.pk.
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
    public function applicationPk($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('application.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('application.pk', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method application.pkOption.
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
    public function applicationPkOption($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('application.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('application.pkOption', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method configuration.export.
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
    public function configurationExport($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('configuration.export', self::$anonymousFunctions, true);

        // request
        return $this->request('configuration.export', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method configuration.import.
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
    public function configurationImport($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('configuration.import', self::$anonymousFunctions, true);

        // request
        return $this->request('configuration.import', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method configuration.tableName.
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
    public function configurationTableName($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('configuration.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('configuration.tableName', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method configuration.pk.
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
    public function configurationPk($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('configuration.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('configuration.pk', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method configuration.pkOption.
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
    public function configurationPkOption($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('configuration.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('configuration.pkOption', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method dcheck.get.
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
    public function dcheckGet($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('dcheck.get', self::$anonymousFunctions, true);

        // request
        return $this->request('dcheck.get', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method dcheck.isReadable.
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
    public function dcheckIsReadable($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('dcheck.isReadable', self::$anonymousFunctions, true);

        // request
        return $this->request('dcheck.isReadable', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method dcheck.isWritable.
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
    public function dcheckIsWritable($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('dcheck.isWritable', self::$anonymousFunctions, true);

        // request
        return $this->request('dcheck.isWritable', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method dcheck.tableName.
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
    public function dcheckTableName($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('dcheck.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('dcheck.tableName', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method dcheck.pk.
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
    public function dcheckPk($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('dcheck.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('dcheck.pk', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method dcheck.pkOption.
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
    public function dcheckPkOption($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('dcheck.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('dcheck.pkOption', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method dhost.get.
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
    public function dhostGet($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('dhost.get', self::$anonymousFunctions, true);

        // request
        return $this->request('dhost.get', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method dhost.exists.
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
    public function dhostExists($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('dhost.exists', self::$anonymousFunctions, true);

        // request
        return $this->request('dhost.exists', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method dhost.tableName.
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
    public function dhostTableName($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('dhost.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('dhost.tableName', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method dhost.pk.
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
    public function dhostPk($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('dhost.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('dhost.pk', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method dhost.pkOption.
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
    public function dhostPkOption($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('dhost.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('dhost.pkOption', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method discoveryrule.get.
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
    public function discoveryruleGet($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('discoveryrule.get', self::$anonymousFunctions, true);

        // request
        return $this->request('discoveryrule.get', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method discoveryrule.exists.
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
    public function discoveryruleExists($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('discoveryrule.exists', self::$anonymousFunctions, true);

        // request
        return $this->request('discoveryrule.exists', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method discoveryrule.create.
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
    public function discoveryruleCreate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('discoveryrule.create', self::$anonymousFunctions, true);

        // request
        return $this->request('discoveryrule.create', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method discoveryrule.update.
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
    public function discoveryruleUpdate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('discoveryrule.update', self::$anonymousFunctions, true);

        // request
        return $this->request('discoveryrule.update', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method discoveryrule.delete.
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
    public function discoveryruleDelete($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('discoveryrule.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('discoveryrule.delete', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method discoveryrule.copy.
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
    public function discoveryruleCopy($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('discoveryrule.copy', self::$anonymousFunctions, true);

        // request
        return $this->request('discoveryrule.copy', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method discoveryrule.syncTemplates.
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
    public function discoveryruleSyncTemplates($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('discoveryrule.syncTemplates', self::$anonymousFunctions, true);

        // request
        return $this->request('discoveryrule.syncTemplates', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method discoveryrule.isReadable.
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
    public function discoveryruleIsReadable($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('discoveryrule.isReadable', self::$anonymousFunctions, true);

        // request
        return $this->request('discoveryrule.isReadable', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method discoveryrule.isWritable.
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
    public function discoveryruleIsWritable($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('discoveryrule.isWritable', self::$anonymousFunctions, true);

        // request
        return $this->request('discoveryrule.isWritable', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method discoveryrule.findInterfaceForItem.
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
    public function discoveryruleFindInterfaceForItem($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('discoveryrule.findInterfaceForItem', self::$anonymousFunctions, true);

        // request
        return $this->request('discoveryrule.findInterfaceForItem', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method discoveryrule.tableName.
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
    public function discoveryruleTableName($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('discoveryrule.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('discoveryrule.tableName', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method discoveryrule.pk.
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
    public function discoveryrulePk($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('discoveryrule.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('discoveryrule.pk', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method discoveryrule.pkOption.
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
    public function discoveryrulePkOption($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('discoveryrule.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('discoveryrule.pkOption', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method drule.get.
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
    public function druleGet($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('drule.get', self::$anonymousFunctions, true);

        // request
        return $this->request('drule.get', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method drule.exists.
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
    public function druleExists($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('drule.exists', self::$anonymousFunctions, true);

        // request
        return $this->request('drule.exists', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method drule.checkInput.
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
    public function druleCheckInput($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('drule.checkInput', self::$anonymousFunctions, true);

        // request
        return $this->request('drule.checkInput', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method drule.create.
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
    public function druleCreate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('drule.create', self::$anonymousFunctions, true);

        // request
        return $this->request('drule.create', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method drule.update.
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
    public function druleUpdate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('drule.update', self::$anonymousFunctions, true);

        // request
        return $this->request('drule.update', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method drule.delete.
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
    public function druleDelete($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('drule.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('drule.delete', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method drule.isReadable.
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
    public function druleIsReadable($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('drule.isReadable', self::$anonymousFunctions, true);

        // request
        return $this->request('drule.isReadable', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method drule.isWritable.
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
    public function druleIsWritable($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('drule.isWritable', self::$anonymousFunctions, true);

        // request
        return $this->request('drule.isWritable', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method drule.tableName.
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
    public function druleTableName($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('drule.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('drule.tableName', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method drule.pk.
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
    public function drulePk($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('drule.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('drule.pk', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method drule.pkOption.
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
    public function drulePkOption($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('drule.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('drule.pkOption', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method dservice.get.
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
    public function dserviceGet($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('dservice.get', self::$anonymousFunctions, true);

        // request
        return $this->request('dservice.get', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method dservice.exists.
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
    public function dserviceExists($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('dservice.exists', self::$anonymousFunctions, true);

        // request
        return $this->request('dservice.exists', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method dservice.tableName.
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
    public function dserviceTableName($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('dservice.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('dservice.tableName', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method dservice.pk.
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
    public function dservicePk($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('dservice.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('dservice.pk', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method dservice.pkOption.
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
    public function dservicePkOption($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('dservice.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('dservice.pkOption', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method event.get.
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
    public function eventGet($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('event.get', self::$anonymousFunctions, true);

        // request
        return $this->request('event.get', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method event.acknowledge.
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
    public function eventAcknowledge($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('event.acknowledge', self::$anonymousFunctions, true);

        // request
        return $this->request('event.acknowledge', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method event.tableName.
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
    public function eventTableName($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('event.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('event.tableName', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method event.pk.
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
    public function eventPk($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('event.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('event.pk', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method event.pkOption.
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
    public function eventPkOption($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('event.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('event.pkOption', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method graph.get.
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
    public function graphGet($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('graph.get', self::$anonymousFunctions, true);

        // request
        return $this->request('graph.get', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method graph.syncTemplates.
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
    public function graphSyncTemplates($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('graph.syncTemplates', self::$anonymousFunctions, true);

        // request
        return $this->request('graph.syncTemplates', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method graph.delete.
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
    public function graphDelete($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('graph.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('graph.delete', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method graph.update.
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
    public function graphUpdate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('graph.update', self::$anonymousFunctions, true);

        // request
        return $this->request('graph.update', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method graph.create.
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
    public function graphCreate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('graph.create', self::$anonymousFunctions, true);

        // request
        return $this->request('graph.create', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method graph.exists.
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
    public function graphExists($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('graph.exists', self::$anonymousFunctions, true);

        // request
        return $this->request('graph.exists', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method graph.getObjects.
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
    public function graphGetObjects($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('graph.getObjects', self::$anonymousFunctions, true);

        // request
        return $this->request('graph.getObjects', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method graph.tableName.
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
    public function graphTableName($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('graph.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('graph.tableName', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method graph.pk.
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
    public function graphPk($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('graph.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('graph.pk', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method graph.pkOption.
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
    public function graphPkOption($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('graph.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('graph.pkOption', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method graphitem.get.
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
    public function graphitemGet($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('graphitem.get', self::$anonymousFunctions, true);

        // request
        return $this->request('graphitem.get', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method graphitem.tableName.
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
    public function graphitemTableName($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('graphitem.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('graphitem.tableName', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method graphitem.pk.
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
    public function graphitemPk($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('graphitem.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('graphitem.pk', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method graphitem.pkOption.
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
    public function graphitemPkOption($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('graphitem.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('graphitem.pkOption', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method graphprototype.get.
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
    public function graphprototypeGet($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('graphprototype.get', self::$anonymousFunctions, true);

        // request
        return $this->request('graphprototype.get', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method graphprototype.syncTemplates.
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
    public function graphprototypeSyncTemplates($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('graphprototype.syncTemplates', self::$anonymousFunctions, true);

        // request
        return $this->request('graphprototype.syncTemplates', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method graphprototype.delete.
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
    public function graphprototypeDelete($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('graphprototype.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('graphprototype.delete', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method graphprototype.update.
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
    public function graphprototypeUpdate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('graphprototype.update', self::$anonymousFunctions, true);

        // request
        return $this->request('graphprototype.update', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method graphprototype.create.
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
    public function graphprototypeCreate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('graphprototype.create', self::$anonymousFunctions, true);

        // request
        return $this->request('graphprototype.create', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method graphprototype.exists.
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
    public function graphprototypeExists($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('graphprototype.exists', self::$anonymousFunctions, true);

        // request
        return $this->request('graphprototype.exists', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method graphprototype.getObjects.
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
    public function graphprototypeGetObjects($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('graphprototype.getObjects', self::$anonymousFunctions, true);

        // request
        return $this->request('graphprototype.getObjects', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method graphprototype.tableName.
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
    public function graphprototypeTableName($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('graphprototype.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('graphprototype.tableName', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method graphprototype.pk.
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
    public function graphprototypePk($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('graphprototype.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('graphprototype.pk', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method graphprototype.pkOption.
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
    public function graphprototypePkOption($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('graphprototype.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('graphprototype.pkOption', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method host.get.
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
    public function hostGet($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('host.get', self::$anonymousFunctions, true);

        // request
        return $this->request('host.get', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method host.getObjects.
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
    public function hostGetObjects($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('host.getObjects', self::$anonymousFunctions, true);

        // request
        return $this->request('host.getObjects', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method host.exists.
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
    public function hostExists($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('host.exists', self::$anonymousFunctions, true);

        // request
        return $this->request('host.exists', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method host.create.
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
    public function hostCreate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('host.create', self::$anonymousFunctions, true);

        // request
        return $this->request('host.create', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method host.update.
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
    public function hostUpdate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('host.update', self::$anonymousFunctions, true);

        // request
        return $this->request('host.update', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method host.massAdd.
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
    public function hostMassAdd($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('host.massAdd', self::$anonymousFunctions, true);

        // request
        return $this->request('host.massAdd', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method host.massUpdate.
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
    public function hostMassUpdate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('host.massUpdate', self::$anonymousFunctions, true);

        // request
        return $this->request('host.massUpdate', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method host.massRemove.
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
    public function hostMassRemove($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('host.massRemove', self::$anonymousFunctions, true);

        // request
        return $this->request('host.massRemove', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method host.delete.
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
    public function hostDelete($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('host.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('host.delete', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method host.isReadable.
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
    public function hostIsReadable($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('host.isReadable', self::$anonymousFunctions, true);

        // request
        return $this->request('host.isReadable', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method host.isWritable.
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
    public function hostIsWritable($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('host.isWritable', self::$anonymousFunctions, true);

        // request
        return $this->request('host.isWritable', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method host.tableName.
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
    public function hostTableName($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('host.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('host.tableName', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method host.pk.
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
    public function hostPk($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('host.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('host.pk', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method host.pkOption.
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
    public function hostPkOption($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('host.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('host.pkOption', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method hostgroup.get.
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
    public function hostgroupGet($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostgroup.get', self::$anonymousFunctions, true);

        // request
        return $this->request('hostgroup.get', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method hostgroup.getObjects.
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
    public function hostgroupGetObjects($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostgroup.getObjects', self::$anonymousFunctions, true);

        // request
        return $this->request('hostgroup.getObjects', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method hostgroup.exists.
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
    public function hostgroupExists($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostgroup.exists', self::$anonymousFunctions, true);

        // request
        return $this->request('hostgroup.exists', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method hostgroup.create.
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
    public function hostgroupCreate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostgroup.create', self::$anonymousFunctions, true);

        // request
        return $this->request('hostgroup.create', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method hostgroup.update.
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
    public function hostgroupUpdate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostgroup.update', self::$anonymousFunctions, true);

        // request
        return $this->request('hostgroup.update', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method hostgroup.delete.
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
    public function hostgroupDelete($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostgroup.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('hostgroup.delete', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method hostgroup.massAdd.
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
    public function hostgroupMassAdd($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostgroup.massAdd', self::$anonymousFunctions, true);

        // request
        return $this->request('hostgroup.massAdd', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method hostgroup.massRemove.
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
    public function hostgroupMassRemove($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostgroup.massRemove', self::$anonymousFunctions, true);

        // request
        return $this->request('hostgroup.massRemove', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method hostgroup.massUpdate.
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
    public function hostgroupMassUpdate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostgroup.massUpdate', self::$anonymousFunctions, true);

        // request
        return $this->request('hostgroup.massUpdate', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method hostgroup.isReadable.
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
    public function hostgroupIsReadable($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostgroup.isReadable', self::$anonymousFunctions, true);

        // request
        return $this->request('hostgroup.isReadable', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method hostgroup.isWritable.
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
    public function hostgroupIsWritable($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostgroup.isWritable', self::$anonymousFunctions, true);

        // request
        return $this->request('hostgroup.isWritable', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method hostgroup.tableName.
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
    public function hostgroupTableName($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostgroup.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('hostgroup.tableName', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method hostgroup.pk.
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
    public function hostgroupPk($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostgroup.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('hostgroup.pk', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method hostgroup.pkOption.
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
    public function hostgroupPkOption($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostgroup.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('hostgroup.pkOption', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method hostprototype.get.
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
    public function hostprototypeGet($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostprototype.get', self::$anonymousFunctions, true);

        // request
        return $this->request('hostprototype.get', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method hostprototype.create.
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
    public function hostprototypeCreate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostprototype.create', self::$anonymousFunctions, true);

        // request
        return $this->request('hostprototype.create', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method hostprototype.update.
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
    public function hostprototypeUpdate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostprototype.update', self::$anonymousFunctions, true);

        // request
        return $this->request('hostprototype.update', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method hostprototype.syncTemplates.
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
    public function hostprototypeSyncTemplates($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostprototype.syncTemplates', self::$anonymousFunctions, true);

        // request
        return $this->request('hostprototype.syncTemplates', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method hostprototype.delete.
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
    public function hostprototypeDelete($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostprototype.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('hostprototype.delete', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method hostprototype.isReadable.
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
    public function hostprototypeIsReadable($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostprototype.isReadable', self::$anonymousFunctions, true);

        // request
        return $this->request('hostprototype.isReadable', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method hostprototype.isWritable.
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
    public function hostprototypeIsWritable($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostprototype.isWritable', self::$anonymousFunctions, true);

        // request
        return $this->request('hostprototype.isWritable', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method hostprototype.tableName.
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
    public function hostprototypeTableName($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostprototype.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('hostprototype.tableName', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method hostprototype.pk.
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
    public function hostprototypePk($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostprototype.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('hostprototype.pk', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method hostprototype.pkOption.
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
    public function hostprototypePkOption($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostprototype.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('hostprototype.pkOption', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method history.get.
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
    public function historyGet($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('history.get', self::$anonymousFunctions, true);

        // request
        return $this->request('history.get', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method history.tableName.
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
    public function historyTableName($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('history.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('history.tableName', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method history.pk.
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
    public function historyPk($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('history.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('history.pk', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method history.pkOption.
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
    public function historyPkOption($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('history.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('history.pkOption', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method hostinterface.get.
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
    public function hostinterfaceGet($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostinterface.get', self::$anonymousFunctions, true);

        // request
        return $this->request('hostinterface.get', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method hostinterface.exists.
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
    public function hostinterfaceExists($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostinterface.exists', self::$anonymousFunctions, true);

        // request
        return $this->request('hostinterface.exists', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method hostinterface.checkInput.
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
    public function hostinterfaceCheckInput($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostinterface.checkInput', self::$anonymousFunctions, true);

        // request
        return $this->request('hostinterface.checkInput', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method hostinterface.create.
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
    public function hostinterfaceCreate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostinterface.create', self::$anonymousFunctions, true);

        // request
        return $this->request('hostinterface.create', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method hostinterface.update.
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
    public function hostinterfaceUpdate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostinterface.update', self::$anonymousFunctions, true);

        // request
        return $this->request('hostinterface.update', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method hostinterface.delete.
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
    public function hostinterfaceDelete($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostinterface.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('hostinterface.delete', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method hostinterface.massAdd.
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
    public function hostinterfaceMassAdd($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostinterface.massAdd', self::$anonymousFunctions, true);

        // request
        return $this->request('hostinterface.massAdd', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method hostinterface.massRemove.
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
    public function hostinterfaceMassRemove($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostinterface.massRemove', self::$anonymousFunctions, true);

        // request
        return $this->request('hostinterface.massRemove', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method hostinterface.replaceHostInterfaces.
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
    public function hostinterfaceReplaceHostInterfaces($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostinterface.replaceHostInterfaces', self::$anonymousFunctions, true);

        // request
        return $this->request('hostinterface.replaceHostInterfaces', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method hostinterface.tableName.
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
    public function hostinterfaceTableName($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostinterface.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('hostinterface.tableName', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method hostinterface.pk.
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
    public function hostinterfacePk($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostinterface.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('hostinterface.pk', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method hostinterface.pkOption.
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
    public function hostinterfacePkOption($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('hostinterface.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('hostinterface.pkOption', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method image.get.
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
    public function imageGet($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('image.get', self::$anonymousFunctions, true);

        // request
        return $this->request('image.get', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method image.getObjects.
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
    public function imageGetObjects($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('image.getObjects', self::$anonymousFunctions, true);

        // request
        return $this->request('image.getObjects', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method image.exists.
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
    public function imageExists($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('image.exists', self::$anonymousFunctions, true);

        // request
        return $this->request('image.exists', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method image.create.
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
    public function imageCreate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('image.create', self::$anonymousFunctions, true);

        // request
        return $this->request('image.create', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method image.update.
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
    public function imageUpdate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('image.update', self::$anonymousFunctions, true);

        // request
        return $this->request('image.update', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method image.delete.
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
    public function imageDelete($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('image.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('image.delete', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method image.tableName.
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
    public function imageTableName($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('image.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('image.tableName', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method image.pk.
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
    public function imagePk($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('image.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('image.pk', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method image.pkOption.
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
    public function imagePkOption($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('image.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('image.pkOption', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method iconmap.get.
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
    public function iconmapGet($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('iconmap.get', self::$anonymousFunctions, true);

        // request
        return $this->request('iconmap.get', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method iconmap.create.
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
    public function iconmapCreate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('iconmap.create', self::$anonymousFunctions, true);

        // request
        return $this->request('iconmap.create', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method iconmap.update.
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
    public function iconmapUpdate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('iconmap.update', self::$anonymousFunctions, true);

        // request
        return $this->request('iconmap.update', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method iconmap.delete.
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
    public function iconmapDelete($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('iconmap.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('iconmap.delete', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method iconmap.isReadable.
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
    public function iconmapIsReadable($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('iconmap.isReadable', self::$anonymousFunctions, true);

        // request
        return $this->request('iconmap.isReadable', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method iconmap.isWritable.
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
    public function iconmapIsWritable($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('iconmap.isWritable', self::$anonymousFunctions, true);

        // request
        return $this->request('iconmap.isWritable', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method iconmap.tableName.
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
    public function iconmapTableName($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('iconmap.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('iconmap.tableName', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method iconmap.pk.
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
    public function iconmapPk($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('iconmap.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('iconmap.pk', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method iconmap.pkOption.
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
    public function iconmapPkOption($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('iconmap.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('iconmap.pkOption', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method item.get.
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
    public function itemGet($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('item.get', self::$anonymousFunctions, true);

        // request
        return $this->request('item.get', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method item.getObjects.
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
    public function itemGetObjects($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('item.getObjects', self::$anonymousFunctions, true);

        // request
        return $this->request('item.getObjects', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method item.exists.
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
    public function itemExists($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('item.exists', self::$anonymousFunctions, true);

        // request
        return $this->request('item.exists', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method item.create.
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
    public function itemCreate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('item.create', self::$anonymousFunctions, true);

        // request
        return $this->request('item.create', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method item.update.
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
    public function itemUpdate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('item.update', self::$anonymousFunctions, true);

        // request
        return $this->request('item.update', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method item.delete.
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
    public function itemDelete($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('item.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('item.delete', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method item.syncTemplates.
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
    public function itemSyncTemplates($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('item.syncTemplates', self::$anonymousFunctions, true);

        // request
        return $this->request('item.syncTemplates', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method item.validateInventoryLinks.
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
    public function itemValidateInventoryLinks($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('item.validateInventoryLinks', self::$anonymousFunctions, true);

        // request
        return $this->request('item.validateInventoryLinks', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method item.addRelatedObjects.
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
    public function itemAddRelatedObjects($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('item.addRelatedObjects', self::$anonymousFunctions, true);

        // request
        return $this->request('item.addRelatedObjects', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method item.findInterfaceForItem.
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
    public function itemFindInterfaceForItem($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('item.findInterfaceForItem', self::$anonymousFunctions, true);

        // request
        return $this->request('item.findInterfaceForItem', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method item.isReadable.
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
    public function itemIsReadable($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('item.isReadable', self::$anonymousFunctions, true);

        // request
        return $this->request('item.isReadable', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method item.isWritable.
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
    public function itemIsWritable($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('item.isWritable', self::$anonymousFunctions, true);

        // request
        return $this->request('item.isWritable', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method item.tableName.
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
    public function itemTableName($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('item.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('item.tableName', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method item.pk.
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
    public function itemPk($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('item.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('item.pk', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method item.pkOption.
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
    public function itemPkOption($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('item.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('item.pkOption', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method itemprototype.get.
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
    public function itemprototypeGet($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('itemprototype.get', self::$anonymousFunctions, true);

        // request
        return $this->request('itemprototype.get', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method itemprototype.exists.
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
    public function itemprototypeExists($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('itemprototype.exists', self::$anonymousFunctions, true);

        // request
        return $this->request('itemprototype.exists', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method itemprototype.create.
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
    public function itemprototypeCreate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('itemprototype.create', self::$anonymousFunctions, true);

        // request
        return $this->request('itemprototype.create', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method itemprototype.update.
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
    public function itemprototypeUpdate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('itemprototype.update', self::$anonymousFunctions, true);

        // request
        return $this->request('itemprototype.update', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method itemprototype.delete.
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
    public function itemprototypeDelete($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('itemprototype.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('itemprototype.delete', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method itemprototype.syncTemplates.
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
    public function itemprototypeSyncTemplates($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('itemprototype.syncTemplates', self::$anonymousFunctions, true);

        // request
        return $this->request('itemprototype.syncTemplates', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method itemprototype.addRelatedObjects.
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
    public function itemprototypeAddRelatedObjects($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('itemprototype.addRelatedObjects', self::$anonymousFunctions, true);

        // request
        return $this->request('itemprototype.addRelatedObjects', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method itemprototype.findInterfaceForItem.
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
    public function itemprototypeFindInterfaceForItem($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('itemprototype.findInterfaceForItem', self::$anonymousFunctions, true);

        // request
        return $this->request('itemprototype.findInterfaceForItem', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method itemprototype.isReadable.
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
    public function itemprototypeIsReadable($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('itemprototype.isReadable', self::$anonymousFunctions, true);

        // request
        return $this->request('itemprototype.isReadable', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method itemprototype.isWritable.
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
    public function itemprototypeIsWritable($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('itemprototype.isWritable', self::$anonymousFunctions, true);

        // request
        return $this->request('itemprototype.isWritable', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method itemprototype.tableName.
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
    public function itemprototypeTableName($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('itemprototype.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('itemprototype.tableName', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method itemprototype.pk.
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
    public function itemprototypePk($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('itemprototype.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('itemprototype.pk', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method itemprototype.pkOption.
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
    public function itemprototypePkOption($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('itemprototype.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('itemprototype.pkOption', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method maintenance.get.
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
    public function maintenanceGet($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('maintenance.get', self::$anonymousFunctions, true);

        // request
        return $this->request('maintenance.get', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method maintenance.exists.
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
    public function maintenanceExists($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('maintenance.exists', self::$anonymousFunctions, true);

        // request
        return $this->request('maintenance.exists', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method maintenance.create.
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
    public function maintenanceCreate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('maintenance.create', self::$anonymousFunctions, true);

        // request
        return $this->request('maintenance.create', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method maintenance.update.
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
    public function maintenanceUpdate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('maintenance.update', self::$anonymousFunctions, true);

        // request
        return $this->request('maintenance.update', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method maintenance.delete.
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
    public function maintenanceDelete($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('maintenance.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('maintenance.delete', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method maintenance.tableName.
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
    public function maintenanceTableName($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('maintenance.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('maintenance.tableName', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method maintenance.pk.
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
    public function maintenancePk($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('maintenance.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('maintenance.pk', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method maintenance.pkOption.
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
    public function maintenancePkOption($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('maintenance.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('maintenance.pkOption', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method map.get.
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
    public function mapGet($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('map.get', self::$anonymousFunctions, true);

        // request
        return $this->request('map.get', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method map.getObjects.
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
    public function mapGetObjects($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('map.getObjects', self::$anonymousFunctions, true);

        // request
        return $this->request('map.getObjects', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method map.exists.
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
    public function mapExists($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('map.exists', self::$anonymousFunctions, true);

        // request
        return $this->request('map.exists', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method map.checkInput.
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
    public function mapCheckInput($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('map.checkInput', self::$anonymousFunctions, true);

        // request
        return $this->request('map.checkInput', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method map.create.
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
    public function mapCreate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('map.create', self::$anonymousFunctions, true);

        // request
        return $this->request('map.create', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method map.update.
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
    public function mapUpdate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('map.update', self::$anonymousFunctions, true);

        // request
        return $this->request('map.update', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method map.delete.
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
    public function mapDelete($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('map.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('map.delete', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method map.isReadable.
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
    public function mapIsReadable($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('map.isReadable', self::$anonymousFunctions, true);

        // request
        return $this->request('map.isReadable', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method map.isWritable.
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
    public function mapIsWritable($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('map.isWritable', self::$anonymousFunctions, true);

        // request
        return $this->request('map.isWritable', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method map.checkCircleSelementsLink.
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
    public function mapCheckCircleSelementsLink($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('map.checkCircleSelementsLink', self::$anonymousFunctions, true);

        // request
        return $this->request('map.checkCircleSelementsLink', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method map.tableName.
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
    public function mapTableName($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('map.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('map.tableName', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method map.pk.
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
    public function mapPk($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('map.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('map.pk', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method map.pkOption.
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
    public function mapPkOption($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('map.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('map.pkOption', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method mediatype.get.
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
    public function mediatypeGet($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('mediatype.get', self::$anonymousFunctions, true);

        // request
        return $this->request('mediatype.get', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method mediatype.create.
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
    public function mediatypeCreate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('mediatype.create', self::$anonymousFunctions, true);

        // request
        return $this->request('mediatype.create', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method mediatype.update.
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
    public function mediatypeUpdate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('mediatype.update', self::$anonymousFunctions, true);

        // request
        return $this->request('mediatype.update', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method mediatype.delete.
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
    public function mediatypeDelete($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('mediatype.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('mediatype.delete', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method mediatype.tableName.
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
    public function mediatypeTableName($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('mediatype.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('mediatype.tableName', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method mediatype.pk.
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
    public function mediatypePk($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('mediatype.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('mediatype.pk', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method mediatype.pkOption.
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
    public function mediatypePkOption($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('mediatype.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('mediatype.pkOption', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method proxy.get.
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
    public function proxyGet($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('proxy.get', self::$anonymousFunctions, true);

        // request
        return $this->request('proxy.get', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method proxy.create.
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
    public function proxyCreate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('proxy.create', self::$anonymousFunctions, true);

        // request
        return $this->request('proxy.create', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method proxy.update.
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
    public function proxyUpdate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('proxy.update', self::$anonymousFunctions, true);

        // request
        return $this->request('proxy.update', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method proxy.delete.
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
    public function proxyDelete($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('proxy.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('proxy.delete', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method proxy.isReadable.
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
    public function proxyIsReadable($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('proxy.isReadable', self::$anonymousFunctions, true);

        // request
        return $this->request('proxy.isReadable', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method proxy.isWritable.
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
    public function proxyIsWritable($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('proxy.isWritable', self::$anonymousFunctions, true);

        // request
        return $this->request('proxy.isWritable', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method proxy.tableName.
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
    public function proxyTableName($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('proxy.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('proxy.tableName', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method proxy.pk.
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
    public function proxyPk($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('proxy.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('proxy.pk', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method proxy.pkOption.
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
    public function proxyPkOption($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('proxy.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('proxy.pkOption', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method service.get.
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
    public function serviceGet($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('service.get', self::$anonymousFunctions, true);

        // request
        return $this->request('service.get', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method service.create.
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
    public function serviceCreate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('service.create', self::$anonymousFunctions, true);

        // request
        return $this->request('service.create', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method service.validateUpdate.
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
    public function serviceValidateUpdate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('service.validateUpdate', self::$anonymousFunctions, true);

        // request
        return $this->request('service.validateUpdate', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method service.update.
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
    public function serviceUpdate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('service.update', self::$anonymousFunctions, true);

        // request
        return $this->request('service.update', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method service.validateDelete.
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
    public function serviceValidateDelete($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('service.validateDelete', self::$anonymousFunctions, true);

        // request
        return $this->request('service.validateDelete', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method service.delete.
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
    public function serviceDelete($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('service.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('service.delete', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method service.addDependencies.
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
    public function serviceAddDependencies($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('service.addDependencies', self::$anonymousFunctions, true);

        // request
        return $this->request('service.addDependencies', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method service.deleteDependencies.
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
    public function serviceDeleteDependencies($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('service.deleteDependencies', self::$anonymousFunctions, true);

        // request
        return $this->request('service.deleteDependencies', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method service.validateAddTimes.
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
    public function serviceValidateAddTimes($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('service.validateAddTimes', self::$anonymousFunctions, true);

        // request
        return $this->request('service.validateAddTimes', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method service.addTimes.
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
    public function serviceAddTimes($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('service.addTimes', self::$anonymousFunctions, true);

        // request
        return $this->request('service.addTimes', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method service.getSla.
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
    public function serviceGetSla($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('service.getSla', self::$anonymousFunctions, true);

        // request
        return $this->request('service.getSla', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method service.deleteTimes.
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
    public function serviceDeleteTimes($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('service.deleteTimes', self::$anonymousFunctions, true);

        // request
        return $this->request('service.deleteTimes', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method service.isReadable.
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
    public function serviceIsReadable($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('service.isReadable', self::$anonymousFunctions, true);

        // request
        return $this->request('service.isReadable', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method service.isWritable.
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
    public function serviceIsWritable($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('service.isWritable', self::$anonymousFunctions, true);

        // request
        return $this->request('service.isWritable', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method service.tableName.
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
    public function serviceTableName($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('service.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('service.tableName', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method service.pk.
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
    public function servicePk($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('service.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('service.pk', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method service.pkOption.
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
    public function servicePkOption($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('service.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('service.pkOption', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method screen.get.
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
    public function screenGet($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('screen.get', self::$anonymousFunctions, true);

        // request
        return $this->request('screen.get', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method screen.exists.
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
    public function screenExists($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('screen.exists', self::$anonymousFunctions, true);

        // request
        return $this->request('screen.exists', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method screen.create.
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
    public function screenCreate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('screen.create', self::$anonymousFunctions, true);

        // request
        return $this->request('screen.create', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method screen.update.
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
    public function screenUpdate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('screen.update', self::$anonymousFunctions, true);

        // request
        return $this->request('screen.update', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method screen.delete.
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
    public function screenDelete($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('screen.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('screen.delete', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method screen.tableName.
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
    public function screenTableName($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('screen.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('screen.tableName', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method screen.pk.
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
    public function screenPk($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('screen.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('screen.pk', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method screen.pkOption.
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
    public function screenPkOption($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('screen.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('screen.pkOption', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method screenitem.get.
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
    public function screenitemGet($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('screenitem.get', self::$anonymousFunctions, true);

        // request
        return $this->request('screenitem.get', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method screenitem.create.
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
    public function screenitemCreate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('screenitem.create', self::$anonymousFunctions, true);

        // request
        return $this->request('screenitem.create', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method screenitem.update.
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
    public function screenitemUpdate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('screenitem.update', self::$anonymousFunctions, true);

        // request
        return $this->request('screenitem.update', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method screenitem.updateByPosition.
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
    public function screenitemUpdateByPosition($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('screenitem.updateByPosition', self::$anonymousFunctions, true);

        // request
        return $this->request('screenitem.updateByPosition', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method screenitem.delete.
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
    public function screenitemDelete($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('screenitem.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('screenitem.delete', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method screenitem.isReadable.
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
    public function screenitemIsReadable($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('screenitem.isReadable', self::$anonymousFunctions, true);

        // request
        return $this->request('screenitem.isReadable', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method screenitem.isWritable.
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
    public function screenitemIsWritable($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('screenitem.isWritable', self::$anonymousFunctions, true);

        // request
        return $this->request('screenitem.isWritable', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method screenitem.tableName.
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
    public function screenitemTableName($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('screenitem.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('screenitem.tableName', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method screenitem.pk.
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
    public function screenitemPk($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('screenitem.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('screenitem.pk', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method screenitem.pkOption.
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
    public function screenitemPkOption($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('screenitem.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('screenitem.pkOption', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method script.get.
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
    public function scriptGet($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('script.get', self::$anonymousFunctions, true);

        // request
        return $this->request('script.get', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method script.create.
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
    public function scriptCreate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('script.create', self::$anonymousFunctions, true);

        // request
        return $this->request('script.create', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method script.update.
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
    public function scriptUpdate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('script.update', self::$anonymousFunctions, true);

        // request
        return $this->request('script.update', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method script.delete.
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
    public function scriptDelete($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('script.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('script.delete', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method script.execute.
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
    public function scriptExecute($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('script.execute', self::$anonymousFunctions, true);

        // request
        return $this->request('script.execute', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method script.getScriptsByHosts.
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
    public function scriptGetScriptsByHosts($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('script.getScriptsByHosts', self::$anonymousFunctions, true);

        // request
        return $this->request('script.getScriptsByHosts', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method script.tableName.
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
    public function scriptTableName($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('script.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('script.tableName', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method script.pk.
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
    public function scriptPk($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('script.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('script.pk', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method script.pkOption.
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
    public function scriptPkOption($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('script.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('script.pkOption', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method template.pkOption.
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
    public function templatePkOption($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('template.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('template.pkOption', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method template.get.
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
    public function templateGet($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('template.get', self::$anonymousFunctions, true);

        // request
        return $this->request('template.get', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method template.getObjects.
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
    public function templateGetObjects($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('template.getObjects', self::$anonymousFunctions, true);

        // request
        return $this->request('template.getObjects', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method template.exists.
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
    public function templateExists($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('template.exists', self::$anonymousFunctions, true);

        // request
        return $this->request('template.exists', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method template.create.
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
    public function templateCreate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('template.create', self::$anonymousFunctions, true);

        // request
        return $this->request('template.create', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method template.update.
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
    public function templateUpdate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('template.update', self::$anonymousFunctions, true);

        // request
        return $this->request('template.update', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method template.delete.
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
    public function templateDelete($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('template.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('template.delete', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method template.massAdd.
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
    public function templateMassAdd($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('template.massAdd', self::$anonymousFunctions, true);

        // request
        return $this->request('template.massAdd', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method template.massUpdate.
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
    public function templateMassUpdate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('template.massUpdate', self::$anonymousFunctions, true);

        // request
        return $this->request('template.massUpdate', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method template.massRemove.
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
    public function templateMassRemove($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('template.massRemove', self::$anonymousFunctions, true);

        // request
        return $this->request('template.massRemove', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method template.isReadable.
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
    public function templateIsReadable($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('template.isReadable', self::$anonymousFunctions, true);

        // request
        return $this->request('template.isReadable', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method template.isWritable.
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
    public function templateIsWritable($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('template.isWritable', self::$anonymousFunctions, true);

        // request
        return $this->request('template.isWritable', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method template.tableName.
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
    public function templateTableName($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('template.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('template.tableName', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method template.pk.
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
    public function templatePk($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('template.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('template.pk', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method templatescreen.get.
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
    public function templatescreenGet($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('templatescreen.get', self::$anonymousFunctions, true);

        // request
        return $this->request('templatescreen.get', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method templatescreen.exists.
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
    public function templatescreenExists($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('templatescreen.exists', self::$anonymousFunctions, true);

        // request
        return $this->request('templatescreen.exists', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method templatescreen.copy.
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
    public function templatescreenCopy($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('templatescreen.copy', self::$anonymousFunctions, true);

        // request
        return $this->request('templatescreen.copy', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method templatescreen.update.
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
    public function templatescreenUpdate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('templatescreen.update', self::$anonymousFunctions, true);

        // request
        return $this->request('templatescreen.update', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method templatescreen.create.
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
    public function templatescreenCreate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('templatescreen.create', self::$anonymousFunctions, true);

        // request
        return $this->request('templatescreen.create', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method templatescreen.delete.
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
    public function templatescreenDelete($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('templatescreen.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('templatescreen.delete', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method templatescreen.tableName.
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
    public function templatescreenTableName($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('templatescreen.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('templatescreen.tableName', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method templatescreen.pk.
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
    public function templatescreenPk($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('templatescreen.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('templatescreen.pk', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method templatescreen.pkOption.
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
    public function templatescreenPkOption($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('templatescreen.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('templatescreen.pkOption', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method templatescreenitem.get.
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
    public function templatescreenitemGet($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('templatescreenitem.get', self::$anonymousFunctions, true);

        // request
        return $this->request('templatescreenitem.get', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method templatescreenitem.tableName.
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
    public function templatescreenitemTableName($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('templatescreenitem.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('templatescreenitem.tableName', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method templatescreenitem.pk.
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
    public function templatescreenitemPk($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('templatescreenitem.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('templatescreenitem.pk', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method templatescreenitem.pkOption.
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
    public function templatescreenitemPkOption($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('templatescreenitem.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('templatescreenitem.pkOption', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method trigger.get.
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
    public function triggerGet($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('trigger.get', self::$anonymousFunctions, true);

        // request
        return $this->request('trigger.get', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method trigger.getObjects.
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
    public function triggerGetObjects($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('trigger.getObjects', self::$anonymousFunctions, true);

        // request
        return $this->request('trigger.getObjects', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method trigger.exists.
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
    public function triggerExists($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('trigger.exists', self::$anonymousFunctions, true);

        // request
        return $this->request('trigger.exists', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method trigger.checkInput.
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
    public function triggerCheckInput($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('trigger.checkInput', self::$anonymousFunctions, true);

        // request
        return $this->request('trigger.checkInput', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method trigger.create.
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
    public function triggerCreate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('trigger.create', self::$anonymousFunctions, true);

        // request
        return $this->request('trigger.create', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method trigger.update.
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
    public function triggerUpdate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('trigger.update', self::$anonymousFunctions, true);

        // request
        return $this->request('trigger.update', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method trigger.delete.
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
    public function triggerDelete($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('trigger.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('trigger.delete', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method trigger.addDependencies.
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
    public function triggerAddDependencies($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('trigger.addDependencies', self::$anonymousFunctions, true);

        // request
        return $this->request('trigger.addDependencies', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method trigger.deleteDependencies.
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
    public function triggerDeleteDependencies($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('trigger.deleteDependencies', self::$anonymousFunctions, true);

        // request
        return $this->request('trigger.deleteDependencies', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method trigger.syncTemplates.
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
    public function triggerSyncTemplates($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('trigger.syncTemplates', self::$anonymousFunctions, true);

        // request
        return $this->request('trigger.syncTemplates', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method trigger.syncTemplateDependencies.
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
    public function triggerSyncTemplateDependencies($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('trigger.syncTemplateDependencies', self::$anonymousFunctions, true);

        // request
        return $this->request('trigger.syncTemplateDependencies', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method trigger.isReadable.
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
    public function triggerIsReadable($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('trigger.isReadable', self::$anonymousFunctions, true);

        // request
        return $this->request('trigger.isReadable', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method trigger.isWritable.
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
    public function triggerIsWritable($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('trigger.isWritable', self::$anonymousFunctions, true);

        // request
        return $this->request('trigger.isWritable', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method trigger.tableName.
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
    public function triggerTableName($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('trigger.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('trigger.tableName', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method trigger.pk.
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
    public function triggerPk($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('trigger.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('trigger.pk', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method trigger.pkOption.
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
    public function triggerPkOption($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('trigger.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('trigger.pkOption', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method triggerprototype.get.
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
    public function triggerprototypeGet($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('triggerprototype.get', self::$anonymousFunctions, true);

        // request
        return $this->request('triggerprototype.get', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method triggerprototype.create.
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
    public function triggerprototypeCreate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('triggerprototype.create', self::$anonymousFunctions, true);

        // request
        return $this->request('triggerprototype.create', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method triggerprototype.update.
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
    public function triggerprototypeUpdate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('triggerprototype.update', self::$anonymousFunctions, true);

        // request
        return $this->request('triggerprototype.update', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method triggerprototype.delete.
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
    public function triggerprototypeDelete($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('triggerprototype.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('triggerprototype.delete', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method triggerprototype.syncTemplates.
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
    public function triggerprototypeSyncTemplates($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('triggerprototype.syncTemplates', self::$anonymousFunctions, true);

        // request
        return $this->request('triggerprototype.syncTemplates', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method triggerprototype.tableName.
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
    public function triggerprototypeTableName($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('triggerprototype.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('triggerprototype.tableName', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method triggerprototype.pk.
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
    public function triggerprototypePk($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('triggerprototype.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('triggerprototype.pk', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method triggerprototype.pkOption.
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
    public function triggerprototypePkOption($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('triggerprototype.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('triggerprototype.pkOption', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method user.get.
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
    public function userGet($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('user.get', self::$anonymousFunctions, true);

        // request
        return $this->request('user.get', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method user.create.
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
    public function userCreate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('user.create', self::$anonymousFunctions, true);

        // request
        return $this->request('user.create', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method user.update.
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
    public function userUpdate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('user.update', self::$anonymousFunctions, true);

        // request
        return $this->request('user.update', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method user.updateProfile.
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
    public function userUpdateProfile($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('user.updateProfile', self::$anonymousFunctions, true);

        // request
        return $this->request('user.updateProfile', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method user.delete.
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
    public function userDelete($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('user.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('user.delete', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method user.addMedia.
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
    public function userAddMedia($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('user.addMedia', self::$anonymousFunctions, true);

        // request
        return $this->request('user.addMedia', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method user.updateMedia.
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
    public function userUpdateMedia($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('user.updateMedia', self::$anonymousFunctions, true);

        // request
        return $this->request('user.updateMedia', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method user.deleteMedia.
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
    public function userDeleteMedia($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('user.deleteMedia', self::$anonymousFunctions, true);

        // request
        return $this->request('user.deleteMedia', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method user.deleteMediaReal.
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
    public function userDeleteMediaReal($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('user.deleteMediaReal', self::$anonymousFunctions, true);

        // request
        return $this->request('user.deleteMediaReal', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method user.checkAuthentication.
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
    public function userCheckAuthentication($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('user.checkAuthentication', self::$anonymousFunctions, true);

        // request
        return $this->request('user.checkAuthentication', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method user.isReadable.
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
    public function userIsReadable($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('user.isReadable', self::$anonymousFunctions, true);

        // request
        return $this->request('user.isReadable', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method user.isWritable.
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
    public function userIsWritable($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('user.isWritable', self::$anonymousFunctions, true);

        // request
        return $this->request('user.isWritable', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method user.tableName.
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
    public function userTableName($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('user.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('user.tableName', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method user.pk.
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
    public function userPk($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('user.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('user.pk', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method user.pkOption.
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
    public function userPkOption($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('user.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('user.pkOption', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method usergroup.get.
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
    public function usergroupGet($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('usergroup.get', self::$anonymousFunctions, true);

        // request
        return $this->request('usergroup.get', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method usergroup.getObjects.
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
    public function usergroupGetObjects($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('usergroup.getObjects', self::$anonymousFunctions, true);

        // request
        return $this->request('usergroup.getObjects', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method usergroup.exists.
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
    public function usergroupExists($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('usergroup.exists', self::$anonymousFunctions, true);

        // request
        return $this->request('usergroup.exists', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method usergroup.create.
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
    public function usergroupCreate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('usergroup.create', self::$anonymousFunctions, true);

        // request
        return $this->request('usergroup.create', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method usergroup.update.
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
    public function usergroupUpdate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('usergroup.update', self::$anonymousFunctions, true);

        // request
        return $this->request('usergroup.update', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method usergroup.massAdd.
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
    public function usergroupMassAdd($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('usergroup.massAdd', self::$anonymousFunctions, true);

        // request
        return $this->request('usergroup.massAdd', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method usergroup.massUpdate.
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
    public function usergroupMassUpdate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('usergroup.massUpdate', self::$anonymousFunctions, true);

        // request
        return $this->request('usergroup.massUpdate', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method usergroup.delete.
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
    public function usergroupDelete($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('usergroup.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('usergroup.delete', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method usergroup.isReadable.
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
    public function usergroupIsReadable($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('usergroup.isReadable', self::$anonymousFunctions, true);

        // request
        return $this->request('usergroup.isReadable', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method usergroup.isWritable.
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
    public function usergroupIsWritable($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('usergroup.isWritable', self::$anonymousFunctions, true);

        // request
        return $this->request('usergroup.isWritable', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method usergroup.tableName.
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
    public function usergroupTableName($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('usergroup.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('usergroup.tableName', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method usergroup.pk.
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
    public function usergroupPk($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('usergroup.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('usergroup.pk', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method usergroup.pkOption.
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
    public function usergroupPkOption($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('usergroup.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('usergroup.pkOption', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method usermacro.get.
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
    public function usermacroGet($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('usermacro.get', self::$anonymousFunctions, true);

        // request
        return $this->request('usermacro.get', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method usermacro.createGlobal.
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
    public function usermacroCreateGlobal($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('usermacro.createGlobal', self::$anonymousFunctions, true);

        // request
        return $this->request('usermacro.createGlobal', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method usermacro.updateGlobal.
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
    public function usermacroUpdateGlobal($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('usermacro.updateGlobal', self::$anonymousFunctions, true);

        // request
        return $this->request('usermacro.updateGlobal', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method usermacro.deleteGlobal.
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
    public function usermacroDeleteGlobal($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('usermacro.deleteGlobal', self::$anonymousFunctions, true);

        // request
        return $this->request('usermacro.deleteGlobal', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method usermacro.create.
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
    public function usermacroCreate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('usermacro.create', self::$anonymousFunctions, true);

        // request
        return $this->request('usermacro.create', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method usermacro.update.
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
    public function usermacroUpdate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('usermacro.update', self::$anonymousFunctions, true);

        // request
        return $this->request('usermacro.update', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method usermacro.delete.
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
    public function usermacroDelete($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('usermacro.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('usermacro.delete', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method usermacro.replaceMacros.
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
    public function usermacroReplaceMacros($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('usermacro.replaceMacros', self::$anonymousFunctions, true);

        // request
        return $this->request('usermacro.replaceMacros', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method usermacro.tableName.
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
    public function usermacroTableName($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('usermacro.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('usermacro.tableName', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method usermacro.pk.
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
    public function usermacroPk($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('usermacro.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('usermacro.pk', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method usermacro.pkOption.
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
    public function usermacroPkOption($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('usermacro.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('usermacro.pkOption', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method usermedia.get.
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
    public function usermediaGet($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('usermedia.get', self::$anonymousFunctions, true);

        // request
        return $this->request('usermedia.get', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method usermedia.tableName.
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
    public function usermediaTableName($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('usermedia.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('usermedia.tableName', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method usermedia.pk.
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
    public function usermediaPk($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('usermedia.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('usermedia.pk', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method usermedia.pkOption.
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
    public function usermediaPkOption($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('usermedia.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('usermedia.pkOption', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method httptest.get.
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
    public function httptestGet($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('httptest.get', self::$anonymousFunctions, true);

        // request
        return $this->request('httptest.get', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method httptest.create.
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
    public function httptestCreate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('httptest.create', self::$anonymousFunctions, true);

        // request
        return $this->request('httptest.create', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method httptest.update.
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
    public function httptestUpdate($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('httptest.update', self::$anonymousFunctions, true);

        // request
        return $this->request('httptest.update', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method httptest.delete.
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
    public function httptestDelete($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('httptest.delete', self::$anonymousFunctions, true);

        // request
        return $this->request('httptest.delete', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method httptest.isReadable.
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
    public function httptestIsReadable($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('httptest.isReadable', self::$anonymousFunctions, true);

        // request
        return $this->request('httptest.isReadable', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method httptest.isWritable.
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
    public function httptestIsWritable($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('httptest.isWritable', self::$anonymousFunctions, true);

        // request
        return $this->request('httptest.isWritable', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method httptest.tableName.
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
    public function httptestTableName($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('httptest.tableName', self::$anonymousFunctions, true);

        // request
        return $this->request('httptest.tableName', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method httptest.pk.
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
    public function httptestPk($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('httptest.pk', self::$anonymousFunctions, true);

        // request
        return $this->request('httptest.pk', $params, $arrayKeyProperty, $auth);
    }

    /**
     * Reqeusts the Zabbix API and returns the response of the API
     *          method httptest.pkOption.
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
    public function httptestPkOption($params = [], $arrayKeyProperty = '')
    {
        // get params array for request
        $params = $this->getRequestParamsArray($params);

        // check if we've to authenticate
        $auth = !in_array('httptest.pkOption', self::$anonymousFunctions, true);

        // request
        return $this->request('httptest.pkOption', $params, $arrayKeyProperty, $auth);
    }

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
