<?php
namespace Ranking;

class ESQuery{
	var $q="";

	private function createDSL($arr){
		$term = $arr['term'];

		return $term;

	}
	private function query_string($array){
		$qs = $array['query']['query_string'];
		$array['query']['query_string']=[];

		foreach($qs as $k=>$v){
			if(strlen($v)>0)
				$q[] = sprintf("%s:%s",$k,$v);

		}

		$qs = implode(" ",$q);
		$this->q = $qs;
		$array['query']['query_string']['query'] = $qs;
		return $array;
	}

	public function buildQueryString($q,$options=array()){

		if(intval($options['size'])>0)
			$array['size']=$options['size'];
		else
			$array['size']=10;
		if(intval($options['before'])>0)
			$array['before']=$options['before'];
		
		if(intval($options['from'])>0){
			$from = ($options['from']-1)*$array['size'];
			$array['from']=$from;
		}else{
			$array['from']=0;
		}
		$array['query']['query_string']['query']=$q;
		return $array;
	}

	public function filter($array){
		if(isset($array['query']['query_string'])){
			return $this->query_string($array);
		}else{
			return $array;
		}
	}
	public function getQueryString(){
		return $this->q;
	}

}
