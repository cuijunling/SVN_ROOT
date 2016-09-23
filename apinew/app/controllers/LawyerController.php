<?php

use Dto\ResutltDto as resutltDto;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Dto\LawyerDto as lawyerDto;


class LawyerController extends ControllerBase
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

            $lawyerDto=new lawyerDto();

            if (isset($_GET["lawyerId"])) {
               $guid=$this->request->getQuery("lawyerId");
            }else{
               $resutltDto->state=false;
               $resutltDto->msg="The request has no parameters lawyerId";
               echo  json_encode($resutltDto);
               exit;
            }

            if (isset($_GET["language"])) {
              $language=$this->request->getQuery("language");
            }

           
            $lawyer = MidLawyer::findFirstByguid($guid);

            $lawyerDto->id=$lawyer->guid;
           
            if ($language=="en") {

                $basicInfo=json_decode($lawyer->basicInfo_en);

                $lawyerDto->name=$basicInfo->name;
                $lawyerDto->tele=$basicInfo->telephone;
                $lawyerDto->admission=$basicInfo->admission;
                $lawyerDto->viplevel=$basicInfo->viplevel;
                $lawyerDto->email=$basicInfo->email;

                $firm=json_encode($basicInfo->firm);
                $lawyerDto->firm=$firm->name;
                $lawyerDto->info=json_decode($lawyer->info_en);

               
            }else{
                $basicInfo=json_decode($lawyer->basicInfo);

                $lawyerDto->name=$basicInfo->name;
                $lawyerDto->tele=$basicInfo->telephone;
                $lawyerDto->admission=$basicInfo->admission;
                $lawyerDto->viplevel=$basicInfo->viplevel;
                $lawyerDto->email=$basicInfo->email;

                $firm=$basicInfo->firm;
                $lawyerDto->firm=$firm->name;
                $lawyerDto->info=json_decode($lawyer->info);

            }
                if (!empty($basicInfo->photo)) {
			         $imgUrl = $this->getImagesUrl($basicInfo->photo);
                        $lawyerDto->photo= $imgUrl;
                }

            
            $resutltDto->data=$lawyerDto;
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
