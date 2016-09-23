<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Dto\ResutltDto as resutltDto;




class PracticeareaController extends ControllerBase
{

	public function initialize()
    {
          $this->view->disable();
          header('Content-type:application/json;charset=utf-8');
    }


    public function listAction()
    {
    	
	    	$resutltDto=new resutltDto();
	    	
	    	$language="zh";
	        if ($this->request->isGet()){

		        if (isset($_GET["language"])) {
	        		$language=$this->request->getQuery("language");
	        	}

		        $MidPracticeareasList=MidPracticeareas::find(
		        	array(
		        		 	"conditions" => "id != 999",
		        	        "order" => "index,areaId,id"

		        	    )
		        	);
    			$MidPracticeareasList_en=MidPracticeareas::find(
  		        	array(
  		        			"conditions" => "id != 999",
  		        	        "order" => "index_en,areaId,id"
  		        	    )
  		        	);

		        $resutltList=array();

	       		if ($language=="en") {

	       			foreach ($MidPracticeareasList_en as $area) {
	       				$array = array(
	       					'id' => $area->id, 
	       					'categoryId' => $area->areaId , 
	       					'name' => $area->name_en, 
	       					'icon' => $area->icon_en
	       					);

	       				array_push($resutltList, $array);
	       			}	

	       		}else{

	       			foreach ($MidPracticeareasList as $area) {
	       				$array = array(
	       					'id' => $area->id, 
	       					'categoryId' => $area->areaId, 
	       					'name' => $area->name, 
	       					'icon' => $area->icon
	       					);

	       				array_push($resutltList, $array);
	       			}	

	       		}
	       		

		        $resutltDto->state=true;
			    $resutltDto->msg="Successful access to information！";
		        $resutltDto->data=$resutltList;


			    }else{
			        $resutltDto->state=false;
			        $resutltDto->msg="The request method error";
			    }

	       
	        	echo  json_encode($resutltDto);
	        	exit;

    }


    public function lawfirmlistAction()
    {
    		$resutltDto=new resutltDto();
	    	
	    	$language="zh";
	    	$start=0;
	    	$size=10;
	        if ($this->request->isGet()){

	        	if (isset($_GET["categoryId"])) {
	               $categoryId=$this->request->getQuery("categoryId");

	            }else{
	               $resutltDto->state=false;
	               $resutltDto->msg="The request has no parameters categoryId ";
	               echo  json_encode($resutltDto);
	               exit;
	            }

	            if (isset($_GET["start"])) {
	        		$start=$this->request->getQuery("start","int");
	        	}

	        	if (isset($_GET["size"])) {
	        		$size=$this->request->getQuery("size","int");
	        	}

	        	if (isset($_GET["language"])) {
	        		$language=$this->request->getQuery("language");
	        	}
		    	$es = $this->getEs("rk/lawfirm");

		    	$query="albAwards.practiceArea:".$categoryId."&size=".$size."&from=".$start;
		    	

		    	$rs= $es->search($query);

		    	$lawfirms = $rs['hits']['hits'];

		    	$firms=array();
		    	$areas=array();
		    	$offices=array();

				

		    	foreach ($lawfirms as $lawfirm) 
		    	{

					$office=$lawfirm['_source']['basicInfo']['office'];
			    		foreach ($office as $of) {
			    			array_push($offices, $of);
			    	}
			    	if ($language=="en") {
			    		$basicInfo=$lawfirm['_source']['basicInfo_en'];
			    		$albs=array();

		    			$lawfirm_albAwards=array();
		    			$lawyer_albAwards=array();

		    			foreach ($lawfirm['_source']['albAwards_en'] as $value) {
							$select_val=$value['practiceArea'];
								if ($select_val==$categoryId) {
										array_push($albs, $value);
								};
						};
		    			foreach ($albs as $alb) {
		    				if ($alb['type']=='lawyer') {

		    					 $lawyer = MidLawyer::findFirstByguid($alb['id']) ;
		    					 $basicInfo=$lawyer->basicInfo;
		    					 $alb_lawyer = array(
		    					 	'id' => $alb['id'], 
		    					 	'name'=>json_decode($basicInfo)->name,
		    					 	);
		    					 $alb_new = array(
		    					 	'updateTime' =>$alb['updateTime'] ,
		    					 	'name' =>$alb['name'] ,
		    					 	'type' =>$alb['type'] ,
		    					 	'isWin' =>$alb['isWin'] ,
		    					 	'albid' =>$alb['albid'] ,
		    					 	'class' =>$alb['class'] ,
		    					 	'flag' =>$alb['flag'] ,
		    					 	'albdetailitemid' =>$alb['albdetailitemid'] ,
		    					 	'year' =>$alb['year'] ,
		    					 	'practiceArea' =>$alb['practiceArea'] ,
		    					 	'id' =>$alb['id'] ,
		    					 	'lawyer'=>$alb_lawyer
		    					 	 );
		    					array_push($lawyer_albAwards, $alb_new);
		    				}elseif ($alb['type']=='project') {
				    				foreach ($lawfirm_albAwards as $albAward) {
				    					$combination=$albAward['year'].$albAward['name'].$albAward['isWin'];
				    					$alb_combination=$alb['year'].$alb['name'].$alb['isWin'];
				    					if ($combination==$alb_combination) {
				    						
				    						break 2;
				    					}
				    					
				    				}
				    			
				    				array_push($lawfirm_albAwards,$alb);
				    			
				    		} else{
			    					array_push($lawfirm_albAwards,$alb);
				    		}

		    			}
		    		}else{
		    			
		    				$albs=array();
			    			$lawfirm_albAwards=array();
		    				$lawyer_albAwards=array();

			    			foreach ($lawfirm['_source']['albAwards'] as $value) {
								$select_val=$value['practiceArea'];
									if ($select_val==$categoryId) {
											array_push($albs, $value);
									};
							};
				    		$basicInfo=$lawfirm['_source']['basicInfo'];


							foreach ($albs as $alb){
								if ($alb['type']=='lawyer'){

									$lawyer = MidLawyer::findFirstByguid($alb['id']) ;
									if ($lawyer) {
										$lawyerInfo=$lawyer->basicInfo;
									}
									
											 
									
									$alb_new = array(
											 	'updateTime' =>$alb['updateTime'] ,
											 	'name' =>$alb['name'] ,
											 	'type' =>$alb['type'] ,
											 	'isWin' =>$alb['isWin'] ,
											 	'albid' =>$alb['albid'] ,
											 	'class' =>$alb['class'] ,
											 	'flag' =>$alb['flag'] ,
											 	'albdetailitemid' =>$alb['albdetailitemid'] ,
											 	'year' =>$alb['year'] ,
											 	'practiceArea' =>$alb['practiceArea'] ,
											 	'id' =>$alb['id'] ,
											 	'lawyerName'=>json_decode($lawyerInfo)->name
											);

									
									foreach ($lawyer_albAwards as $lawyer_albAward) {
										if ($lawyer_albAward['lawyerName']==json_decode($lawyerInfo)->name) {
											break 2;
										}
									}

									array_push($lawyer_albAwards,$alb_new);
								
								}elseif ($alb['type']=='project') {
										foreach ($lawfirm_albAwards as $albAward) {
											$combination=$albAward['year'].$albAward['name'].$albAward['isWin'];
											$alb_combination=$alb['year'].$alb['name'].$alb['isWin'];
											if ($combination==$alb_combination) {
												
												break 2;
											}
										}
									
										array_push($lawfirm_albAwards,$alb);
								}else{

										array_push($lawfirm_albAwards,$alb);
								}

							}
		    		}	
		    		if (count($lawfirm_albAwards)==0) {
		    			$lawfirm_albAwards=null;
		    		}
		    		if (count($lawyer_albAwards)==0) {
		    			$lawyer_albAwards=null;
		    		}
		    		
	    			$firm = array(
	    			'id' => $lawfirm['_source']['guid'], 
	    			'firmName' =>$basicInfo['name'],
	    			'lawfirmAlbAwards'=>$lawfirm_albAwards,
	    			'lawyerAlbAwards'=>$lawyer_albAwards
	    			);
	    	   		array_push($firms, $firm);	

		    	}
		    	$data=array(
		    		'areas' => $areas, 
		    		'lawfirms' => $firms, 
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
	

}