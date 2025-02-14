<?php

namespace App\service;

use InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RwdGateService

{
    /**
     * @var string
     */
    private $domainName = 'vcms.eu';

    /**
     * @var string affiliateId
     */
    private $affiliateId = null;

    /**
     * @var resource
     */
    private $curlHandle = null;

    /**
     * @var array
     */
    private $sections = array();

    /**
     * @var string
     */
    private $gatePrefix = '';

    /**
     * @var bool
     */
    private $isRawResult = false;

    /**
     * @var string
     */
    private $result = '';

    /**
     * @var array
     */
    public $resultHeaders = array();

    /**
     * @var array
     */
    public $cookies= array();



    /**
     * Initialize RWD Gate library.
     *
     * @param string $_affiliateId Affiliate ID.
     * @param string $gatePrefix Prefix of non-existing directory for mod_rewrite Url processing.
     *
     * @throws InvalidArgumentException
     *
     * @link http://merlinx.pl/vibe_podlaczenie_en.php
     */
    public function __construct($_affiliateId, $gatePrefix = 'RWD')
    {
        if (empty($_affiliateId))
        {
            throw new InvalidArgumentException('$_affiliateId is empty!');
        }
        if (empty($gatePrefix))
        {
            throw new InvalidArgumentException('$gatePrefix is empty!');
        }
        $this->affiliateId = $_affiliateId;
        $this->gatePrefix = $gatePrefix;

    }

    public function __destruct()
    {
        if ($this->curlHandle)
        {
            curl_close($this->curlHandle);
        }
    }

    private function curlInit()
    {
        if (!$this->curlHandle)
        {
            $this->curlHandle = curl_init();
        }
    }

    /**
     * Fetch content from Vcms server.
     *
     * @param array $_widgets List of widget IDs from Admin panel.
     * @throws ErrorException
     */
    public function fetch($_widgets = array())
    {
        $this->curlInit();
        $this->result        = '';
        $this->resultHeaders = array();

        $url = explode('/' . $this->gatePrefix . '/', $_SERVER['REQUEST_URI']);

        if (isset($url[1])){
            $rwdUrl = 'http://' . $this->domainName . '/' . $url[1];
        }
        else
        {
            if(empty($_widgets)){
                $rwdUrl = 'http://' . $this->domainName . '/?' . http_build_query($_GET);
            }
            else{
                $rwdUrl = 'http://' . $this->domainName . '/gate/recParts/'.implode(',', $_widgets).'/';
            }
        }

        $gateScheme = '';
        if (isset($_SERVER['REQUEST_SCHEME']) && !empty($_SERVER['REQUEST_SCHEME']))
        {
            $gateScheme = $_SERVER['REQUEST_SCHEME'] . ':';
        }
        $gateUrl = $gateScheme . '//' . $_SERVER['HTTP_HOST'] . rtrim($url[0], '/') . '/' . $this->gatePrefix . '/';

        $headers = array('Gate-URL: ' . $gateUrl, 'Gate-AffiliateId: '.$this->affiliateId, 'Gate-Prefix: '.$this->gatePrefix);


        $curOptions = array(
            CURLOPT_URL            => $rwdUrl,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_ENCODING       => 'gzip',
            CURLOPT_HEADERFUNCTION => array($this, 'curlHeaderReader') /** @uses rwdGate::curlHeaderReader */
        );


        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            $curOptions[CURLOPT_POSTFIELDS] = http_build_query($_POST);
        }

        if ($cookie = $this->buildCookie()){
            $curOptions[CURLOPT_COOKIE] = $cookie;
        }

        curl_setopt_array($this->curlHandle, $curOptions);

        $res = curl_exec($this->curlHandle);

        if (curl_errno($this->curlHandle)) {
            throw new ErrorException(curl_error($this->curlHandle));
        }

        if (curl_getinfo($this->curlHandle, CURLINFO_HTTP_CODE) == 302)
        {
            //usunięcie podwójnego adresu gate'a w przekierowaniach
            $trimmedUrl = trim($gateUrl,'/');
            $url = curl_getinfo($this->curlHandle, CURLINFO_REDIRECT_URL);
            if (empty($url))
            {
                // PHP < 5.3.7 - nie ma stałej CURLINFO_REDIRECT_URL.
                $url = trim($this->resultHeaders['Location']);
            }
            if (strstr($url, $trimmedUrl))
            {
                $urlParts = explode($trimmedUrl, $url);
                $url = array_shift($urlParts) . $trimmedUrl . array_pop($urlParts);
            }
            header('location:' . $url);
            die();
        }

        if (curl_getinfo($this->curlHandle, CURLINFO_HTTP_CODE) != 200)
        {
            // Status HTTP odpowiedzi zwracany przez Vcms.
            header($_SERVER['SERVER_PROTOCOL'] . ' ' . trim($this->resultHeaders['Status']));
        }

        if (isset($url[1]) || empty($_widgets))
        {
            $this->result = $res;
            $this->parseResult();
        }
        else
        {
            if($this->isRawResult()){
                $this->printRawResult($res);
            }

            $this->sections = json_decode($res, true);
        }
    }

    /**
     * @param $name
     *
     * @return bool|string
     */
    public function getSection($name)
    {
        if (!isset($this->sections[$name]))
        {
            return false;
        }

        return $this->sections[$name];
    }

    /**
     * Get available sections list from result.
     *
     * @return array
     */
    public function getSectionsList()
    {
        if (empty($this->sections))
        {
            return null;
        }
        return array_keys($this->sections);
    }

    /**
     * @return bool
     */
    public function isRawResult()
    {
        $rawHeaders = array('text/css', 'application/javascript; charset=UTF-8', 'application/javascript');
        if(in_array(trim($this->resultHeaders['Content-Type']), $rawHeaders))
        {
            return true;
        }

        //old fallback
        return $this->isRawResult;
    }

    /**
     * Print raw Image/Javascript/CSS/fonts data fetched in request.
     *
     * @param string $_content
     */
    public function printRawResult($_content = null)
    {
        if (ob_get_length())
        {
            // Jeśli jest aktywne buforowanie wyjścia, to kasujemy ewentualną zawartość - zapobiegnie wysłaniu UTF8 BOM doklejonygo do plików źródłowych w części przypadków.
            ob_clean();
        }
        $allowSourceHeaders = array(
            'Content-Type',
            'Last-Modified',
            'Cache-Control',
            'Pragma',
            'Expires'
        );
        foreach (array_intersect_key($this->resultHeaders, array_flip($allowSourceHeaders)) as $headerKey => $headerValue)
        {
            header($headerKey . ':' . $headerValue);
        }

        if($_content === null)
        {
            $_content = $this->result;
        }
        echo $_content;
    }

    /**
     * @param resource $curl
     * @param string   $headerLine
     *
     * @return int
     */
    private function curlHeaderReader(/** @noinspection PhpUnusedParameterInspection */ $curl, $headerLine)
    {
        if (stripos($headerLine, ':') !== false)
        {
            list($headerKey, $headerValue) = explode(':', $headerLine, 2);

            if ($headerKey == 'Set-Cookie')
            {
                $this->setRawCookieValue($headerValue);
            }

            $this->resultHeaders[$headerKey] = $headerValue;
        }
        return strlen($headerLine);
    }

    /**
     * Sets cookies identical to ones that came from Vcms.
     *
     * @param string $rawCookieValue
     *
     * @link https://tools.ietf.org/html/rfc2109
     */
    private function setRawCookieValue($rawCookieValue = '')
    {
        $cookieValue = array(
            'name'     => '',
            'value'    => null,
            'expires'  => null,
            'path'     => null,
            'domain'   => null,
            'secure'   => null,
            'httponly' => null
        );

        foreach (explode('; ', $rawCookieValue) as $i => $cookiePart)
        {
            $cookiePart = trim($cookiePart);

            if (stripos($cookiePart, '=') != false)
            {
                list($partNameRaw, $partValue) = explode('=', $cookiePart, 2);
                $partName = strtolower($partNameRaw);
                if ($i === 0)
                {
                    // First part is always Cookie name and value
                    $cookieValue['name']  = $partNameRaw;
                    $cookieValue['value'] = $partValue;
                }
                elseif ($partName === 'expires')
                {
                    $cookieValue['expires'] = strtotime($partValue);
                }
                elseif (array_key_exists($partName, $cookieValue))
                {
                    $cookieValue[ $partName ] = $partValue;
                }
            }
            else
            {
                if (in_array(strtolower($cookiePart), array('secure', 'httponly')))
                {
                    $cookieValue[ strtolower($cookiePart) ] = true;
                }
            }
        }
        setrawcookie($cookieValue['name'],
            $cookieValue['value'],
            $cookieValue['expires'],
            $cookieValue['path'],
            $cookieValue['domain'],
            $cookieValue['secure'],
            $cookieValue['httponly']);
    }

    /**
     * Remove all cookies - use carefully
     */
    public function noCookies()
    {
        foreach($_COOKIE as $name => $val)
        {
            setcookie($name, '', 1, '/');
            unset($_COOKIE[$name]);
        }
    }

    /**
     * Build cookie for curl
     */
    private function buildCookie()
    {
        if(empty($_COOKIE))
        {
            return false;
        }
        $cookies = array();
        foreach($_COOKIE as $name => $val)
        {
            $cookies[] = $name.'='.$val;
        }
        return implode('; ', $cookies);
    }

    /**
     * Parsing of result from RWD.
     */
    private function parseResult()
    {
        if (substr($this->result, 0, 3) === "\xEF\xBB\xBF")
        {
            // Remove utf8 BOM, if present.
            $this->result = substr($this->result, 3);
        }

        $this->sections = $this->parseSections($this->result);
        if (empty($this->sections))
        {
            $this->isRawResult = true;
            return;
        }

        $nestedSections = array();
        foreach ($this->sections as $section)
        {
            $nestedSections += $this->parseSections($section);
        }
        $this->sections += $nestedSections;
    }

    /**
     * @param string $content
     *
     * @return array
     */
    private function parseSections($content)
    {
        $sections = array();
        $content = explode('<!--[RWD_PART:', $content);

        array_shift($content);  //drop starting trash
        foreach($content as $section)
        {
            list($sectionName, $sectionBody) = explode(']-->', $section, 2);
            list($sectionBody, )  = explode('<!--[/RWD_PART:'.$sectionName.']-->', $sectionBody, 2);
            $sections[$sectionName] = $sectionBody;
        }

        return $sections;
    }

    public function setUrl($_url)
    {
        $this->domainName = $_url;
    }

    public function isIndex()
    {
        return in_array('STEP', $this->getSectionsList()) ? !(int)$this->getSection('STEP') : false;
    }

    public function result()
    {
        return $this->result;
    }

    public function resultHeaders()
    {
        return $this->resultHeaders;
    }
}