<?php

use Dto\ResutltDto as resutltDto;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;



class FileController extends ControllerBase
{
    
    public function initialize()
    {
          $this->view->disable();
          header('Content-type:application/json;charset=utf-8');
    }

    public function infoAction()
    {
        $resutltDto=new resutltDto();
        $language="zh";
        if ($this->request->isGet()) {

        
            if (isset($_GET["fileId"])) {
               $fileId=$this->request->getQuery("fileId");
            }else{
               $resutltDto->state=false;
               $resutltDto->msg="The request has no parameters fileId";
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

            if (isset($_GET["language"])) {
              $language=$this->request->getQuery("language");
            }


            $file=MidFile::findFirstByguid($fileId);

            if (!$file) {
               $resutltDto->state=false;
               $resutltDto->msg="Could not find the data";
               echo  json_encode($resutltDto);
               exit;
            }



            $praise=Praise::findFirstByeventId($fileId);

                if (!$praise) {
                   
                    $praise=new Praise();
                    $praise->guid=$this->guid();
                    $praise->eventId=$fileId;
                    $praise->count=0;
                    $praise->type="activity";

                    if (!$praise->save()) {

                        $resutltDto->state=false;
                        $resutltDto->msg="Failed to get the like number";
                        echo  json_encode($resutltDto);
                        exit;
                    }

                }

             $praise=Praise::findFirstByeventId($fileId);



            if ($language=="en") {

                $lawyer=array(
                    'id' => json_decode($file->lawyerBasicModel_en)->lawyerId,
                    'type'=>"lawyer" ,
                    'name'=>json_decode($file->lawyerBasicModel_en)->name,
                    'photo'=>json_decode($file->lawyerBasicModel_en)->photo
                    );
               

                $data = array(

                    'id' => $file->id,
                    'type' => $file->type,
                    'title' => $file->title_en,
                    'like' => $praise->count,
                    'lawyerBasicModel' =>$lawyer,
                    'content' => json_decode($file->detail_en),

                );
            }else{

                if (!empty(json_decode($file->lawyerBasicModel)->photo)) {
                    $photo=json_decode($file->lawyerBasicModel)->photo;
                }else{
                    $photo="";
                }

                 $lawyer=array(
                    'id' => json_decode($file->lawyerBasicModel_en)->lawyerId,
                    'type'=>"lawyer" ,
                    'name'=>json_decode($file->lawyerBasicModel_en)->name,
                    'photo'=>$photo
                    );

                $data = array(

                    'id' => $file->id, 
                    'type' => $file->type,
                    'title' => $file->title,
                    'like' => $praise->count,
                    'lawyerBasicModel' => $lawyer,
                    'content' => json_decode($file->detail),

                );

            }

            
            $resutltDto->data=$data;
            $resutltDto->state=true;
            $resutltDto->msg="Successful access to information！";
        }else{

            $resutltDto->state=false;
            $resutltDto->msg="The request method error";
        }

        echo  json_encode($resutltDto);
        exit;


    }


    public function listAction()
    {
        $resutltDto=new resutltDto();
        $language="zh";
        $start=0;
        $size=10;
        if ($this->request->isGet()) {



        }else{

            $resutltDto->state=false;
            $resutltDto->msg="The request method error";
        }

        echo  json_encode($resutltDto);
        exit;
    } 

}