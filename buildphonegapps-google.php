<?php
require_once('Search.php');
class DBinfoPhoneGapps extends DBBase
{
    function save($it)
    {
    	$sql = 'replace into buildphonegapapps (id, url, descripcion, version, qr, android_url, ios_url, winphone_url) VALUES (?,?,?,?,?,?,?,?)';
        
        try
        {
            if ($this->connect())
            {
                if ($stmt = $this->mysqli->prepare($sql))
                {
                    $stmt->bind_param("issdssss", 
                   	 $it->id,
                     $it->url,
                     $it->desc,
                     $it->version,
                     $it->qr,
                     $it->android_url,
                     $it->ios_url,
                     $it->winphone_url);
                    $res = $stmt->execute();
                    if($stmt->error!=null)trace ($stmt->error);
                    else trace("Busqueda guardado en BD con resultado " . $res);
                    return $res;
                }
            }
        }
        catch (Exception $e)
        {
            echo 'Excepción capturada: ', $e->getMessage(), "\n";
        }
        return false;
    }
}

class infoPhoneGapps{
	public $nombre;
	public $version;
	public $desc;
	public $android_url;
	public $ios_url;
	public $winphone_url;
	public $url;
	public $qr;
}
class oli extends Search
{



function filtrarInfo(){
	$urlRoot='https://build.phonegap.com';
	$url=$urlRoot.'/apps/';
	$header;
	$body;
	$db=new DBinfoPhoneGapps();
	for ($i=100000; $i <  999999 ; $i++) { 

		$this->CURLOPT_NOBODY=true;
		$this->getURL($url.$i,$header,$body);
		if( intval($this->HEADER['HTTP/1.1'])!=200){
			trace("URL ".$url.$i.' no existe');
		}
		else{
			$this->CURLOPT_NOBODY=false;
			$this->getURL($url.$i,$header,$body);

			



			/* Use internal libxml errors -- turn on in production, off for debugging */
			libxml_use_internal_errors(true);
			/* Createa a new DomDocument object */
			$dom = new DomDocument;
			/* Load the HTML */
			$dom->loadHTML($body);
			/* Create a new XPath object */
			$xpath = new DomXPath($dom);

			//Xpath nombre //div[@class='details'] //Nombre /H1 version /small descripcion /p[@class="desc]

			//paquetes //div[@class='packages clearfix']/div  //en el class del div esta la plataforma class="pkg ui-block android"
			//a es el link al paquete
			////img[@class="qr-code"]/@src

			$nombre 		= $xpath->query("//div[@class='details']/h1");

			$version 		= $xpath->query("//div[@class='details']/h1/small");
			$desc 			= $xpath->query("//div[@class='details']/p[@class='desc']");
			$android_url	= $xpath->query("//div[@class='packages clearfix']/div[@class='pkg ui-block android']/a");
			$ios_url		= $xpath->query("//div[@class='packages clearfix']/div[@class='pkg ui-block hydration']/a");
			$winphone_url	= $xpath->query("//div[@class='packages clearfix']/div[@class='pkg ui-block winphone']/a");
			$qr				= $xpath->query("//img[@class='qr-code']");
			

			$info=new infoPhoneGapps();
			$info->url=$url.$i;
			$info->id           = $i;
			$info->nombre 		= $nombre->length 		>0?$nombre 		->item(0)->textContent:'';
			$info->version 		= $version->length 		>0?$version		->item(0)->textContent:'';
			$info->desc 		= $desc->length 		>0?$desc   		->item(0)->textContent:'';
			$info->android_url	= $android_url->length 	>0?$urlRoot.$android_url	->item(0)->getAttribute('href') :'';
			$info->ios_url		= $ios_url->length 		>0?$urlRoot.$ios_url		->item(0)->getAttribute('href') :'';
			$info->winphone_url	= $winphone_url->length >0?$urlRoot.$winphone_url->item(0)->getAttribute('href') :'';
			$info->qr			= $qr->length 			>0?$qr 			->item(0)->getAttribute('src') :'';
			$db->save($info);
			


 
		}
		
	}


}
}
$oli=new oli();
$oli->filtrarInfo();
?>