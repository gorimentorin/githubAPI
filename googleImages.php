<?php
require_once("DownloadUrl.php");
require_once("DBBase.php");

class GoogleImages extends DownloadURl{
	private $userKey='5852476611df6f441651c9a46dc9d75c';
	private $userApi='2rwht170';
	private $url="http://www.kimonolabs.com/api/%s?apikey=%s&q=%s";

	public function buscarImagenes($buscar){
		$turl=sprintf($this->url,$this->userApi,urlencode($this->userKey ),$buscar);
		$this->getUrl($turl);

		file_put_contents("gat.json", $this->BODY);
		die();

		$lstResultados=json_decode($this->BODY,true);
		$db=new DBInfoGoogleImages(); 
		foreach ($lstResultados["results"]["listado"] as $item ) {
			$info=$item["imagen"];

			$InfoG=new InfoGoogleImages();
			if(isset($info["src"])){
				$src=$info["src"];
				$src=substr($src,strpos($src,"data:image"));
				$mime=substr($src,0,strpos($src,";"));
				$InfoG->data=$src;
			}
			else{
				$InfoG->datasrc=$info["data-src"];

			}

			$InfoG->mime=$mime;
			$temp		=array();
			parse_str($info["href"],$temp);
			$InfoG->hrefSite=$temp["imgrefurl"];
			$InfoG->hrefImg=$temp["imgurl"];
			$InfoG->w=$temp["w"];
			$InfoG->h=$temp["h"];
			//print_r($infoG)
			$db->save($infoG);
		}

	}

}
class InfoGoogleImages{
	public $data;
	public $hrefImg;
	public $hrefSite;
	public $w;
	public $h;
	public $mime;
	public $datasrc;
}

class DBInfoGoogleImages extends DBBase{
	function save(&$info){
		
        try
        {
            if ($this->connect())
            {
                $sql="insert into googleImages (data, hrefImg, hrefSite, w, h, mime) values  (?,?,?,?,?,?)";
                if ($stmt = $this->mysqli->prepare($sql))
                {
                    $stmt->bind_param("ssssss", $info->data, $info->hrefImg, $info->hrefSite,$info->w,$info->h,$info->mime);
                    $res = $stmt->execute();
                    if($res){
                    	$info->id= $this->mysqli->insert_id;
                    	trace("Imagen  " . $info->id . " guardado en BD con resultado " . $res);
                    }
                    else trace("Error  " . $this->mysqli->error);
                    return $res;
                }else trace("error prepare".$this->mysqli->error);
            }else trace("error conn".$this->mysqli->error);
        }
        catch (Exception $e)
        {
            echo 'Excepción capturada: ', $e->getMessage(), "\n";
        }
        return false;

}
}
$lol=new GoogleImages();
$lol->buscarImagenes("gato");
?>