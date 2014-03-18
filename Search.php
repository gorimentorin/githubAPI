<?php

/*
Fecha 17/03/2014
Objetivo: Obtener proyectos desde github, segun una palabra clave ("phonegap")
Asumo que la idea a futuro es bajarlos XD. 
GitHub tiene una API con bastantes ejemplos sobre como hacerlo, pero no hay API oficial en php


Fuentes
http://developer.github.com/guides/getting-started/#authentication
http://developer.github.com/v3/search/ Informacion sobre como buscar usando la API
*/



class Search{

public $username;
public $password;

private $RLLimit;
private $RLRemaining;
private $RLReset;

	function  __construct(){
		$this->username="gorimentorin";
		$this->password="1q2w3e4rLOL";

	}

	function getURL($url,&$header,&$body){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_VERBOSE, 0);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_USERPWD, $this->username . ":" . $this->password);  


		$response = curl_exec($ch);
		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$header = substr($response, 0, $header_size);
		$body = substr($response, $header_size);
		curl_close($ch);

	}

	 function connect(){
		if($this->username==null || $this->password==null){
			echo "AWEONAO, te falta ingresar usuario y password";
			return NULL;
		}
		$urlbase="https://api.github.com/users/";
		$header;
		$body;
		$res=$this->getURL($urlbase.$this->username,$header,$body);
		
		
		$headVar=$this->parseHeaders($header);
		print_r($headVar);
		$this->RLReset=intval($headVar["X-RateLimit-Reset"]);
		$this->RLRemaining=intval($headVar["X-RateLimit-Remaining"]);
		$this->RLLimit= intval($headVar["X-RateLimit-Limit"]);
		if($this->RLLimit==5000)return true;
		else return false;

	}

	function parseHeaders($header){
		$tmp=explode("\n",$header);
		$res= array();
		for ($i=0; $i <  count($tmp); $i++) {
			$ind=explode(":", $tmp[$i]);
			$res[$ind[0]]=$ind[1];
		}
		return $res;

	}
}
$lol=new Search();
$lol->connect();
?>