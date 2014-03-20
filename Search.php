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

require_once("BD.php");

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


		$total_count = null;

		$nResultados=0;

		$DbBusqueda=new DbBusqueda();
		$DBBusquedaRepositorio=new DBresultadobusquedarepositorio();
		$lstResBusqueda = array();
		for ($i=0; $i < 10; $i++) {

			$res = $this->getURL($urlActual, $head, $body);
			file_put_contents('file_' . sprintf('%03s', $i) . '.json', $body);
			file_put_contents('file_' . sprintf('%03s', $i) . '.txt', $head);

			$jsonres = json_decode($body, true);
			if($total_count==NULL){
				$total_count=intval($jsonres["total_count"]);
				$nResultados=0;
				$busqueda=new Busqueda();
				$busqueda->busqueda=$urlActual;
				$busqueda->total_count=$total_count;
				$DbBusqueda->save($busqueda);

			}
			trace('Url Actual	: '.$urlActual);
			trace('Url Siguiente: '.$this->next);

			trace('Limite:' . $this->RLLimit);
			trace('Remanente:' . $this->RLRemaining);
			trace('Reset:' . $this->RLReset);
			trace("Pagina :".$this->ultPagina);

			//SI no hay resultados duermo
			if ($this->RLRemaining == 0) {
				$timerest = $this->RLReset - time();
				trace("Durmiendo " . $timerest);
				if($timerest>0)
				sleep($timerest);
				else sleep(5);
				$i--;
				continue;
			}

			if($jsonres ==null || count($jsonres)<2||count($jsonres['items'])==0){
				trace("Sin Resultados");
				echo $body;
				die();
			}

			trace("N Items en json: ".count($jsonres['items']));
			$tmp=array();


			foreach ($jsonres['items'] as  $item) {
				$nResultados++;
				$repo = new Repositorio($item);
				$repo->json = json_encode($item);
				$own=new Owner($item['owner']);
				if($repo->pushed_at!=null)
					$this->ultFecha=substr($repo->pushed_at,0,strpos($repo->pushed_at, 'T'));

				$resbus=new resultadobusquedarepositorio();
				$resbus->id_repositorio=$repo->id;
				$resbus->id_busqueda=$busqueda->id;
				array_push($lstResBusqueda, $resbus);
				if(in_array ($own->id, $this->lstownersID)) continue;
				else{
					$this->DBOwn->save($own);
					array_push($this->lstownersID, $own->id);
				}

				if(in_array ($repo->id, $this->lstrepositoriesId)) continue;
				else{
					//$this->DBRepo->save($repo);
					array_push($this->lstrepositoriesId, $repo->id);
				}
				array_push($this->lstResultados, $repo);
				array_push($tmp, $repo);
			}
			$this->DBRepo->saveAll($tmp);
			$DBBusquedaRepositorio->saveAll($lstResBusqueda);

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
			if($nResultados>=$total_count)break;

		}

		trace("Resultado ".count($this->lstResultados));
	}
}
function exception_error_handler($errno, $errstr, $errfile, $errline ) {
	throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}
//set_error_handler("exception_error_handler");
/*
class FuerzaBruta extends Thread
{
	public function run() {
		echo "OLI";
	}
}

Hay que wevear para instalarlo en windows
$t=new FuerzaBruta();
$t->start();
*/
$lol = new Search();
//$lol->connect();
$lol->reset();
$array= array('pushed' => '>2007-01-01' );
$lol->FindRepositorios('org.americanbible.biblesearch');
$lol->FindRepositorios('com.mcm.plugins.androidinappbilling');
$lol->FindRepositorios('com.simplec.plugins.videosettings');
$lol->FindRepositorios('com.boyvanderlaak.cordova.plugin.orientationchanger');
$lol->FindRepositorios('com.ohh2ahh.plugins.appavailability');
$lol->FindRepositorios('com.appback.plugins.appback');
$lol->FindRepositorios('com.flexblast.cordova.plugin.assetslib');
$lol->FindRepositorios('mobi.autarky.audioencode');
$lol->FindRepositorios('com.auth0.sdk');
$lol->FindRepositorios('com.badrit.backgroundjs');
$lol->FindRepositorios('com.wizital.plugins.backgroundlocationenabler');
$lol->FindRepositorios('de.appplant.cordova.plugin.backgroundmode');
$lol->FindRepositorios('de.appplant.cordova.plugin.badge');
$lol->FindRepositorios('com.phonegap.plugins.barcodescanner');
$lol->FindRepositorios('com.badrit.base64');
$lol->FindRepositorios('org.apache.cordova.batterystatus');
$lol->FindRepositorios('com.randdusing.bluetoothle');
$lol->FindRepositorios('com.megster.cordova.bluetoothserial');
$lol->FindRepositorios('com.tomvanenckevort.cordova.bluetoothserial');
$lol->FindRepositorios('nl.xservices.plugins.calendar');
$lol->FindRepositorios('genesis.plugins.calendar');
$lol->FindRepositorios('com.badrit.calendar');
$lol->FindRepositorios('nl.xservices.plugins.calendar.berserk');
$lol->FindRepositorios('org.apache.cordova.camera');
$lol->FindRepositorios('org.devgeeks.canvas2imageplugin');
$lol->FindRepositorios('org.apache.cordova.mediacapture');
$lol->FindRepositorios('org.transistorsoft.cordova.backgroundgeolocation');
$lol->FindRepositorios('org.transistorsoft.cordova.backgroundnotification');
$lol->FindRepositorios('com.phonegap.plugins.childbrowser');
$lol->FindRepositorios('com.verso.cordova.clipboard');
$lol->FindRepositorios('org.apache.cordova.console');
$lol->FindRepositorios('hu.dpal.phonegap.plugins.contactnumberpicker');
$lol->FindRepositorios('com.badrit.contactpicker');
$lol->FindRepositorios('com.mobitel.nalaka.piccon');
$lol->FindRepositorios('org.apache.cordova.contacts');
$lol->FindRepositorios('com.performanceactive.plugins.camera');
$lol->FindRepositorios('nl.xservices.plugins.launchmyapp');
$lol->FindRepositorios('de.websector.datepicker');
$lol->FindRepositorios('com.plugin.datepicker');
$lol->FindRepositorios('com.dileep.plugins.datepicker');
$lol->FindRepositorios('org.apache.cordova.device');
$lol->FindRepositorios('org.apache.cordova.devicemotion');
$lol->FindRepositorios('org.apache.cordova.deviceorientation');
$lol->FindRepositorios('com.vliesaputra.deviceinformation');
$lol->FindRepositorios('ch.ti8m.documenthandler');
$lol->FindRepositorios('com.phonegap.plugins.emailcomposer');
$lol->FindRepositorios('com.jcjee.plugins.emailcomposer');
$lol->FindRepositorios('de.appplant.cordova.plugin.emailcomposer');
$lol->FindRepositorios('com.badrit.emailcomposer');
$lol->FindRepositorios('com.blueshirtdesign.cordova.plugin.pgexternalscreen');
$lol->FindRepositorios('com.tricedesigns.ios.externalscreen');
$lol->FindRepositorios('com.phonegap.plugins.facebookconnect');
$lol->FindRepositorios('com.adobe.plugins.fastcanvas');
$lol->FindRepositorios('org.apache.cordova.file');
$lol->FindRepositorios('io.github.pwlin.cordova.plugins.fileopener');
$lol->FindRepositorios('io.github.pwlin.cordova.plugins.fileopener2');
$lol->FindRepositorios('org.apache.cordova.filetransfer');
$lol->FindRepositorios('com.badrit.fileoperations');
$lol->FindRepositorios('nl.xservices.plugins.flashlight');
$lol->FindRepositorios('com.adobe.plugins.gaplugin');
$lol->FindRepositorios('com.location9.dfencing');
$lol->FindRepositorios('org.apache.cordova.geolocation');
$lol->FindRepositorios('org.apache.cordova.globalization');
$lol->FindRepositorios('dk.interface.cordova.plugin.googlenavigate');
$lol->FindRepositorios('de.appplant.cordova.plugin.hiddenstatusbaroverlay');
$lol->FindRepositorios('com.plugins.shortcut');
$lol->FindRepositorios('com.rjfun.cordova.plugin.iad');
$lol->FindRepositorios('com.kickstand.cordova.plugin.iad');
$lol->FindRepositorios('com.synconset.imagepicker');
$lol->FindRepositorios('org.apache.cordova.inappbrowser');
$lol->FindRepositorios('com.alexvillaro.plugins.inapppurchase');
$lol->FindRepositorios('cc.fovea.plugins.inapppurchase');
$lol->FindRepositorios('com.retoglobal.plugins.inapppurchase');
$lol->FindRepositorios('com.rjfun.cordova.plugin.appleiap');
$lol->FindRepositorios('nl.xservices.plugins.insomnia');
$lol->FindRepositorios('com.vladstirbu.cordova.instagram');
$lol->FindRepositorios('com.cleartag.plugins.enablebackgroundlocation');
$lol->FindRepositorios('nl.xservices.plugins.ioswebviewcolor');
$lol->FindRepositorios('com.chariotsolutions.cordova.plugin.keyboard_toolbar_remover');
$lol->FindRepositorios('com.shazron.cordova.plugin.keychainutil');
$lol->FindRepositorios('com.simplec.plugins.localnotification');
$lol->FindRepositorios('de.appplant.cordova.plugin.localnotification');
$lol->FindRepositorios('ihome.cordova.plugin.localnotification');
$lol->FindRepositorios('com.localnotification.gotcakes');
$lol->FindRepositorios('com.rjfun.cordova.plugin.lowlatencyaudio');
$lol->FindRepositorios('com.badrit.macaddress');
$lol->FindRepositorios('org.apache.cordova.media');
$lol->FindRepositorios('dk.gi2.plugins.meteorcordova');
$lol->FindRepositorios('nav.kolo.service');
$lol->FindRepositorios('org.apache.cordova.networkinformation');
$lol->FindRepositorios('com.albahra.plugin.networkinterface');
$lol->FindRepositorios('com.chariotsolutions.nfc.plugin');
$lol->FindRepositorios('org.apache.cordova.dialogs');
$lol->FindRepositorios('com.passslot.cordova.plugin.passbook');
$lol->FindRepositorios('com.teamnemitoff.phonedialer');
$lol->FindRepositorios('hu.dpal.phonegap.plugins.pindialog');
$lol->FindRepositorios('com.simplec.plugins.powermanagement');
$lol->FindRepositorios('com.simonmacdonald.prefs');
$lol->FindRepositorios('de.appplant.cordova.plugin.printer');
$lol->FindRepositorios('com.badrit.printplugin');
$lol->FindRepositorios('com.phonegap.plugins.pushplugin');
$lol->FindRepositorios('com.pushwoosh.plugins.pushwoosh');
$lol->FindRepositorios('com.arnia.plugins.smsbuilder');
$lol->FindRepositorios('info.asankan.phonegap.smsplugin');
$lol->FindRepositorios('nl.xservices.plugins.socialsharing');
$lol->FindRepositorios('de.phonostar.softkeyboard');
$lol->FindRepositorios('hu.dpal.phonegap.plugins.spinnerdialog');
$lol->FindRepositorios('it.mobimentum.phonegapspinnerplugin');
$lol->FindRepositorios('org.apache.cordova.splashscreen');
$lol->FindRepositorios('ch.zhaw.sqlite');
$lol->FindRepositorios('nl.xservices.plugins.sslcertificatechecker');
$lol->FindRepositorios('com.phonegap.plugin.statusbar');
$lol->FindRepositorios('it.y3web.cordova.documentinteraction');
$lol->FindRepositorios('com.ankamagames.plugins.sysinfo');
$lol->FindRepositorios('com.simonmacdonald.telephonenumber');
$lol->FindRepositorios('com.testflightapp.cordovaplugin');
$lol->FindRepositorios('nl.xservices.plugins.toast');
$lol->FindRepositorios('org.common.plugins.updateapp');
$lol->FindRepositorios('org.apache.cordova.vibration');
$lol->FindRepositorios('com.sebible.cordova.videosnapshot');
$lol->FindRepositorios('nl.xservices.plugins.videocaptureplus');
$lol->FindRepositorios('com.dawsonloudon.videoplayer');
$lol->FindRepositorios('ca.purplemad.wallpaper');
$lol->FindRepositorios('net.tunts.webintent');
$lol->FindRepositorios('com.borismus.webintent');
$lol->FindRepositorios('com.ququplay.websocket.websocket');
$lol->FindRepositorios('com.jamiestarke.webviewdebug');
$lol->FindRepositorios('com.polyvi.plugins.weibo');
$lol->FindRepositorios('com.kumbe.phonegap.zoom.zoomcontrol');
$lol->FindRepositorios('<gap:platform name="ios”/>');
$lol->FindRepositorios('<gap:platform name=“android”/>');
$lol->FindRepositorios('cordova.js');
$lol->FindRepositorios('api.phonegap.com');
$lol->FindRepositorios('http://api.phonegap.com/1.0/geolocation');
$lol->FindRepositorios('http://api.phonegap.com/1.0/camera');
$lol->FindRepositorios('http://api.phonegap.com/1.0/battery');
$lol->FindRepositorios('http://api.phonegap.com/1.0/device');
$lol->FindRepositorios('http://api.phonegap.com/1.0/network');
$lol->FindRepositorios('http://api.phonegap.com/1.0/app');
$lol->FindRepositorios('http://api.phonegap.com/1.0/notification');
$lol->FindRepositorios('http://api.phonegap.com/1.0/network');
$lol->FindRepositorios('http://api.phonegap.com/1.0/contacts');
$lol->FindRepositorios('http://api.phonegap.com/1.0/media');
$lol->FindRepositorios('gap:platform=“android"');
$lol->FindRepositorios('name="phonegap-version”');
$lol->FindRepositorios('com.phonegap.plugins.');
$lol->FindRepositorios('com.phonegap.plugins.analytics.');
$lol->FindRepositorios('com.phonegap.plugins.mapkit');
$lol->FindRepositorios('com.phonegap.plugins.barcodescanner');
$lol->FindRepositorios('com.phonegap.plugins.PushPlugin');
$lol->FindRepositorios('com.phonegap.DroidGap');
$lol->FindRepositorios('xmlns:gap');



?>