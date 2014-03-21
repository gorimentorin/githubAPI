<?php

/*
Clase base para manejar descargas de URL
*/
function trace($var)
{
    echo ($var);
    echo "\n";
}


class DownloadUrl {
	public $username;
	public $password;

	protected $CURLOPT_NOBODY;

	protected $HEADER;

	protected $BODY;

	protected $HEADER_TXT;
	

	function __construct()
	{

		$this->CURLOPT_NOBODY=false;
	}

	function getURL($url)
	{
		trace("Url:".$url);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_VERBOSE, 0);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		if($this->username!=null && $this->password!=null)
		curl_setopt($ch, CURLOPT_USERPWD, $this->username . ":" . $this->password);

		$response 			= curl_exec($ch);
		$header_size 		= curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$this->HEADER_TXT 	= substr($response, 0, $header_size);
		$this->BODY 		= substr($response, $header_size);
		$this->HEADER 		= $this->parseHeaders($this->HEADER_TXT);


		curl_close($ch);
	}



	function parseHeaders($header)
	{
		$tmp = explode("\n", $header);
		$res = array();
		for ($i = 0; $i < count($tmp); $i++) {
			$lin = $tmp[$i];
			$ind = strpos($lin, ':');
			if ($ind == false)
				$ind = strpos($lin, ' ');
			if ($ind != false) {
				$key = substr($lin, 0, $ind);
				$val = substr($lin, $ind + 1);
				$res[$key] = trim($val);
			}
		}
		return $res;
	}
	
}
?>