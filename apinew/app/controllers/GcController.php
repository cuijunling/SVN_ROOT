<?php

use Dto\ResutltDto as resutltDto;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;



class GcController extends ControllerBase
{
    
    public function initialize()
    {
          $this->view->disable();
          header('Content-type:application/json;charset=utf-8');
    }

    public function gcpersoninfoAction()
    {
        $resutltDto=new resutltDto();

        $language="zh";
        if ($this->request->isGet()) {


            if (isset($_GET["personId"])) {
               $guid=$this->request->getQuery("personId");
            }else{
               $resutltDto->state=false;
               $resutltDto->msg="The request has no parameters personId";
               echo  json_encode($resutltDto);
               exit;
            }

            if (isset($_GET["language"])) {
              $language=$this->request->getQuery("language");
            }

           
            $gcperson = MidGcperson::findFirstByguid($guid);

            if (!$gcperson) {
               $resutltDto->state=false;
               $resutltDto->msg="Could not find the data";
               echo  json_encode($resutltDto);
               exit;
            }


            if ($language=="en") {

                $info= array(
                    'id' => $gcperson->id, 
                    'name' => $gcperson->name_en, 
                    'personId' =>$gcperson->guid, 
                    'company' =>$gcperson->company_en, 
                    'title' => $gcperson->title_en,  
                    'type' => $gcperson->id, 
                    'photo' => $gcperson->photo, 
                    'introduce' => $gcperson->introduce_en

                );

                
            }else{

                $info= array(
                    'id' => $gcperson->id, 
                    'name' => $gcperson->name, 
                    'personId' =>$gcperson->guid, 
                    'company' =>$gcperson->company, 
                    'title' => $gcperson->title,  
                    'type' => $gcperson->id, 
                    'photo' => $gcperson->photo, 
                    'introduce' => $gcperson->introduce

                );

            }

           
                       
            $resutltDto->data=$info;
            $resutltDto->state=true;
            $resutltDto->msg="Successful access to information！";
        }else{

            $resutltDto->state=false;
            $resutltDto->msg="The request method error";
        }

        echo  json_encode($resutltDto);
        exit;


    }


    public function homeAction()
    {
        $resutltDto=new resutltDto();

        $language="zh";
        if ($this->request->isGet()) {
            if (isset($_GET["language"])) {
              $language=$this->request->getQuery("language");
            }

            $msgitems=array();
            $gcitems=array();
            $viewitems=array();


            $gcmessages = MidGcmessage::find();

            if ($language=="en") {
               foreach ($gcmessages as $gcmessage) {

                    $gc=$gcmessage->gc_en;
                    $msg=array(
                        'name'  => json_decode($gc)->name,
                        'photo' => $gcmessage->picture,
                        'title' => json_decode($gc)->title, 
                        'content' => $gcmessage->content_en,  
                        );
                    array_push($msgitems, $msg);
                }
               
            }else{
                foreach ($gcmessages as $gcmessage) {
                    $gc=$gcmessage->gc;

                    
                    $msg=array(
                        'name'  => json_decode($gc)->name,
                        'photo' => $gcmessage->picture, 
                        'title' => json_decode($gc)->title, 
                        'content' => $gcmessage->content,  
                        );
                    array_push($msgitems, $msg);
                }


            }


            $resutltMsg=array(
                'type' => "word", 
                'name' => "精英寄语",
                'items' => $msgitems
                );
            

            $gcpersons = MidGcperson::find();

            if ($language=="en") {

                foreach ($gcpersons as $gcperson) {
                         $person= array(
                            'id' => $gcperson->id, 
                            'name' => $gcperson->name_en, 
                            'personId' =>$gcperson->guid, 
                            'company' =>$gcperson->company_en, 
                            'title' => $gcperson->title_en,  
                            'type' => $gcperson->id, 
                            'photo' => $gcperson->horizonPhoto, 
                            'introduce' => $gcperson->introduce_en

                            );

                    array_push($gcitems, $person);     

                 }

            }else{

                foreach ($gcpersons as $gcperson) {
                        $person= array(
                            'id' => $gcperson->id, 
                            'name' => $gcperson->name, 
                            'personId' =>$gcperson->guid, 
                            'company' =>$gcperson->company, 
                            'title' => $gcperson->title,  
                            'type' => $gcperson->id, 
                            'photo' => $gcperson->horizonPhoto, 
                            'introduce' => $gcperson->introduce

                        );
                         array_push($gcitems, $person);  
                }

            }


            $resutltGc=array(
                'type' => "elite", 
                'name' => "企业法务精英",
                'items' => $gcitems
            );


            $gcinterviewsummarys = MidGcinterviewsummary::find( array('order' =>'date DESC'  ));

            if ($language=="en") {

                foreach ($gcinterviewsummarys as $view) {

                    if ($view->type=="person") {

                        
                        $view= array(
                            'id' => $view->id, 
                            'interviewId'=>$view->guid,
                            'name' => $view->name_en, 
                            'photo' =>$view->guid, 
                            'firm' =>json_decode($view->person_en)->company, 
                            'title' => json_decode($view->person_en)->title,  
                            'picture' => $view->summaryPicture, 
                            'type' => $view->type, 
                            'interviewIndex' => $view->interviewIndex

                        );
                        
                    }else{

                        $view= array(
                            'id' => $view->id, 
                            'interviewId'=>$view->guid,
                            'name' => $view->name_en, 
                            'photo' =>$view->guid, 
                            // 'firm' =>$person->company, 
                            // 'title' => $person->title,  
                            'picture' => $view->summaryPicture, 
                            'type' => $view->type, 
                            'interviewIndex' => $view->interviewIndex

                        );


                    }

                   
                    array_push($viewitems, $view);                    
                }
               
            }else{
                foreach ($gcinterviewsummarys as $view) {

                    if ($view->type=="person") {


                        $view= array(
                            'id' => $view->id, 
                            'interviewId'=>$view->guid,
                            'name' => $view->name, 
                            'photo' =>$view->guid, 
                            'firm' =>json_decode($view->person)->company, 
                            'title' => json_decode($view->person)->title,  
                            'picture' => $view->summaryPicture, 
                            'type' => $view->type, 
                            'interviewIndex' => $view->interviewIndex

                        );
                        
                    }else{

                        $view= array(
                            'id' => $view->id, 
                            'interviewId'=>$view->guid,
                            'name' => $view->name, 
                            'photo' =>$view->guid, 
                            // 'firm' =>$person->company, 
                            // 'title' => $person->title,  
                            'picture' => $view->summaryPicture, 
                            'type' => $view->type, 
                            'interviewIndex' => $view->interviewIndex

                        );


                    }

                   
                    array_push($viewitems, $view);                    
                }

            }


            $resutltView=array(
                'type' => "interview", 
                'name' => "企业法务专访",
                'items' => $viewitems
            );

            $data=array();

            array_push($data, $resutltMsg);
            array_push($data, $resutltGc);
            array_push($data, $resutltView);


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
        if ($this->request->isGet()) {
            if (isset($_GET["language"])) {
              $language=$this->request->getQuery("language");
            }

            if (isset($_GET["type"])) {
               $type=$this->request->getQuery("type");
            }else{
               $resutltDto->state=false;
               $resutltDto->msg="The request has no parameters type";
               echo  json_encode($resutltDto);
               exit;
            }

            $interviewItem=array();
            $eliteItem=array();

            if($type=="interview") {

                $gcinterviewsummarys = MidGcinterviewsummary::find( array('order' =>'date DESC'  ));  

                if ($language=="en") {
                    foreach ($gcinterviewsummarys as $view) {

                        if ($view->type=="person") {


                            $view= array(
                                'id' => $view->id, 
                                'interviewId'=>$view->guid,
                                'name' => $view->name_en, 
                                'photo' =>$view->guid, 
                                'firm' =>json_decode($view->person_en)->company, 
                                'title' => json_decode($view->person_en)->title,  
                                'picture' => $view->summaryPicture, 
                                'type' => $view->type, 
                                'interviewIndex' => $view->interviewIndex

                            );
                            
                        }else{

                            $view= array(
                                'id' => $view->id, 
                                'interviewId'=>$view->guid,
                                'name' => $view->name_en, 
                                'photo' =>$view->guid, 
                                // 'firm' =>$person->company, 
                                // 'title' => $person->title,  
                                'picture' => $view->summaryPicture, 
                                'type' => $view->type, 
                                'interviewIndex' => $view->interviewIndex

                            );

                        }

                    array_push($eliteItem, $view);   

                    }
                }else{
                    foreach ($gcinterviewsummarys as $view) {

                        if ($view->type=="person") {


                            $view= array(
                                'id' => $view->id, 
                                'interviewId'=>$view->guid,
                                'name' => $view->name, 
                                'photo' =>$view->guid, 
                                'firm' =>json_decode($view->person)->company, 
                                'title' => json_decode($view->person)->title,  
                                'picture' => $view->summaryPicture, 
                                'type' => $view->type, 
                                'interviewIndex' => $view->interviewIndex

                            );
                            
                        }else{

                            $view= array(
                                'id' => $view->id, 
                                'interviewId'=>$view->guid,
                                'name' => $view->name, 
                                'photo' =>$view->guid, 
                                // 'firm' =>$person->company, 
                                // 'title' => $person->title,  
                                'picture' => $view->summaryPicture, 
                                'type' => $view->type, 
                                'interviewIndex' => $view->interviewIndex

                            );
                        }

                    array_push($eliteItem, $view);  

                    }

                }

                 $data=array(
                    'type' => 'interview', 
                    'name' => '法务访谈',
                    'items' => $eliteItem
                    );

            }else if ($type=="elite") { 
                $gcpersons = MidGcperson::find();

                foreach ($gcpersons as $gcperson) {

                    if ($language=="en") {

                        $person= array(
                            'id' => $gcperson->id, 
                            'name' => $gcperson->name_en, 
                            'personId' =>$gcperson->guid, 
                            'company' =>$gcperson->company_en, 
                            'title' => $gcperson->title_en,  
                            'type' => $gcperson->id, 
                            'photo' => $gcperson->horizonPhoto, 
                            'introduce' => $gcperson->introduce_en

                            );
                    }else{

                         $person= array(
                            'id' => $gcperson->id, 
                            'name' => $gcperson->name, 
                            'personId' =>$gcperson->guid, 
                            'company' =>$gcperson->company, 
                            'title' => $gcperson->title,  
                            'type' => $gcperson->id, 
                            'photo' => $gcperson->horizonPhoto, 
                            'introduce' => $gcperson->introduce

                            );


                    }
                        
                    array_push($eliteItem, $person);     

                 }

                 $data=array(
                    'type' => 'elite', 
                    'name' => '企业法务精英',
                    'items' => $eliteItem
                    );

            }else{
                $resutltDto->state=false;
                $resutltDto->msg="Parameter is not valid";
                echo  json_encode($resutltDto);
                exit;
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


    public function interviewAction()
    {
        $resutltDto=new resutltDto();

        $language="zh";
        if ($this->request->isGet()) {
            if (isset($_GET["language"])) {
              $language=$this->request->getQuery("language");
            }

            if (isset($_GET["interviewId"])) {
               $guid=$this->request->getQuery("interviewId");
            }else{
               $resutltDto->state=false;
               $resutltDto->msg="The request has no parameters type";
               echo  json_encode($resutltDto);
               exit;
            }

            $gcinterviewsummary = MidGcinterviewsummary::findFirstByguid($guid);  

            if (empty($gcinterviewsummary)) {
                $resutltDto->state=false;
                $resutltDto->msg="Could not find the data";
                echo  json_encode($resutltDto);
                exit;
            }


            // if ($gcinterviewsummary->type=="group") {
            //     foreach (json_decode($gcinterviewsummary->content) as $content) {
                
            //         echo json_encode($content->answers);
            //         exit;
            //     }
            // }


            if ($language=="en") {
               $data= array(
                    'person' => json_decode($gcinterviewsummary->person_en),
                    'interviewId' => $gcinterviewsummary->guid,
                    'name' => $gcinterviewsummary->name_en,
                    'interviewIndex' => $gcinterviewsummary->interviewIndex,
                    'summaryPicture' => $gcinterviewsummary->summaryPicture,
                    'picture' => $gcinterviewsummary->picture,
                    'type' => $gcinterviewsummary->type,
                    'date' => $gcinterviewsummary->date,
                    'location' => $gcinterviewsummary->location_en,
                    'content' => json_decode($gcinterviewsummary->content_en), 
                );
            }else{
                 $data= array(
                    'person' => json_decode($gcinterviewsummary->person),
                    'interviewId' => $gcinterviewsummary->guid,
                    'name' => $gcinterviewsummary->name,
                    'interviewIndex' => $gcinterviewsummary->interviewIndex,
                    'summaryPicture' => $gcinterviewsummary->summaryPicture,
                    'picture' => $gcinterviewsummary->picture,
                    'type' => $gcinterviewsummary->type,
                    'date' => $gcinterviewsummary->date,
                    'location' => $gcinterviewsummary->location,
                    'content' => json_decode($gcinterviewsummary->content), 
                );

            }

            // echo json_decode($gcinterviewsummary->content);
          

           

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



}