<?php

function trace($var)
{
    echo ($var);
    echo "\n";
}
function escaparObjeto($obj)
{
    trace("escapando val:");
    foreach ($obj as $key => &$value)
    {
        trace("escapando :" . $key . ' ' . $value . ' ' . $obj->$key . ' ' . gettype($value));
        if (gettype($value) == 'boolean')
        {
            if (is_null($value))$obj->key = '';
            elseif ($value == true)$obj->key = 'TRUE';
            else $obj->key = 'FALSE';
        }
        if (is_numeric($value))
        {
            trace("escapando :" . $key . ' ' . $value . ' ' . $obj->$key . ' ' . gettype($value));
            continue;
        }
        else
        {
            if (is_null($value))
            {
                $obj->$key = '-';
            }
            else $obj->$key = addslashes($value);
        }
    }
    return $obj;
}
function tostring($obj)
{
    $sql = '';
    foreach ($obj as $key => &$value)
    {
        $sql .= '"' . addslashes($value) . '",';
    }
    $sql = substr($sql, 0, strlen($sql) - 1);

    $sql = '(' . $sql . ')';
    return $sql;
}

class DBBase
{
    /*
	   public $user="githubapi";
	   public $password='1q2w3e4r';
	   public $server='localhost';
	   public $DB='githubapi';
	*/

    protected $mysqli;
    public function __construct()
    {
        $this->mysqli=null;
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
        if($this->mysqli==null){
            $this->mysqli = new mysqli(null, $user, $password, $DB);

            /* check connection */
            if (mysqli_connect_errno())
            {
                printf("Connect failed: %s\n", mysqli_connect_error());
                return false;
            }
            else return true;
        }
        else return true;
    }
    /*
	   i	la variable correspondiente es de tipo entero
	   d	la variable correspondiente es de tipo double
	   s	la variable correspondiente es de tipo string
	   b	la variable correspondiente es un blob y se envía en paquetes
	*/
}
class DBOwner extends DBBase
{
    function save($owner)
    {
        try
        {
            if ($this->connect())
            {
                $sql = 'REPLACE INTO owner(id,login,url) VALUES (?,?,?)';
                if ($stmt = $this->mysqli->prepare($sql))
                {
                    $stmt->bind_param("iss", $owner->id, $owner->login, $owner->url);
                    $res = $stmt->execute();
                    trace("Owner " . $owner->id . " guardado en BD con resultado " . $res);
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

class DBRepositorio extends DBBase
{
    /**
     * Graba un registro en la BD
     */
    function save($owner)
    {
        try
        {
            if ($this->connect())
            {
                $sql = 'replace INTO repositorio(id, name, full_name, owner_id, private, html_url, description, fork, url, forks_url, created_at, updated_at, pushed_at, git_url, svn_url, homepage, size, stargazers_count, watchers_count, language, has_issues, has_downloads, has_wiki, forks_count, mirror_url, open_issues_count, forks, open_issues, watchers, default_branch, master_branch, score,json) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
                if ($stmt = $this->mysqli->prepare($sql))
                {
                    if ($stmt->bind_param("issiississssssssiiisiiiisiiiissds", $owner->id , $owner->name , $owner->full_name , $owner->owner_id , $owner->private , $owner->html_url , $owner->description , $owner->fork , $owner->url , $owner->forks_url , $owner->created_at , $owner->updated_at , $owner->pushed_at , $owner->git_url , $owner->svn_url , $owner->homepage , $owner->size , $owner->stargazers_count , $owner->watchers_count , $owner->language , $owner->has_issues , $owner->has_downloads , $owner->has_wiki , $owner->forks_count , $owner->mirror_url , $owner->open_issues_count , $owner->forks , $owner->open_issues , $owner->watchers , $owner->default_branch , $owner->master_branch , $owner->score, $owner->json))
                    {
                        $res = $stmt->execute();
                        if($stmt->error==null)
                        trace(" Repositorio " . $owner->id . " guardado en BD con resultado ");
                        else trace(" Error   repositorio" . $owner->id . ' ' . $stmt->error);
                        return $res;
                    }
                    else echo 'Error Bind Param';
                }
                else echo 'Error prepare';
            }
            else echo 'Error connect';
        }
        catch (Exception $e)
        {
            echo 'Excepción capturada: ', $e->getMessage(), "\n";
        }
    }

    /**
     * Guarda Todos los registros en la lista
     */
    function saveAll($lstowner)
    {
        if (count($lstowner) > 0)
        {
            try
            {
                if ($this->connect())
                {
                    $sql = 'INSERT INTO repositorio(id, name, full_name, owner_id, private, html_url, description, fork, url, forks_url, created_at, updated_at, pushed_at, git_url, svn_url, homepage, size, stargazers_count, watchers_count, language, has_issues, has_downloads, has_wiki, forks_count, mirror_url, open_issues_count, forks, open_issues, watchers, default_branch, master_branch, score,json) VALUES ';

                    foreach ($lstowner as $item)
                    {
                        $sql .= tostring($item) . ',';
                    }
                    $sql = substr($sql, 0, strlen($sql) - 1);

                    $res = $this->mysqli->query($sql);
                    if ($res > 0) trace(" Guardando " . count($lstowner) . " en BD con resultado " . $res);
                    else
                    {
                        trace("Error al guardar repositorios " . $this->mysqli->error);
                        // trace($sql);
                        die();
                    }
                    return $res;
                }
                else echo 'Error connect';
            }
            catch (Exception $e)
            {
                echo 'Excepción capturada: ', $e->getMessage(), "\n";
            }
        }
    }
}
class Owner
{
    public $id;
    public $login;
    public $url;

    function __construct($json)
    {
        $this->id = $json['id'];
        $this->login = $json['login'];
        $this->url = $json['url'];
    }
}
class Repositorio
{
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

class Busqueda
{
    public $id = null;
    public $busqueda;
    public $fecha;
    public $total_count;
}
class DBBusqueda extends DBBase
{
    function save(&$busqueda)
    {
        $sql = 'insert INTO busqueda(busqueda,total_count) VALUES (?,?)';
        try
        {
            if ($this->connect())
            {
                if ($stmt = $this->mysqli->prepare($sql))
                {
                    $stmt->bind_param("si", $busqueda->busqueda, $busqueda->total_count);
                    $res = $stmt->execute();
                    if ($res > 0)$busqueda->id = $this->mysqli->insert_id;
                    trace("Busqueda guardado en BD con resultado " . $res);
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
class resultadobusquedarepositorio
{
    public $id_busqueda;
    public $id_repositorio;
}
class DBresultadobusquedarepositorio extends DBBase
{
    function save($busqueda)
    {
        try
        {
            if ($this->connect())
            {
                $sql = 'insert INTO resultadobusquedarepositorio(id_busqueda,id_repositorio) VALUES (?.?)';

                if ($stmt = $this->mysqli->prepare($sql))
                {
                    $stmt->bind_param("ii", $busqueda->id_busqueda, $busqueda->id_repositorio);
                    $res = $stmt->execute();
                    trace("resultadobusquedarepositorio guardado en BD con resultado " . $res);
                    return $res;
                }
                else trace("error prepare");
            }
            else trace("error conn");
        }
        catch (Exception $e)
        {
            echo 'Excepción capturada: ', $e->getMessage(), "\n";
        }
        return false;
    }

    function saveAll($lst)
    {
        if (count($lst) > 0)
        {
            try
            {
                if ($this->connect())
                {
                    $sql = 'insert INTO resultadobusquedarepositorio(id_busqueda,id_repositorio) VALUES ';

                    foreach ($lst as $item)
                    {
                        $sql .= tostring($item) . ',';
                    }
                    $sql = substr($sql, 0, strlen($sql) - 1);

                    $res = $this->mysqli->query($sql);
                    if ($res > 0) trace(" Guardando " . count($lst) . " en BD con resultado " . $res);
                    else
                    {
                        trace("Error al guardar resultados busqueda " . $this->mysqli->error);
                        trace($sql);
                    }
                    return $res;
                }
                else echo 'Error connect';
            }
            catch (Exception $e)
            {
                echo 'Excepción capturada: ', $e->getMessage(), "\n";
            }
        }
        return false;
    }
}

?>