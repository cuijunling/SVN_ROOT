<?php
use Dto\ResutltDto as resutltDto;

class IndexController extends ControllerBase
{

    public function indexAction()
    {
    	
   
    }


    public function homeAction()
    {
    	 $this->getCof();
    }

     public function likeAction()
    {
    	
        $resutltDto=new resutltDto();

        $language="zh";
        if ($this->request->isGet()) {

            if (isset($_GET["id"])) {
               $id=$this->request->getQuery("id");
            }else{
               $resutltDto->state=false;
               $resutltDto->msg="The request has no parameters id";
               echo  json_encode($resutltDto);
               exit;
            }

            if (isset($_GET["type"])) {
               $type=$this->request->getQuery("type");
            }else{
               $resutltDto->state=false;
               $resutltDto->msg="The request has no parameters type";
               echo  json_encode($resutltDto);
               exit;
            }

            $praise=Praise::findFirstByeventId($id);

            if (!$praise) {
                $praise=new Praise();
                $praise->guid=$this->guid();
                $praise->eventId=$id;
                $praise->count=1;
                $praise->type=$type;

                if (!$praise->save()) {

                        $resutltDto->state=false;
                        $resutltDto->msg="Failed to get the like number";
                        echo  json_encode($resutltDto);
                        exit;
                }

            }else{

                $praise->count=$praise->count+1;
                if (!$praise->save()) {

                        $resutltDto->state=false;
                        $resutltDto->msg="Failed to get the like number";
                        echo  json_encode($resutltDto);
                        exit;
                }

            }

            $p=Praise::findFirstByeventId($id);

            $data=array( 
                'id' =>$id , 
                'number'=>$p->count
                );

            $resutltDto->data= $data;
            $resutltDto->state=true;
            $resutltDto->msg="点赞成功";
        }else{

            $resutltDto->state=false;
            $resutltDto->msg="The request method error";
        }

        echo  json_encode($resutltDto);
        exit;
    }

}

