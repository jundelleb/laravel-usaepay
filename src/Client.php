<?php 

namespace PhpUsaepay;

use Config;
use Exception;
use Illuminate\Support\Arr;
use SoapClient;
use PhpUsaepay\ServerSwitcher;
use Illuminate\Support\Arr;

class Client
{
	/**
	 * SoapClient.
	 *
	 * @var SoapClient
	 */
	protected $soap;

	/**
	 * USAepay's source key
	 *
	 * @var string
	 */
	protected $sourceKey;

	/**
	 * USAepay's source pin
	 *
	 * @var string
	 */
	protected $sourcePin;

	/**
	 * Sandbox Mode
	 *
	 * @var boolean
	 */
	protected $sandboxMode = false;

	/**
	 * Debug Mode
	 *
	 * @var boolean
	 */
	protected $debug = false;

	/**
	 * Configs
	 *
	 * @return array
	 */
	protected $config;

	/**
	 * Constructor
	 *
	 * @param string $sourceKey
	 * @param string $sourcePin
	 * @param boolean $sandboxMode
	 * @param array $options
	 *
	 */
	public function __construct($sourceKey = null, $sourcePin = null, $sandboxMode = false, $options = array())
	{
		$this->config = Config::get('usaepay');
		// When no arguments or atleast sourcekey is not being passed,
		// let's assume the default in config file
		if (is_null($sourceKey) || func_num_args() == 0) {
			$sourceKey = $this->config['key'];
			$sourcePin = $this->config['pin'];
			$sandboxMode = $this->config['sandbox'];
		}

		$this->sourceKey = $sourceKey;
		$this->sourcePin = $sourcePin;
		$this->sandboxMode = $sandboxMode;

		$options = array_merge($this->defaultOptions(), $options);

		if(isset($options['debug']) && $options['debug'] == true) {
			$this->debug = true;
		}
	}

	/**
	 * Execute the request to the server
	 *
	 * @param string $method
	 * @param array $parameters
	 */
    public function __call($method, $parameters)
    {
    	array_unshift($parameters, $this->token());
    	$result = $this->soap()->__soapCall($method, $parameters);

    	if(is_soap_fault($result))
    		return;

    	return $this->sanitize($result);
    }

    /**
	 * Clean result data from response
	 *
	 * @param mixed $data
	 * @return array
     */
    protected function sanitize($data)
    {
    	return json_decode(json_encode($data), true);
    }

	/**
	 * Retrieve WSDL uri
	 *
	 * @return string
	 */
    protected function wsdl()
    {
    	return $this->config['proto'] . '://' . $this->domain() . $this->config['wsdl'];
    }

    /**
	 * Returns the url
	 *
	 * @return string
	 */
    public function url()
    {
    	return $this->config['proto'] . '://' . $this->domain();
    }

    /**
	 * Returns the domain
	 *
	 * @return string
	 */
    public function domain()
    {
    	if($this->sandboxMode) {
    		$servers = Arr::only($this->config['server'], 'sandbox');
    		$switcher = new ServerSwitcher($servers);

    		return $switcher->activeDomain();
    	}

    	$servers = Arr::except($this->config['server'], 'sandbox');
    	$switcher = new ServerSwitcher($servers);

    	return $switcher->activeDomain();
    }

	/**
	 * Create SoapClient instance
	 *
	 * @return SoapClient
	 */
    protected function soap()
    {
    	if($this->soap) return $this->soap;

    	$options = [
		    'trace' => $this->debug,  
		    'exceptions' => $this->debug,
		    'encoding' => 'UTF-8'
		];

    	return $this->soap = new SoapClient($this->wsdl(), $options);
    }

    /**
     * Default configuration
     *
     * @return array
     */
    protected function defaultOptions()
    {
    	return [
    		'debug' => $this->config['debug'],
    		'clientIp' => '',
    	];
    }

    /**
	 * Create token Object for server request
	 *
	 * @return array
	 */
    protected function token()
    {
        // Generate random seed value
        $seed = time() . rand();
        // Client options
        $options = $this->defaultOptions();
        // Encryption method
        $encryptType = $this->config['encryption'];

        if( ! in_array($encryptType, ['sha1', 'md5'])) {
        	throw new Exception("Encryption method '{$encryptType}' is currently not supported.");
        }
        
        // make hash value using the encryption method provided.
        $hashData = $this->sourceKey . $seed . $this->sourcePin;
        
        $hash = call_user_func($encryptType, $hashData);

        // assembly ueSecurityToken as an array
        return [
            'SourceKey' => $this->sourceKey,
            'PinHash' => [
                'Type' => $encryptType,
                'Seed' => $seed,
                'HashValue' => $hash
            ],
            'ClientIP' => $options['clientIp'],
        ];
    }
}
