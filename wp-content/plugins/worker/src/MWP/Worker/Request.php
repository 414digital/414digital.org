<?php
/*
 * This file is part of the ManageWP Worker plugin.
 *
 * (c) ManageWP LLC <contact@managewp.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Class MWP_Worker_Request
 */
class MWP_Worker_Request
{

    /**
     * Header that contains the name of the action to execute.
     * Must be compliant with {@link http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html RFC 2616}
     *
     * @var string
     */
    protected $actionHeaderName = 'MWP-Action';

    /**
     * Header that contains the ID of the action to execute.
     * Must be compliant with {@link http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html RFC 2616}
     *
     * @var string
     */
    protected $messageIdHeaderName = 'MWP-Message-ID';

    /**
     * Header that contains message signature.
     * Must be compliant with {@link http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html RFC 2616}
     *
     * @var string
     */
    protected $signatureHeaderName = 'MWP-Signature';

    protected $protocolVersionHeaderName = 'MWP-Protocol';

    /**
     * @var bool
     */
    private $initialized = false;

    /**
     * The GET parameters.
     *
     * @var array
     */
    public $query;

    /**
     * The POST parameters.
     *
     * @var array
     */
    public $request;

    /**
     * The request attributes.
     *
     * @var array
     */
    public $attributes;

    /**
     * The COOKIE parameters.
     *
     * @var array
     */
    public $cookies;

    /**
     * The FILES parameters.
     *
     * @var array
     */
    public $files;

    /**
     * The SERVER parameters.
     *
     * @var array
     */
    public $server;

    /**
     * The raw request body data.
     *
     * @var string
     */
    private $content;

    private $method;

    /**
     * @param array       $query      The GET parameters.
     * @param array       $request    The POST parameters.
     * @param array       $attributes The request attributes.
     * @param array       $cookies    The COOKIE parameters.
     * @param array       $files      The FILES parameters.
     * @param array       $server     The SERVER parameters.
     * @param null|string $content    The raw request body data. If null, it will be lazy-loaded.
     */
    public function __construct($query = array(), $request = array(), $attributes = array(), $cookies = array(), $files = array(), $server = array(), $content = null)
    {
        $this->query      = $query;
        $this->request    = $request;
        $this->attributes = $attributes;
        $this->cookies    = $cookies;
        $this->files      = $files;
        $this->server     = $server;
        $this->content    = $content;
    }

    /**
     * Gets the request method.
     *
     * The method is always an uppercased string.
     *
     * @return string The request method
     */
    public function getMethod()
    {
        if (null === $this->method) {
            $this->method = isset($this->server['REQUEST_METHOD']) ? strtoupper($this->server['REQUEST_METHOD']) : 'GET';
        }

        return $this->method;
    }

    /**
     * Sets the request method.
     *
     * @param string $method
     */
    public function setMethod($method)
    {
        $this->method                   = null;
        $this->server['REQUEST_METHOD'] = $method;
    }

    /**
     * MWP_Worker factory.
     *
     * @return MWP_Worker_Request
     */
    public static function createFromGlobals()
    {
        $request = new self($_GET, $_POST, array(), $_COOKIE, $_FILES, $_SERVER);

        return $request;
    }

    /**
     * @throws RuntimeException If the request is already initialized.
     */
    public function initialize()
    {
        if ($this->initialized) {
            throw new RuntimeException('Request is already initialized.');
        }
        $this->initialized = true;

        $this->attributes['action']        = $this->getHeader($this->actionHeaderName);
        $this->attributes['id']            = $this->getHeader($this->messageIdHeaderName);
        $this->attributes['signature']     = base64_decode($this->getHeader($this->signatureHeaderName));
        $this->attributes['data']          = null;
        $this->attributes['params']        = null;
        $this->attributes['setting']       = null;
        $this->attributes['user']          = null;
        $this->attributes['authenticated'] = false;
        $this->attributes['protocol']      = (int) $this->getHeader($this->protocolVersionHeaderName);

        // Do we have {"params":{...}} inside of body?
        if ($this->isMasterRequest() && is_array($data = json_decode($this->getContent(), true)) && array_key_exists('params', $data)) {
            $this->attributes['data']    = $data;
            $this->attributes['params']  = $data['params'];
            $this->attributes['setting'] = array_key_exists('setting', $data) ? $data['setting'] : null;
            $this->attributes['user']    = (array_key_exists('username', $data) && is_scalar($data['username'])) ? $data['username'] : null;
        }
    }

    /**
     * @return bool
     */
    public function isInitialized()
    {
        return $this->initialized;
    }

    /**
     * @param string $actionHeaderName
     */
    public function setActionHeaderName($actionHeaderName)
    {
        $this->actionHeaderName = $actionHeaderName;
    }

    /**
     * @param string $signatureHeaderName
     */
    public function setSignatureHeaderName($signatureHeaderName)
    {
        $this->signatureHeaderName = $signatureHeaderName;
    }

    /**
     * @param bool $asResource If true, a resource will be returned
     *
     * @return resource|string The request body content or a resource to read the body stream.
     * @throws RuntimeException If attempting to call the method again after getting it as a resource previously.
     */
    public function getContent($asResource = false)
    {
        if (false === $this->content || (true === $asResource && null !== $this->content)) {
            throw new RuntimeException('getContent() can only be called once when using the resource return type.');
        }

        if (true === $asResource) {
            $this->content = false;

            return fopen('php://input', 'rb');
        }

        if (null === $this->content) {
            $this->content = file_get_contents('php://input');
        }

        return $this->content;
    }

    /**
     * @return bool Whether the current request is sent by the master script.
     */
    public function isMasterRequest()
    {
        if ($this->getMethod() !== 'POST') {
            return false;
        }

        $masterHeader = $this->getHeader($this->actionHeaderName);
        if ($masterHeader === null) {
            return false;
        }

        return true;
    }

    /**
     * @return null|string
     */
    public function getAction()
    {
        return $this->attributes['action'];
    }

    /**
     * @return null|string
     */
    public function getSignature()
    {
        return $this->attributes['signature'];
    }

    /**
     * @return null|string
     */
    public function getUsername()
    {
        return $this->attributes['user'];
    }

    public function getNonce()
    {
        return $this->attributes['id'];
    }

    public function getParams()
    {
        return $this->attributes['params'];
    }

    public function getData()
    {
        return $this->attributes['data'];
    }

    public function getSetting()
    {
        return $this->attributes['setting'];
    }

    /**
     * @param string $header Header name.
     *
     * @return string|null Header content, or null if it doesn't exist.
     */
    public function getHeader($header)
    {
        $header = 'HTTP_'.strtoupper(str_replace('-', '_', $header));
        if (isset($this->server[$header])) {
            return $this->server[$header];
        }

        return null;
    }

    /**
     * @return bool
     */
    public function isAuthenticated()
    {
        return $this->attributes['authenticated'];
    }

    public function setAuthenticated($isAuthenticated)
    {
        $this->attributes['authenticated'] = $isAuthenticated;
    }

    /**
     * @return int
     */
    public function getProtocol()
    {
        return $this->attributes['protocol'];
    }
}
