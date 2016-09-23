<?php
use \ElasticSearch\Client;
use Phalcon\Mvc\Controller;
use Ranking\RankingBase;

class ControllerBase extends Controller
{


	protected function initialize()
	{
		
	}

	protected function guid(){
		 $Ranking = new RankingBase();
		 return $Ranking->guid();
	 }


	 protected function getEs($type){
		 
		 return Client::connection("http://es.ranking.chinalaw.com:9200/$type");
		 
	 }


	protected function searchFirm($query){

		 $client=Client::connection("http://es.ranking.chinalaw.com:9200/rk/lawfirm");
		 
		 return $client->search($query);
		 
	 }

	 protected function searchLawyer($query){

		 $client=Client::connection("http://es.ranking.chinalaw.com:9200/rk/lawyer");
		 
		 return $client->search($query);
		 
	 }


	 protected function searchAlb($query){

		 $client=Client::connection("http://es.ranking.chinalaw.com:9200/rk/alb");
		 
		 return $client->search($query);
		 
	 }

	  protected function searchFile($query){

		 $client=Client::connection("http://es.ranking.chinalaw.com:9200/rk/file");
		 
		 return $client->search($query);
		 
	 }
	protected function getImagesUrl($uuid){
		//return $this->config->imagesUrl.$uuid;
		return $uuid;
	}

	
	protected function unicode_encode($name) {
	   	$name = iconv('UTF-8', 'UCS-2', $name);  
	    $len = strlen($name);  
	    $str = '';  
	    for ($i = 0; $i < $len - 1; $i = $i + 2)  
	    {  
	        $c = $name[$i];  
	        $c2 = $name[$i + 1];  
	        if (ord($c) > 0)  
	        {    // 两个字节的文字  
	            $str .= '\u'.base_convert(ord($c), 10, 16).base_convert(ord($c2), 10, 16);  
	        }  
	        else  
	        {  
	            $str .= $c2;  
	        }  
	    }  
	    return $str;  
	}
	

}
