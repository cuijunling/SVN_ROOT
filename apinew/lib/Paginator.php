<?php
namespace Ranking;

class Paginator{
	var $config;
	var $options;

	public function __construct($config,$options=array()){
		#print_r($config);
		$this->config = $config;
		$this->options = $options;
	}
	public function setCurrentPage($page){
	
	}
	public function getPaginate(){
		$p = new Paginate();
		if($this->config['data']['hits']['total']!=0){
			$p->setItems($this->config['data']['hits']['hits']);
		}else{
			$p->setItems($this->config['data']['hits']['hits']);
		
		}
		$p->setTotal($this->config['data']['hits']['total']);
		$p->setLimit($this->config['limit']);
		#$p->setCurrent($this->config['current']);
		$p->setCurrent($this->config['page']);
		$p->setNext($this->config['page']);
		$p->setBefore($this->config['page']);
		return $p;
	}

}


class Paginate{
	var $items=array();
	var $current = 0;
	var $before = 0;
	var $next = 0;
	var $last = 0;
	var $limit = 10;
	var $total_pages = 0;
	var $total_items = 0;
	public function setItems($items){
		$this->items = $items;
	}
	public function setLimit($n) {
		if(intval($n)>0)
			$this->limit = $n;
	
	}
	public function setCurrent($n){ 
		if(intval($n)>0){
			$this->current=$n;
			$this->next = $n+1;
			$this->before = $n-1;
		}
	}
	public function setNext($n){
		$this->next = $n+1;
	}
	public function setLast($n){ 

		$this->last=$n;
	
	}
	public function setTotal($n){
		$this->total_pages = $n;
		$this->last = ceil($n/$this->limit);
		
	}
	public function setBefore($n){ 
		if($n==0)
			$this->before=0;
		else
			$this->before = $n-1;
	}

}
