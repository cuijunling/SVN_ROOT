<?php

use Dto\ResutltDto as resutltDto;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Dto\InfoDto as activityInfo;
use Dto\ActivityItemDto as ActivityItemDto;
use Dto\ActivitydetailDto as ActivitydetailDto;

class ActivityController extends ControllerBase
{
    public function initialize()
    {
        $this->view->disable();
        header('Content-type:application/json;charset=utf-8');

    }

    /*
        获取活动首页信息接口

    */
    public function homeAction()
    {
        $resutltDto = new resutltDto();


        $language = "zh";
        if ($this->request->isGet()) {
            if (isset($_GET["language"])) {
                $language = $this->request->getQuery("language");
            }

            $active = new activityInfo();
            $noActive = new activityInfo();
            $news = new activityInfo();

            $activeItems = array();
            $noActiveItems = array();
            $newsItem = array();

            $ActivityNews = MidActivityNews::find(
                array('order' => 'date DESC')
            );

            foreach ($ActivityNews as $item) {

                if ($language == "en") {
                    $new = array(

                        'guid' => $item->guid,
                        'name_en' => $item->name_en,
                        'photo' => $item->photo,
                        'date' => $item->date
                    );
                } else {
                    $new = array(
                        'guid' => $item->guid,
                        'name' => $item->name,
                        'photo' => $item->photo,
                        'date' => $item->date
                    );
                }

                array_push($newsItem, $new);

            }

            $activitys = MidActivity::find(array('order' => 'date DESC'));

            foreach ($activitys as $activity) {
                $nowTime = date("y-m-d");
                $item = new ActivityItemDto();
                $item->activityId = $activity->guid;
                $item->imgPath = $activity->photo;

                $praise = Praise::findFirstByeventId($activity->guid);

                if (!$praise) {
                    $item->count = 0;

                    $praise = new Praise();
                    $praise->guid = $this->guid();
                    $praise->eventId = $activity->guid;
                    $praise->count = 0;
                    $praise->type = "activity";

                    if (!$praise->save()) {

                        $resutltDto->state = false;
                        $resutltDto->msg = "Failed to get the like number";
                        echo json_encode($resutltDto);
                        exit;
                    }

                } else {
                    $item->count = $praise->count;
                }


                if ($language == "en") {
                    $item->address = $activity->place_en;
                    $item->activityName = $activity->name_en;

                } else {
                    $item->address = $activity->place;
                    $item->activityName = $activity->name;
                }

                $item->time = $activity->date;

                if (strtotime($nowTime) <= strtotime($activity->date)) {

                    array_push($activeItems, $item);

                } else {

                    array_push($noActiveItems, $item);
                }


            }

            $active->type = "active";
            $active->items = $activeItems;

            $noActive->type = "noActive";
            $noActive->items = $noActiveItems;

            $news->type = "news";
            $news->items = array_slice($newsItem, 0, 3);


            $data = array();


            if (count($activeItems) > 0) {
                array_push($data, $active);
                array_push($data, $noActive);
            } else {
                array_push($data, $news);
                array_push($data, $noActive);

            }

            $resutltDto->state = true;
            $resutltDto->msg = "Successful access to information！";
            $resutltDto->data = $data;
        } else {

            $resutltDto->state = false;
            $resutltDto->msg = "The request method error";
        }

        echo json_encode($resutltDto);
        exit;
    }


    public function detailAction()
    {
        $resutltDto = new resutltDto();
        $language = "zh";
        if ($this->request->isGet()) {

            if (isset($_GET["id"])) {
                $guid = $this->request->getQuery("id");
            } else {
                $resutltDto->state = false;
                $resutltDto->msg = "The request has no parameters id";
                echo json_encode($resutltDto);
                exit;
            }

            if (isset($_GET["language"])) {
                $language = $this->request->getQuery("language");
            }

            $activity = MidActivity::findFirstByguid($guid);

            if (!$activity) {
                $resutltDto->state = false;
                $resutltDto->msg = "The activity of information not found";
                echo json_encode($resutltDto);
                exit;
            }


            $activityDetail = new ActivitydetailDto();

            $activityDetail->id = $activity->guid;
            $activityDetail->date = $activity->date;
            $activityDetail->photo = $activity->photo;

            if ($language == "en") {
                $activityDetail->name = $activity->name_en;
                $activityDetail->place = $activity->place_en;
                $activityDetail->contents = json_decode($activity->contents_en);

            } else {
                $activityDetail->name = $activity->name;
                $activityDetail->place = $activity->place;
                $activity_contents = json_decode($activity->contents);

                foreach ($activity_contents as $key => $value) {
                    if (count($value->items) == 0) {
                        $value->items = null;
                    }
                    if ($value->type == 'schedule') {
                        if (count($value->items) > 0
                            && $value->items !== null
                        ) {
                            foreach ($value->items as $v2) {
                                if (count($v2) > 0) {
                                    foreach ($v2 as $k3 => $v3) {
                                        if ($v3&&count($v3)>0) {
                                            if($k3=='items'){
                                                foreach ($v3 as $k4 => $v4) {
                                                    if ($v4&&count($v4)>0){
                                                        foreach ($v4 as $v5) {
                                                            if (is_array($v5)||is_object($v5)&&count($v5)>0){
                                                                foreach ($v5 as $v6) {
                                                                    $speakers=$v6->speakers;
                                                                    $v6->speakers=$speakers->items;
                                                                    if(count($v6->speakers)==0){
                                                                        $v6->speakers=NULL;
                                                                    }
                                                                }


                                                            }

                                                        }

                                                    }

                                                }
                                            }

                                          }

                                    }
                                }
                            }
                        }
                    }

                }
                $activityDetail->contents = $activity_contents;
            }

            $questionnaireId = $activity->questionnaireId;
            $questionnaire = Questionnaire::findFirstByquestionnaireId(
                $questionnaireId
            );

            if (empty($questionnaireId) || !$questionnaire) {
                $activityDetail->flag = false;
            } else {
                $activityDetail->flag = true;
                $activityDetail->questionnaireId = $questionnaireId;

            }

            $nowTime = date("y-m-d");
            if (strtotime($nowTime) <= strtotime($activity->date)) {
                $activityDetail->active = true;
            } else {
                $activityDetail->active = false;
            }


            $resutltDto->data = $activityDetail;
            $resutltDto->state = true;
            $resutltDto->msg = "Successful access to information！";

        } else {
            $resutltDto->state = false;
            $resutltDto->msg = "The request method error";
        }


        echo json_encode($resutltDto);
        exit;

    }


    public function listAction()
    {

        $resutltDto = new resutltDto();

        $language = "zh";
        $start = 0;
        $size = 10;
        if ($this->request->isGet()) {
            if (isset($_GET["type"])) {
                $type = $this->request->getQuery("type");
            } else {
                $resutltDto->state = false;
                $resutltDto->msg = "The request has no parameters type";
                echo json_encode($resutltDto);
                exit;
            }

            if (isset($_GET["start"])) {
                $start = $this->request->getQuery("start", "int");
            }

            if (isset($_GET["size"])) {
                $size = $this->request->getQuery("size", "int");
            }

            if (isset($_GET["language"])) {
                $language = $this->request->getQuery("language");
            }


            $activityInfo = new activityInfo();


            $activeItems = array();
            $noActiveItems = array();

            $activitys = MidActivity::find();

            foreach ($activitys as $activity) {
                $nowTime = date("y-m-d");
                $item = new ActivityItemDto();
                $item->activityId = $activity->guid;
                $item->imgPath = $activity->photo;

                $praise = Praise::findFirstByeventId($activity->guid);

                if (!$praise) {
                    $item->count = 0;

                    $praise = new Praise();
                    $praise->guid = $this->guid();
                    $praise->eventId = $activity->guid;
                    $praise->count = 0;
                    $praise->type = "activity";

                    if (!$praise->save()) {

                        $resutltDto->state = false;
                        $resutltDto->msg = "Failed to get the like number";
                        echo json_encode($resutltDto);
                        exit;
                    }

                } else {
                    $item->count = $praise->count;
                }

                if ($language == "en") {
                    $item->address = $activity->place_en;
                    $item->activityName = $activity->name_en;
                } else {
                    $item->address = $activity->place;
                    $item->activityName = $activity->name;
                }

                $item->time = $activity->date;

                if (strtotime($nowTime) <= strtotime($activity->date)) {

                    array_push($activeItems, $item);

                } else {

                    array_push($noActiveItems, $item);
                }

            }

            if ($type == "active") {
                $activityInfo->type = $type;
                $activityInfo->items = array_slice($activeItems, $start, $size);

            } elseif ($type == "noActive") {
                $activityInfo->type = $type;
                $activityInfo->items = array_slice(
                    $noActiveItems, $start, $size
                );
            }


            $resutltDto->data = $activityInfo;
            $resutltDto->state = true;
            $resutltDto->msg = "Successful access to information！";

        } else {
            $resutltDto->state = false;
            $resutltDto->msg = "The request method error";
        }

        echo json_encode($resutltDto);
        exit;

    }

    public function newsAction()
    {
        $resutltDto = new resutltDto();

        $language = "zh";
        if ($this->request->isGet()) {
            if (isset($_GET["id"])) {
                $guid = $this->request->getQuery("id");
            } else {
                $resutltDto->state = false;
                $resutltDto->msg = "The request has no parameters id";
                echo json_encode($resutltDto);
                exit;
            }

            if (isset($_GET["language"])) {
                $language = $this->request->getQuery("language");
            }


            $news = MidActivityNews::findFirstByguid($guid);

            if (!$news) {
                $resutltDto->state = false;
                $resutltDto->msg = "Could not find the data";
                echo json_encode($resutltDto);
                exit;
            }
            if ($language == "en") {
                $resutltNews = array(
                    'id' => $news->guid,
                    'name' => $news->name_en,
                    'date' => $news->date,
                    'content' => $news->content_en,
                );
            } else {
                $resutltNews = array(
                    'id' => $news->guid,
                    'name' => $news->name,
                    'date' => $news->date,
                    'content' => $news->content,
                );
            }

            $resutltDto->data = $resutltNews;
            $resutltDto->state = true;
            $resutltDto->msg = "Successful access to information！";

        } else {
            $resutltDto->state = false;
            $resutltDto->msg = "The request method error";
        }
        echo json_encode($resutltDto);
        exit;
    }

    public function questionnaireAction()
    {

        $resutltDto = new resutltDto();

        $language = "zh";
        if ($this->request->isGet()) {
            if (isset($_GET["id"])) {
                $questionnaireId = $this->request->getQuery("id");
            } else {
                $resutltDto->state = false;
                $resutltDto->msg = "The request has no parameters id";
                echo json_encode($resutltDto);
                exit;
            }

            if (isset($_GET["language"])) {
                $language = $this->request->getQuery("language");
            }

            $questionnaire = Questionnaire::findFirstByquestionnaireId(
                $questionnaireId
            );

            if (!$questionnaire) {
                $resutltDto->state = false;
                $resutltDto->msg = "Could not find the data";
                echo json_encode($resutltDto);
                exit;
            }

            $questionIds = explode(",", $questionnaire->questions);

            $items = array();

            foreach ($questionIds as $questionId) {

                $question = Question::findFirstByquestionId($questionId);
                if (!$question) {
                    continue;
                }

                $item = array(
                    'question' => $question->question,
                    'answer' => explode(",", $question->answer),
                    'type' => $question->type,
                    'questionId' => $question->questionId,

                );
                array_push($items, $item);
            }


            $resutltQuestion = array(
                'id' => $questionnaire->questionnaireId,
                'name' => $questionnaire->name,
                'items' => $items

            );

            $resutltDto->data = $resutltQuestion;
            $resutltDto->state = true;
            $resutltDto->msg = "Successful access to information！";


        } else {
            $resutltDto->state = false;
            $resutltDto->msg = "The request method error";
        }
        echo json_encode($resutltDto);
        exit;

    }

    public function feedbackAction()
    {

        $resutltDto = new resutltDto();

        if ($this->request->isPost()) {

            $Feedback = new Feedback();

            if (isset($_POST["activityId"])) {
                $Feedback->activityId = $this->request->getPost("activityId");
            } else {
                $resutltDto->state = false;
                $resutltDto->msg = "The request has no parameters activityId";
                echo json_encode($resutltDto);
                exit;
            }


            $Feedback->personName = $this->request->getPost("name");

            $Feedback->generalComment = $this->request->getPost(
                "generalComment"
            );

            $Feedback->company = $this->request->getPost("company");

            $Feedback->title = $this->request->getPost("title");

            $answer = $this->request->getPost("answer");

            $answers = explode(";", $answer);

            $feedbackIds = array();


            foreach ($answers as $answ) {
                if (!empty($answ)) {


                    $item = explode(":", $answ);

                    $feedbackAnswer = new Feedbackanswer();

                    $feedbackAnswer->feedbackId = $this->guid();

                    $feedbackAnswer->questionId = $item[0];

                    $feedbackAnswer->content = $item[1];

                    if ($feedbackAnswer->save()) {

                        array_push($feedbackIds, $feedbackAnswer->feedbackId);

                    }

                }

            }

            $Feedback->feedbackId = implode(";", $feedbackIds);


            if (!$Feedback->save()) {

                $resutltDto->state = false;
                $resutltDto->msg = "Failed to submit";
                echo json_encode($resutltDto);
                exit;
            }

            $resutltDto->state = true;
            $resutltDto->msg = "Submitted successfully";

        } else {
            $resutltDto->state = false;
            $resutltDto->msg = "The request method error";
        }
        echo json_encode($resutltDto);
        exit;
    }

    public function registerAction()
    {
        $resutltDto = new resutltDto();

        if ($this->request->isPost()) {

            $register = new ActivityRegister();

            if (isset($_POST["activityId"])) {
                $register->activityId = $this->request->getPost("activityId");
            } else {
                $resutltDto->state = false;
                $resutltDto->msg = "The request has no parameters activityId";
                echo json_encode($resutltDto);
                exit;
            }

            $register->guid = $this->guid();
            $register->name = $this->request->getPost("name");
            $register->mobilephone = $this->request->getPost("mobilephone");
            $register->email = $this->request->getPost("email");
            $register->title = $this->request->getPost("title");
            $register->company = $this->request->getPost("company");


            if (!$register->save()) {

                $resutltDto->state = false;
                $resutltDto->msg = "Failed to submit";
                echo json_encode($resutltDto);
                exit;
            }

            $resutltDto->state = true;
            $resutltDto->msg = "Submitted successfully";


        } else {
            $resutltDto->state = false;
            $resutltDto->msg = "The request method error";
        }

        echo json_encode($resutltDto);
        exit;

    }


}
