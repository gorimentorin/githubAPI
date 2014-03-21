<?php
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

?>