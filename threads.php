<?php
	class test{

		function test1($saludo){

			echo $saludo;
		}

	}

	class FuerzaBruta extends Thread
	{
		public function run() {
			echo "OLI";
		}
	}


	$t=new FuerzaBruta();
	$t->start();
?>