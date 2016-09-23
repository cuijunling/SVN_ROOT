<?php
use \ElasticSearch\Client;
use Ranking\ESQuery;
use Ranking\Paginator as RPaginator;
use ElasticSearch\DSL;

class EsController extends ControllerBase
{


    public function indexAction()
    {

    }
   
    public function lawyerAction()
    {
    	$q=$this->request->getQuery("q");
    	// echo $q;
    	$es = $this->getEs("rk/lawyer");


    	  $Que = new ESQuery();
    	
    	$options['from']=1;
    	$query = $Que->buildQueryString($q,$options);
		$rs= $es->search($query);

    	$lawyer=$rs['hits']['hits'];
    	$this->view->disable();
    	header('Content-type:application/json;charset=utf-8');
    	echo json_encode($lawyer);
    	

    }

    

    public function lawfirmAction()
    {
    	$q=$this->request->getQuery("q");
    	// echo $q;
    	$es = $this->getEs("rk/lawfirm");

    
    	$rs= $es->search("albAwards.practiceArea:8");
    	$lawfirms = $rs['hits']['hits'];

    	foreach ($lawfirms as $lawfirm) {
    		// print_r(array_keys($lawfirm)) ;
    		echo $lawfirm['_source']['updatetime'];
    		exit;
    	}

    	// $lawyer=$rs['hits']['hits'];
    	$this->view->disable();
    	header('Content-type:application/json;charset=utf-8');
    	// echo json_decode($rs);
    	
    	// http://localhost/api/es/lawfirm?q=albAwards.practiceArea:7%20

    }
    public function searchAction(){

	   $Que = new ESQuery();
	    if($this->request->isPost()){
		    $query['query'] = $this->request->getPost('query');
		    $query = $Que->filter($query);
		    $q = $Que->getQueryString();
		    /*
		    $query = array('query'=>array('term'=>array('introduce'=>urlencode('中国'))));
		    $query = array(
			    'size'=>2,
			    'query' => array(
				    //'match' => array('introduce' => array("query"=>"$k","analyzer"=>"ik_smart"))
				    //'term' => array('introduce' =>"$k")
				    "query_string"=>array("query"=>"introduce:律师事务所")
				)
			);
		    #$query = "name:".urlencode("中国");
		     */
		    #print_r($query);
		    #echo "<hr>";


	    }else{
		    $q=$this->request->getQuery('q');
		    $page=$this->request->getQuery('page');
		    $options['from']=$page;
		    $query = $Que->buildQueryString($q,$options);
		    print_r($query);
	    }

	$es = $this->getEs("ranking/lawfirm");
	$rs = $es->search($query);
	$lawfirm = $rs['hits']['hits'];
             $paginator = new RPaginator(array(
	    'data'=> $rs,	
	    'limit'=> $sizePage,
	    'page' => $page,
	    'query'=>$query,
        ));

        $this->view->page = $paginator->getPaginate();
	$this->view->size = $sizePage;
	$this->view->q = $q;

    
    }

    public function dslAction(){
	    $es = $this->getEs("rk/lawyer");
	    $query = array(
		    "query"=>array("term"=>array("basicInfo_en.office"=>"beijing")),
		"aggs"=>array("office"=>array("terms"=>array("field"=>"basicInfo_en.office")))
	    );
	$rs = $es->search($query);
	    $this->view($rs);
	    exit;
    }
    private function view($d){
    	if(is_array($d)){
		foreach($d as $k=>$v){
			if(is_array($v)){
				echo "<ol>".$k;
				$this->view($v);
				echo "</ol>";
			}else{
				echo "<li>[$k]:$v</li>";
			}
		}
	}else{
		echo "<div>$d</div>";
	}
    }



}

