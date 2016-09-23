<?php

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Dto\ResutltDto as resutltDto;


class SearchController extends ControllerBase
{
	
	public function initialize()
    {
          $this->view->disable();
          header('Content-type:application/json;charset=utf-8');
    }

    public function indexAction()
    {

    		$resutltDto=new resutltDto();
	    	
	    	$language="zh";

	    	$start=0;
	    	$size=10;

	    	if ($this->request->isGet()){

	    		if (isset($_GET["q"])) {
	               $q=$this->request->getQuery("q");

	            }else{
	               $resutltDto->state=false;
	               $resutltDto->msg="The request has no parameters q ";
	               echo  json_encode($resutltDto);
	               exit;
	            }

	            if (isset($_GET["language"])) {
	        		$language=$this->request->getQuery("language");
	        	}

	        	if ($language=="en") {
	        		$query_all=$q."&size=".$size."&from=".$start;
	        	}else{
	        		$query_all=$this->unicode_encode($q)."&size=".$size."&from=".$start;
	        	}

	            if ($language=="en") {
	            	$query_lawfirm="basicInfo_en.name:".$q."&size=".$size."&from=".$start;
	            }else{
	            	$query_lawfirm="basicInfo.name:".$this->unicode_encode($q)."&size=".$size."&from=".$start;
	            }

	            if ($language=="en") {
	            	$query_lawyer="basicInfo_en.name:".$q."&size=".$size."&from=".$start;
	            }else{
	            	$query_lawyer="basicInfo.name:".$this->unicode_encode($q)."&size=".$size."&from=".$start;
	            }

	            if ($language=="en") {
	            	$query_alb="name_en:".$q."&size=".$size."&from=".$start;
	            }else{
	            	$query_alb="name:".$this->unicode_encode($q)."&size=".$size."&from=".$start;
	            }

	            if ($language=="en") {
	            	$query_file="title_en:".$q."&size=".$size."&from=".$start;
	            }else{
	            	$query_file="title:".$this->unicode_encode($q)."&size=".$size."&from=".$start;
	            }
	            	           
	          	          	           
		    	$es_lawfirm=$this->searchFirm($query_lawfirm);
		    	$es_lawyer=$this->searchLawyer($query_lawyer);
		    	$es_alb=$this->searchAlb($query_alb);
		    	$es_file=$this->searchFile($query_file);



		    	$lawfirms = $es_lawfirm['hits']['hits'];
		    	$lawyers = $es_lawyer['hits']['hits'];
		    	$albs = $es_alb['hits']['hits'];
		    	$files = $es_file['hits']['hits'];


		    	if (count($lawfirms)==0) {
		    		$es_lawfirm=$this->searchFirm($query_all);
		    		$lawfirms = $es_lawfirm['hits']['hits'];
		    	}
		    	if (count($lawyers)==0) {
		    		$es_lawyer=$this->searchLawyer($query_all);
		    		$lawyers = $es_lawyer['hits']['hits'];
		    	}
		    	
		    	if (count($albs)==0) {
		    		
		    		$es_alb=$this->searchAlb($query_all);
		    		$albs = $es_alb['hits']['hits'];
		    		
		    	}
		    	if (count($files)==0) {
		    		$es_file=$this->searchFile($query_all);
		    		$files = $es_file['hits']['hits'];
		    	}

		    	$arrayFirm = array();
		    	$arraylawyer = array();
		    	$arrayalb = array();
		    	$arrayfile = array();

		    	foreach ($lawfirms as $lawfirm) {
		    		$firm=array(
		    			'id' => $lawfirm['_source']['guid'],
		    			'name' => $lawfirm['_source']['basicInfo']['name'],
		    			'nameEn' => $lawfirm['_source']['basicInfo_en']['name'],
		    			);
		    		array_push($arrayFirm, $firm);
		    	}

		    	foreach ($lawyers as $lawyer) {
		    		$law=array(
		    			'id' => $lawyer['_source']['guid'],
		    			'name' => $lawyer['_source']['basicInfo']['name'],
		    			'nameEn' => $lawyer['_source']['basicInfo_en']['name'],
		    			);
		    		array_push($arraylawyer, $law);
		    	}

		    	foreach ($albs as $alb) {
		    		$al=array(
		    			'id' => $alb['_source']['guid'],
		    			'name' => $alb['_source']['name'],
		    			'nameEn' => $alb['_source']['name_en'],
		    			);
		    		array_push($arrayalb, $al);
		    	}

		    	foreach ($files as $file) {
		    		$fil=array(
		    			'id' => $file['_source']['guid'],
		    			'name' => $file['_source']['title'],
		    			'nameEn' => $file['_source']['title_en'],
		    			);
		    		array_push($arrayfile, $fil);
		    	}

		    	$data = array(

		    		'lawfirm' => $arrayFirm,
		    		'lawyer' => $arraylawyer,
		    		'alb' => $arrayalb,
		    		'case' => $arrayfile,

		    		 );



		    	$resutltDto->state=true;
			    $resutltDto->msg="Successful access to information！";
		        $resutltDto->data=$data;

		    	
	        	
	        }else{

	        	$resutltDto->state=false;
			    $resutltDto->msg="The request method error";
	        }   

	        echo  json_encode($resutltDto);
	        exit;
		   
    }

    public function listAction()
    {
    	//$files=array("updatetime","title");
    	$files=array("title");



    	$query = array(
		"query"=>array(
			"term"=>array(
				"title"=>"南京"
			)
		),
	    "highlight"=>array(
		    "fields"=>array("title"=>new \stdClass()),
		    "post_tags"=>array("</tag1>","</tag2>"),
		    "pre_tags"=>array("<tag1>","<tag2>")
	   	 )
	    );


    	$es_lawfirm=$this->searchFile($query);
    	

    	echo json_encode($es_lawfirm);
    }



}