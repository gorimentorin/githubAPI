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
private $urlbase="https://api.github.com/";


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
		$headVar=$this->parseHeaders($header);
		//print_r($headVar);
		$this->RLReset=intval($headVar["X-RateLimit-Reset"]);
		$this->RLRemaining=intval($headVar["X-RateLimit-Remaining"]);
		$this->RLLimit= intval($headVar["X-RateLimit-Limit"]);
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
		
		
		
		if($this->RLLimit==5000)return true;
		else return false;

	}

	function parseHeaders($header){
		$tmp=explode("\n",$header);
		$res= array();
		for ($i=0; $i <  count($tmp); $i++) {
			$lin=$tmp[$i];
			$ind=strpos($lin,':');
			if($ind==FALSE)$ind=strpos($lin,' ');
			if($ind!=FALSE){
				$key=substr($lin,0,$ind);
				$val=substr($lin,$ind);
				$res[$key]=$val;
			}
		}
		return $res;

	}
/*
	Search repositories

Find repositories via various criteria. This method returns up to 100 results per page.

GET /
Parameters

Name	Type	Description
q	string	The search keywords, as well as any qualifiers.
sort	string	The sort field. One of stars, forks, or updated. Default: results are sorted by best match.
order	string	The sort order if sort parameter is provided. One of asc or desc. Default: desc
The q search term can also contain any combination of the supported repository search qualifiers:

in Qualifies which fields are searched. With this qualifier you can restrict the search to just the repository name, description, readme, or any combination of these.
	jquery in:name
	Matches repositories with jquery in their name
	jquery in:name,description
	Matches repositories with jquery in their name or description
	jquery in:readme
	Matches repositories mentioning jquery in their README file

size Finds repositories that match a certain size (in kilobytes).
	The size qualifier finds repository's that match a certain size (in kilobytes). For example:
	size:1000
	Matches repositories that are 1 MB exactly
	size:>=30000
	Matches repositories that are at least 30 MB
	size:<50
	Matches repositories that are smaller than 50 KB
	size:50..120
	Matches repositories that are between 50 KB and 120 KB

forks Filters repositories based on the number of forks, and/or whether forked repositories should be included in the results at all.
	forks:5
	Matches repositories with only five forks
	forks:>=205
	Matches repositories that with at least 205 forks
	forks:<90
	Matches repositories with less than 90 forks
	forks:10..20
	Matches repositories with 10 to 20 forks
fork
	github fork:true
	Matches all repositories containing the word "github," including forked ones
	github fork:only
	Matches all repositories that are forked containing the word "github"
	github
	Matches all repositories that contain the word "github," that are not forks
	You can, of course, combine both options:

	forks:>500 fork:only
	Matches repositories with more than 500 forks, and only returns those forks

created or pushed Filters repositories based on times of creation, or when they were last updated.
	You can filter repositories based on times of creation, or when they were last updated. For repository creation, you can use the created qualifier; to find out when a repository was last updated, you'll want to use the pushed qualifier.

	Both takes dates as its parameter, which must be in the format of YYYY-MM-DD--that's year, followed by month, followed by day. You can continue to use < to refer to "before a date," and > as after a date. For example:

	webos created:<2011-01-01
	Matches repositories with the word "webos" that were created before 2011
	css pushed:<2013-02-01
	Matches repositories with the word "css" that were pushed to before February 2013
	case pushed:>=2013-03-06 fork:only
	Matches repositories with the word "case" that were pushed to on or after March 6th, 2013, and that are forks

user or repo Limits searches to a specific user or repository.
	user:github
	Matches repositories from GitHub
	user:mojombo forks:>100
	Matches repositories from @mojombo that have more than 100 forks
language Searches repositories based on the language they’re written in.
	rails language:javascript
	Matches repositories with the word "rails" that are written in JavaScript
stars Searches repositories based on the number of stars.
	You can choose to search repositories based on the number of stars, or watchers, a repository has. For example:
	stars:10..20
	Matches repositories 10 to 20 stars, that are smaller than 1000 KB
	stars:>=500 fork:true language:php
	Matches repositories with the at least 500 stars, including forked ones, that are written in PHP
*/
	function FindRepositorios($nombre,$opts=null){
	 	$urlFindRepo=$this->urlbase."search/repositories?q=".$nombre;
	 	$buscar=$nombre;
	 	if($opts!=null){
	 		$urlFindRepo.='+';
	 		if(isset($opts['in'])) 		$urlFindRepo.=" in:"		.implode(',',$opts['in']);
	 		if(isset($opts['size']))	$urlFindRepo.=" size:"		.$opts['size'];
	 		if(isset($opts['forks']))	$urlFindRepo.=" forks:"		.$opts['forks'];
			if(isset($opts['fork']))	$urlFindRepo.=" fork:"		.$opts['fork'];
			if(isset($opts['created']))	$urlFindRepo.=" created:"	.$opts['created'];
			if(isset($opts['pushed']))	$urlFindRepo.=" pushed:"		.$opts['pushed'];
			if(isset($opts['user']))	$urlFindRepo.=" user:"		.$opts['user'];
			if(isset($opts['repo']))	$urlFindRepo.=" repo:"		.$opts['repo'];
			if(isset($opts['language']))$urlFindRepo.=" language:"	.$opts['language'];
			if(isset($opts['stars']))	$urlFindRepo.=" stars:"		.$opts['stars'];

			if(isset($opts['sort']))	$urlFindRepo.="&sort=".$opts['sort'];
			if(isset($opts['order']))	$urlFindRepo.="&order=".$opts['order'];
			
		}

		$header;
		$body;
		//$res=$this->getURL($urlFindRepo,$header,$body);
		//file_put_contents("data.json", $body);
		//file_put_contents("header.txt", $header);
		$body=file_get_contents("data.json");
		$jsonres=json_decode($body,true);
		
		$total_count=intval($jsonres['total_count']);

		$pages=intval($total_count/100);
		echo $total_count;
		echo $pages;
		$resultados=array();
		print_r($jsonres['items']);
		for ($i=0; $i <count($jsonres['items']); $i++)
			array_push($resultados,$jsonres['items'][$i]);
		
		/*
		for ($j=2; $j <=$pages ; $ji++) { 
			$res=$this->getURL($urlFindRepo.'&page='.$j,$header,$body);
			$jsonres=json_decode($body);
			for ($i=0; $i <count($jsonres->items); $i++)
				array_push($resultados,$jsonres->items[i]);
		}
		*/
		print_r($resultados);
		
	}
}

$lol=new Search();
$lol->connect();
$lol->FindRepositorios('phonegap');


class Owner{
public `id`, `login`, `url`SELECT * FROM `user`


}
?>