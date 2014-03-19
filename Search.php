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

function trace($var)
{
	echo ($var);
	echo "\n";
}
function escaparObjeto($obj){
	trace("escapando val:");
	foreach ($obj as $key=> &$value) {
	   trace("escapando :".$key.' '.$value.' '.$obj->$key.' '.gettype($value));
	   if(gettype($value)=='boolean'){
		if(is_null($value))$obj->key='';
		elseif($value==TRUE)$obj->key='TRUE';
		else $obj->key='FALSE';
	   }
	   if(is_numeric($value)){
		trace("escapando :".$key.' '.$value.' '.$obj->$key.' '.gettype($value));
		continue;
	   }
	   else{
		if(is_null($value)){
			$obj->$key='-';
		}
		else $obj->$key=addslashes($value);
	   }
	}
   return $obj;
}
function tostring($obj){
	$sql='';
	foreach ($obj as $key=> &$value) {
		$sql.='"'.addslashes($value).'",';
	}
	$sql=substr($sql,0,strlen($sql)-1);

	$sql='('.$sql.')';
   return $sql;

}

class DBBase {
	/*
	   public $user="githubapi";
	   public $password='1q2w3e4r';
	   public $server='localhost';
	   public $DB='githubapi';
	*/

	protected $mysqli;
	public function __construct()
	{
		/*
		   $this->user="githubapi";
		   $this->password='1q2w3e4r';
		   $this->server='127.0.0.1';
		   $this->DB='githubapi';
		*/
	}

	function connect()
	{
		$user = "root";
		$password = '1q2w3e4r';
		$server = '127.0.0.1';
		$DB = 'githubapi';
		$this->mysqli = new mysqli(null, $user, $password, $DB);

		/* check connection */
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			return false;
		}
		return true;
	}
	/*
	   i	la variable correspondiente es de tipo entero
	   d	la variable correspondiente es de tipo double
	   s	la variable correspondiente es de tipo string
	   b	la variable correspondiente es un blob y se envía en paquetes
	*/
}
class DBOwner extends DBBase {
	function save($owner)
	{
		try{
			if ($this->connect()) {
				$sql = 'REPLACE INTO owner(id,login,url) VALUES (?,?,?)';
				if ($stmt = $this->mysqli->prepare($sql)) {
					$stmt->bind_param("iss", $owner->id, $owner->login, $owner->url);
					$res=$stmt->execute();
					trace("Owner ".$owner->id." guardado en BD con resultado ".$res);
					return $res;
				}
			}
		}
		catch (Exception $e) {
			echo 'Excepción capturada: ',  $e->getMessage(), "\n";
		}
		return false;
	}
}
class DBRepositorio extends DBBase {
	/**
	 * Graba un registro en la BD
	 */
	function save($owner)
	{
	try{
		if ($this->connect()) {
			$sql = 'INSERT INTO repositorio(id, name, full_name, owner_id, private, html_url, description, fork, url, forks_url, created_at, updated_at, pushed_at, git_url, svn_url, homepage, size, stargazers_count, watchers_count, language, has_issues, has_downloads, has_wiki, forks_count, mirror_url, open_issues_count, forks, open_issues, watchers, default_branch, master_branch, score,json) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
			if ($stmt = $this->mysqli->prepare($sql)) {
				if ($stmt->bind_param("issiississssssssiiisiiiisiiiissds", $owner->id , $owner->name , $owner->full_name , $owner->owner_id , $owner->private , $owner->html_url , $owner->description , $owner->fork , $owner->url , $owner->forks_url , $owner->created_at , $owner->updated_at , $owner->pushed_at , $owner->git_url , $owner->svn_url , $owner->homepage , $owner->size , $owner->stargazers_count , $owner->watchers_count , $owner->language , $owner->has_issues , $owner->has_downloads , $owner->has_wiki , $owner->forks_count , $owner->mirror_url , $owner->open_issues_count , $owner->forks , $owner->open_issues , $owner->watchers , $owner->default_branch , $owner->master_branch , $owner->score, $owner->json)) {
					$res = $stmt->execute();
					trace(" Repositorio ".$owner->id." guardado en BD con resultado ".$res);
					return  $res;
				} else echo 'Error Bind Param';
			} else echo 'Error prepare';
		} else echo 'Error connect';
		}
		catch (Exception $e) {
			echo 'Excepción capturada: ',  $e->getMessage(), "\n";
		}
	}

	/**
	 * Guarda Todos los registros en la lista
	 */
	function saveAll($lstowner){
		if(count($lstowner)>0){
	try{
		if ($this->connect()) {
			$sql ='INSERT INTO repositorio(id, name, full_name, owner_id, private, html_url, description, fork, url, forks_url, created_at, updated_at, pushed_at, git_url, svn_url, homepage, size, stargazers_count, watchers_count, language, has_issues, has_downloads, has_wiki, forks_count, mirror_url, open_issues_count, forks, open_issues, watchers, default_branch, master_branch, score,json) VALUES ';

				foreach ($lstowner as $item) {
					$item->json='';
					$sql.=tostring($item).',';
				}
				$sql=substr($sql,0, strlen($sql)-1);

				$res=$this->mysqli->query($sql);
				if($res>0) trace(" Guardando ".count($lstowner)." en BD con resultado ".$res);
				else{
					trace("Error al guardar repositorios ".$this->mysqli->error);
					trace($sql);
				}
				return  $res;
		} else echo 'Error connect';
		}
		catch (Exception $e) {
			echo 'Excepción capturada: ',  $e->getMessage(), "\n";
		}
	}
}
}
class Owner {
	public $id;
	public $login;
	public $url;


	function __construct($json){
		$this->id = $json['id'];
		$this->login = $json['login'];
		$this->url = $json['url'];
	}
}
class Repositorio {
	public $id;
	public $name;
	public $full_name;
	public $owner_id;
	public $private;
	public $html_url;
	public $description;
	public $fork;
	public $url;
	public $forks_url;
	public $created_at;
	public $updated_at;
	public $pushed_at;
	public $git_url;
	public $svn_url;
	public $homepage;
	public $size;
	public $stargazers_count;
	public $watchers_count;
	public $language;
	public $has_issues;
	public $has_downloads;
	public $has_wiki;
	public $forks_count;
	public $mirror_url;
	public $open_issues_count;
	public $forks;
	public $open_issues;
	public $watchers;
	public $default_branch;
	public $master_branch;
	public $score;
	public $json;

	function __construct($json)
	{
		$this->id = $json['id'];
		$this->name = $json['name'];
		$this->full_name = $json['full_name'];
		$this->owner_id = $json['owner']['id'];
		$this->private = $json['private'];
		$this->html_url = $json['html_url'];
		$this->description = $json['description'];
		$this->fork = $json['fork'];
		$this->url = $json['url'];
		$this->forks_url = $json['forks_url'];
		$this->created_at = $json['created_at'];
		$this->updated_at = $json['updated_at'];
		$this->pushed_at = $json['pushed_at'];
		$this->git_url = $json['git_url'];
		$this->svn_url = $json['svn_url'];
		$this->homepage = $json['homepage'];
		$this->size = $json['size'];
		$this->stargazers_count = $json['stargazers_count'];
		$this->watchers_count = $json['watchers_count'];
		$this->language = $json['language'];
		$this->has_issues = $json['has_issues'];
		$this->has_downloads = $json['has_downloads'];
		$this->has_wiki = $json['has_wiki'];
		$this->forks_count = $json['forks_count'];
		$this->mirror_url = $json['mirror_url'];
		$this->open_issues_count = $json['open_issues_count'];
		$this->forks = $json['forks'];
		$this->open_issues = $json['open_issues'];
		$this->watchers = $json['watchers'];
		$this->default_branch = $json['default_branch'];
		$this->master_branch = $json['master_branch'];
		$this->score = $json['score'];
	}
}

class Search {
	public $username;
	public $password;

	private $RLLimit;
	private $RLRemaining;
	private $RLReset;

	private $next;
	private $urlbase = "https://api.github.com/";

	private $DBRepo;
	private $DBOwn;
	//Almaceno temporalmente los id registrados
	private $lstownersID=array();
	private $lstrepositoriesId=array();
	private $lstResultados=array();

	private $ultPagina=0;

	//private $total_count            =null;

	private $ultFecha=null;


	public function reset(){
		 $this->lstownersID         =array();
		 $this->lstrepositoriesId   =array();
		 $this->lstResultados       =array();
		 $this->ultPagina           =0;
		 //$this->total_count         =null;
		 $this->ultFecha			=null;
	}
	function __construct()
	{
		$this->username = "gorimentorin";
		$this->password = "1q2w3e4rLOL";
		$this->DBRepo = new DBRepositorio();
		$this->DBOwn = new DBOwner();

		$this->lstownersID=array();
		$this->lstrepositoriesId=array();
		$this->lstResultados=array();
	}

	function getURL($url, &$header, &$body)
	{
		trace("Url:".$url);
		$this->next = null;
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
		$headVar = $this->parseHeaders($header);
		try {
			if (isset($headVar["X-RateLimit-Reset"])){
				$this->RLReset = intval($headVar["X-RateLimit-Reset"]);
				$this->RLRemaining = intval($headVar["X-RateLimit-Remaining"]);
				$this->RLLimit = intval($headVar["X-RateLimit-Limit"]);
			}
		} catch (Exception $e) {
			trace("Exception".$e->getMessage());
		}

		if (isset($headVar["Link"])) {
			$infoLink = trim($headVar["Link"]);
			if (strrpos($infoLink,'rel="next"')>0) {
				$links=explode(",",$infoLink);

				foreach ($links as $item){
					if (strpos($item,'rel="next"')>0) {
						$next=explode(";",$item)[0];
						$next=trim($next);
						$this->next=substr($next,1,strlen($next)-2);
					break;
					}
				}
			}
		}
		curl_close($ch);
	}

	function connect()
	{
		if ($this->username == null || $this->password == null) {
			echo "AWEONAO, te falta ingresar usuario y password";
			return null;
		}
		$urlbase = "https://api.github.com/users/";
		$header;
		$body;
		$res = $this->getURL($urlbase . $this->username, $header, $body);

		if ($this->RLLimit == 5000)
			return true;
		else
			return false;
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
	function FindRepositorios($nombre, $opts = null)
	{
		$inicio=time();
		$urlFindRepo = $this->urlbase . "search/repositories?q=" . $nombre.'+';
		$buscar = $nombre;
		$sp="%20";


		if ($opts != null) {
			if(!isset($opts["encode"])){
				trace(count($opts));

				foreach ($opts as &$value) {
					$value=urlencode($value);
				}

				$opts["encode"]=true;
			}
			if (isset($opts['in']))	$urlFindRepo .= $sp."in:" . implode(',', $opts['in']);
			else $urlFindRepo.=$sp."in:name,description,readme,config.xml";
			if (isset($opts['size']))$urlFindRepo .= $sp."size:" . $opts['size'];
			if (isset($opts['forks']))$urlFindRepo .= $sp."forks:" . $opts['forks'];
			if (isset($opts['fork']))$urlFindRepo .= $sp."fork:" . $opts['fork'];
			if (isset($opts['created']))$urlFindRepo .= $sp."created:" . $opts['created'];
			if (isset($opts['pushed']))
				$urlFindRepo .= $sp."pushed:" . $opts['pushed'];
			if (isset($opts['user']))
				$urlFindRepo .= $sp."user:" . $opts['user'];
			if (isset($opts['repo']))
				$urlFindRepo .= $sp."repo:" . $opts['repo'];
			if (isset($opts['language']))
				$urlFindRepo .= $sp."language:" . $opts['language'];
			if (isset($opts['stars']))
				$urlFindRepo .= $sp."stars:" . $opts['stars'];

			if (isset($opts['sort']))$urlFindRepo 	.= "&sort=" . $opts['sort'];
			else $urlFindRepo 	.= "&sort=updated";
			if (isset($opts['order']))$urlFindRepo 	.= "&order=" . $opts['order'];
			else $urlFindRepo 	.= "&order=desc";
		}


		$head;
		$body;

		$pages = 0;
		$urlActual=$urlFindRepo . '&per_page=100';

		$page=0;
		/*
		if($total_count==null){//Para solo hacer una vez la asignacion
			trace("total_count: ".$this->total_count);
			$this->total_count=intval($jsonres['total_count']);
			$this->ultPagina=1;
		}
		*/
		

		do {
			$this->ultPagina++;
			//Obtengo pagina actual
			$res = $this->getURL($urlActual, $head, $body);
			if ($this->RLRemaining == 0) {
			$timerest = $this->RLReset - time();
			trace("Durmiendo " . $timerest);
			if($timerest>0)
			sleep($timerest);
			else sleep(5);
			}

			$jsonres = json_decode($body, true);
			$total_count=intval($jsonres['total_count']);

			trace('Url Actual	: '.$urlActual);
			trace('Url Siguiente: '.$this->next);
			//file_put_contents('file_' . sprintf('%03s', $page) . '.json', $body);
			//file_put_contents('file_' . sprintf('%03s', $page) . '.txt', $head);
			trace('Limite:' . $this->RLLimit);
			trace('Remanente:' . $this->RLRemaining);
			trace('Reset:' . $this->RLReset);
			trace("Pagina :".$this->ultPagina);


			if($jsonres ==null || count($jsonres)<2){
				echo $body;
				die();
			}

			trace("N Items en json: ".count($jsonres['items']));
			$tmp=array();

			if(count($jsonres['items'])==0){
				echo $body;
				die();

			}
			foreach ($jsonres['items'] as  $item) {
				$repo = new Repositorio($item);
				$repo->json = json_encode($item);
				$own=new Owner($item['owner']);
				if($repo->pushed_at!=null)
					$this->ultFecha=substr($repo->pushed_at,0,strpos($repo->pushed_at, 'T'));
/*
				if(in_array ($own->id, $this->lstownersID)) continue;
				else{
					$this->DBOwn->save($own);
					array_push($this->lstownersID, $own->id);
				}
*/
				if(in_array ($repo->id, $this->lstrepositoriesId)) continue;
				else{
					//$this->DBRepo->save($repo);
					array_push($this->lstrepositoriesId, $repo->id);
				}
				array_push($this->lstResultados, $repo);
				array_push($tmp, $repo);
			}
			$this->DBRepo->saveAll($tmp);

			if ($this->RLRemaining == 0) {
				$timerest = $this->RLReset - time();
				trace("Durmiendo " . $timerest);
				if($timerest>0)
				sleep($timerest);
			else sleep(5);
			}
			$page++;
			if($this->next!=null){
				$urlActual=$this->next;
			}
			else break;
			
		} while ($page<10);
		trace("Fin primario".time()-$inicio);
		if(count($tmp)<$total_count){
			trace("Ultimo resultado Fecha actualizacion:".$this->ultFecha);
			$opts['pushed']='>'.$this->ultFecha;
			$this->FindRepositorios($nombre, $opts);
		}
		trace("Fin primario".time()-$inicio);
		trace("Resultado ".count($this->lstResultados));
	}
}
function exception_error_handler($errno, $errstr, $errfile, $errline ) {
	throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}
//set_error_handler("exception_error_handler");

$lol = new Search();
//$lol->connect();
$lol->reset();
$array= array('pushed' => '>2007-01-01' );
$lol->FindRepositorios('phonegap',$array);


?>