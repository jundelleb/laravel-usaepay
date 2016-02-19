<?php 

namespace PhpUsaepay;

use PhpUsaepay\Exceptions\ServerNotFoundException;

class ServerSwitcher
{
	/**
	 * Array of Servers.
	 *
	 * @var array
	 */
	protected $servers;

	/**
	 * Alias/Name of the active server.
	 *
	 * @var string|null
	 */
	protected $active = null;

	/**
	 * Default domain protocol
	 *
	 * @var string
	 */
	protected $defaultProtocol;

	/**
	 * Constructor
	 *
	 * @param array $servers
	 * @param string $defaultProtocol
	 */
	public function __construct($servers = array(), $defaultProtocol = 'https')
	{
		$this->servers = $servers;
		$this->defaultProtocol = $defaultProtocol;
		$this->performTest();
	}

	/**
	 * Get the active server's alias name
	 *
	 * @return string
	 */
	public function active()
	{
		return $this->active;
	}

	/**
	 * Get the active server's domain
	 *
	 * @return string
	 */
	public function activeDomain()
	{
		$serverAlias = $this->active();

		return $this->servers[$serverAlias];
	}

	/**
	 * Perform the server checking test
	 *
	 * @return void
	 */
	protected function performTest()
	{
		foreach($this->servers as $alias => $domain)
		{
			$url = $domain . '/ping';
			// Check if protocol is provided
			if(preg_match('[https|http]', $domain) !== true) {
				$url = $this->defaultProtocol . '://' . $domain . '/ping';
			}

			// If active server found,
			// we are done.
			if($this->ping($url)) {
				$this->active = $alias;
				break;
			}
		}

		if( ! $this->active) {
			throw new ServerNotFoundException('No active USAePay server found.');
		}
	}

	/**
	 * Ping a usaepay server url.
	 *
	 * @param string $url
	 * @return boolean
	*/
	public function ping($url)
	{
		$curl = curl_init();
		// Set some options 
		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $url,
			CURLOPT_SSL_VERIFYPEER => false
		));
		// Send the request & save response to $resp
		$resp = curl_exec($curl);
		// Close request to clear up some resources
		curl_close($curl);

		return strpos($resp, 'UP') !== false;
	}
}