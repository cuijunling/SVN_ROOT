<?php

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Dto\ResutltDto as resutltDto;
use Dto\AlblistDto as albListDto;
use Dto\AlbdetailDto as albdetailDto;


class AlbController extends ControllerBase
{

    public function initialize()
    {
        $this->view->disable();
        header('Content-type:application/json;charset=utf-8');
    }

    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * http://{domainname}/api/ alb/list?language=en& start=1& size=10
     **/

    public function listAction()
    {

        $resutltDto = new resutltDto();
        $start = 0;
        $size = 10;
        $language = "zh";
        if ($this->request->isGet()) {

            if (isset($_GET["start"])) {
                $start = $this->request->getQuery("start", "int");
            }
            if (isset($_GET["size"])) {
                $size = $this->request->getQuery("size", "int");
            }
            if (isset($_GET["language"])) {
                $language = $this->request->getQuery("language");
            }

            if ($language == "zh") {
                $AlbList = MidAlblist::find(
                    array
                    (
                        "conditions" => "flag&0x11=0x10",
                        'order' => 'sort asc'
                    )
                );
            } else {
                $AlbList = MidAlblist::find(
                    array
                    (
                        "conditions" => "flag&0x3000=0x1000",
                        'order' => 'sort asc'
                    )
                );
            }

            $AlbListDtos = array();

            foreach ($AlbList as $albList) {
                $list_guid=$albList->guid;

                $alb_info =MidAlb::findFirstByguid($list_guid);

                if ($alb_info && count($alb_info) > 0) {
                    $segmentItems = $alb_info->segmentItems;

                    $albListDto = new albListDto();
                    if ($language == "en") {
                        $albListDto->id = $albList->guid;
                        $albListDto->name = $albList->name_en;
                        $albListDto->summary = $albList->summary_en;
                        $albListDto->month = $albList->month;
                        $albListDto->year = $albList->year;
                        $albListDto->mainType = $albList->mainType;
                        $albListDto->flag = $albList->flag;
                        $albListDto->updateTime = $albList->updateTime;
                    } else {
                        $albListDto->id = $albList->guid;
                        $albListDto->name = $albList->name;
                        $albListDto->summary = $albList->summary;
                        $albListDto->month = $albList->month;
                        $albListDto->year = $albList->year;
                        $albListDto->mainType = $albList->mainType;
                        $albListDto->flag = $albList->flag;
                        $albListDto->updateTime = $albList->updateTime;
                    }
                    if ($segmentItems && count($segmentItems) > 0) {
                        array_push($AlbListDtos, $albListDto);
                    }
                }
            }

            $resutltDto->state = true;
            $resutltDto->msg = "Successful access to information！";
            $resutltDto->data = array_slice($AlbListDtos, $start, $size);
        } else {
            $resutltDto->state = false;
            $resutltDto->msg = "The request method error";
        }


        echo json_encode($resutltDto);

    }

    /**
     *    http://{domainname}/api/alb/detail? albid=201606&language=en
     **/
    public function detailAction()
    {
        $resutltDto = new resutltDto();
        $language = "zh";

        if ($this->request->isGet()) {
            if (isset($_GET["language"])) {
                $language = $this->request->getQuery("language");
            }
            if (isset($_GET["albid"])) {
                $albid = $this->request->getQuery("albid", "int");
            } else {
                $resutltDto->state = false;
                $resutltDto->msg = "The request has no parameters albid";
                echo json_encode($resutltDto);
                exit;
            }
           if($language='zh'){
                $where=array("conditions"=>"guid=$albid AND flag&0x11=0x10");
            }else{
               $where=array("conditions"=>"guid=$albid AND flag&0x3000=0x1000");
            }
            $albdetailDto = new albdetailDto();
            $alb = MidAlb::findFirst($where);
            if (!$alb) {
                $resutltDto->state = false;
                $resutltDto->msg = "Could not find the data";
                echo json_encode($resutltDto);
                exit;
            }
            if ($language == "en") {
                $segmentItems = json_decode($alb->segmentItems_en);
            } else {
                $segmentItems = json_decode($alb->segmentItems);
            }
            $segmentItems = json_decode($alb->segmentItems);
            foreach ($segmentItems as $v_segmentItems) {
                    foreach ($v_segmentItems->classItems as $v_Items) {
                        if($v_Items->introduce==""){
                            $v_Items->introduce=NULL;
                        }


                        foreach ($v_Items->items as $v) {
                            if($v->addedInfo==""){
                                $v->addedInfo=NULL;
                            }
                            if($v->method==""){
                                $v->method=NULL;
                            }
                            if(count($v->introduce)==0||$v->introduce==""){
                                $select_types=$v->type;

                                if($select_types=='firm'||$select_types=='lawyer'){
                                    foreach ($v->itemDetail as $itemDetail) {
                                        if (is_object($itemDetail) && count($itemDetail)> 0) {
                                            $detail_intro = $itemDetail->introduce;
                                            if (!$detail_intro || $detail_intro == '') {
                                                $itemDetail->introduce = NULL;
                                            }
                                            $v->introduce = $itemDetail->introduce;
                                        }
                                    }
                                }elseif($select_types=='gc_person'||$select_types=='gc_group'){
                                    $itemDetail=$v->itemDetail;
                                    if($itemDetail->introduce==""){
                                        $itemDetail->introduce=NULL;
                                    }

                                    $v->introduce = $itemDetail->introduce;


                                }elseif($select_types=='project'){
                                    $v->introduce =NULL;
                                }

                            }

                        }
                    }

                if (!$v_segmentItems || $v_segmentItems == ''
                ) {
                    $v_segmentItems->introduce = NULL;
                }
            }

            if ($language == "en") {

                $albdetailDto->id = $alb->id;
                $albdetailDto->name = $alb->name_en;
                $albdetailDto->albId = $alb->guid;
                $albdetailDto->method = $alb->method_en;
                $albdetailDto->mainType = $alb->mainType;
                $albdetailDto->segmentType = $alb->segmentType;
                $albdetailDto->isWin = $alb->isWin;
                $albdetailDto->segmentItems = $segmentItems;

            } else {

                $albdetailDto->id = $alb->id;
                $albdetailDto->name = $alb->name;
                $albdetailDto->albId = $alb->guid;
                $albdetailDto->method = $alb->method;
                $albdetailDto->mainType = $alb->mainType;
                $albdetailDto->segmentType = $alb->segmentType;
                $albdetailDto->isWin = $alb->isWin;
                $albdetailDto->segmentItems = $segmentItems;
            }

            $resutltDto->state = true;
            $resutltDto->msg = "Successful access to information！";
            $resutltDto->data = $albdetailDto;

        } else {
            $resutltDto->state = false;
            $resutltDto->msg = "The request method error";
        }

        echo json_encode($resutltDto);
    }

    ####add by jianli yy
    public function detailoptionAction()
    {
        $resutltDto = new resutltDto();
        $language = "zh";
        if ($this->request->isGet()) {
            if (isset($_GET["language"])) {
                $language = $this->request->getQuery("language");
            }
            if (isset($_GET["albid"])) {
                $albid = $this->request->getQuery("albid", "int");
            } else {
                $resutltDto->state = false;
                $resutltDto->msg = "The request has no parameters albid";
                echo json_encode($resutltDto);
                exit;
            }
            if($language='zh'){
                $where=array("conditions"=>"guid=$albid AND flag&0x11=0x10");
            }else{
                $where=array("conditions"=>"guid=$albid AND flag&0x3000=0x1000");
            }
            $albdetailDto = new albdetailDto();

            $alb = MidAlb::findFirst($where);

            if (!$alb) {
                $resutltDto->state = false;
                $resutltDto->msg = "Could not find the data";
                echo json_encode($resutltDto);
                exit;
            }

            if ($language == "en") {

                $albdetailDto->id = $alb->id;
                $albdetailDto->name = $alb->name_en;
                $albdetailDto->albId = $alb->guid;
                $albdetailDto->method = $alb->method_en;
                $albdetailDto->mainType = $alb->mainType;
                $albdetailDto->segmentType = $alb->segmentType;
                $albdetailDto->isWin = $alb->isWin;
                $albdetailDto->segmentItems = json_decode(
                    $alb->segmentItems_en
                );

            } else {

                $albdetailDto->id = $alb->id;
                $albdetailDto->name = $alb->name;
                $albdetailDto->albId = $alb->guid;
                $albdetailDto->method = $alb->method;
                $albdetailDto->mainType = $alb->mainType;
                $albdetailDto->segmentType = $alb->segmentType;
                $albdetailDto->isWin = $alb->isWin;
                $s = $this->filterSegmentItems($alb->segmentItems);
                #$albdetailDto->segmentItems=json_decode($alb->segmentItems);
                $albdetailDto->segmentItems = json_decode($s);

            }

            $resutltDto->state = true;
            $resutltDto->msg = "Successful access to information！";
            $resutltDto->data = $albdetailDto;

        } else {
            $resutltDto->state = false;
            $resutltDto->msg = "The request method error";
        }

        echo json_encode($resutltDto);
    }

    private function filterSegmentItems($c)
    {
        $cs = json_decode($c, true);
        foreach ($cs[0]['classItems'] as $k => $v) {
            #unset($cs['classItems']['items'][$k]['introduce']);
            #$cs['classItems']['items'][$k]['introduce']="abc";
            foreach ($cs[0]['classItems'][$k]['items'] as $k1 => $v1) {
                unset($cs[0]['classItems'][$k]['items'][$k1]['itemDetail']['introduce']);
                #unset($cs[0]['classItems'][$k]['items'][$k1]['introduce']);
                /*
                if($k1>1){
                    unset($cs[0]['classItems'][$k]['items'][$k1]);
                }*/
            }
        }
        return json_encode($cs);
    }

}
