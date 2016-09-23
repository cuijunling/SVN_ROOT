<?php

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Dto\ResutltDto as resutltDto;
use Dto\LawfirmDto as lawfirmDto;
use Dto\InfoDto as lawyersInfoDto;
use Dto\LawyerItemDto as lawyerItemDto;

class LawfirmController extends ControllerBase
{

    public function initialize()
    {
        $this->view->disable();
        header('Content-type:application/json;charset=utf-8');
    }


    public function infoAction()
    {
        $resutltDto = new resutltDto();

        $categoryId = null;
        // $guid=null;
        $language = "zh";
        if ($this->request->isGet()) {

            if (isset($_GET["firmId"])) {
                $guid = $this->request->getQuery("firmId");
            } else {
                $resutltDto->state = false;
                $resutltDto->msg = "The request has no parameters albid";
                echo json_encode($resutltDto);
                exit;
            }

            if (isset($_GET["categoryId"])) {
                $categoryId = $this->request->getQuery("categoryId", "int");
            }

            if (isset($_GET["language"])) {
                $language = $this->request->getQuery("language");
            }

            $lawfirm = MidLawfirm::findFirstByguid($guid);

            if (!$lawfirm) {
                $resutltDto->state = false;
                $resutltDto->msg = "Could not find the data";
                echo json_encode($resutltDto);
                exit;
            }

            $data = new lawfirmDto();
            $data->id = $guid;

            if ($language == 'en') {
                $data->basicInfo = json_decode($lawfirm->basicInfo_en);

                $data->albAwards = $lawfirm->albAwards_en;
            } else {
                $data->basicInfo = json_decode($lawfirm->basicInfo);
                $data->albAwards = $lawfirm->albAwards;
            }
            $Alb = json_decode($data->albAwards);
            $alb_new = array();
            $lawyerIds = array();
            $lawyerIds_awards = array();
            if (!$categoryId) {

                foreach ($Alb as $alb) {
                    array_push($alb_new, $alb);
                    if ($alb->type == "lawyer") {
                        array_push($lawyerIds, $alb->id);
                        if ($alb->isWin == "awards") {
                            array_push($lawyerIds_awards, $alb->id);
                        }
                    }
                }
            } else {
                foreach ($Alb as $alb) {
                    if ($alb->practiceArea == $categoryId) {
                        array_push($alb_new, $alb);
                        if ($alb->type == "lawyer") {
                            array_push($lawyerIds, $alb->id);
                            if ($alb->isWin == "awards") {
                                array_push($lawyerIds_awards, $alb->id);
                            }
                        }
                    }
                }
            }
            $data->albAwards = $alb_new;
            $recommendLawyer = new lawyersInfoDto();
            $awardLawyer = new lawyersInfoDto();
            $lawyersInfos = array();
            $viplevels = array();
            $guids_lawyer = array();
            $mid_Lawyers = MidLawyer::find();
            foreach ($mid_Lawyers as $mid_lawyer) {
                $basics_lawyer = $mid_lawyer->basicInfo;
                $midlawyer_firmId = json_decode($basics_lawyer)->firmId;
                if ($midlawyer_firmId == $guid) {
                    $viplevel = json_decode($basics_lawyer)->viplevel;
                    $guid_lawyer = json_decode($basics_lawyer)->guid;
//                    array_push($viplevels, $viplevel);
                    array_push($guids_lawyer, $guid_lawyer . ',' . $viplevel);
                }
            }
            $item = new lawyerItemDto();
            if (count(array_unique($guids_lawyer)) > 0) {
                $recommendItems = array();
                foreach ($guids_lawyer as $lawyer_id_vip) {
                    $lawyer_id_push = array();

                    $localton_num = strpos($lawyer_id_vip, ',');
                    $lawyer_id = substr($lawyer_id_vip, 0, $localton_num);
                    $viplevel = substr($lawyer_id_vip, $localton_num + 1);
                    $lawyer = MidLawyer::findFirstByguid($lawyer_id);
                    $basicInfo = $lawyer->basicInfo;
                    $basicInfoEn = $lawyer->basicInfo_en;

                    //十进制转二进制
                    $viplevel_bin = decbin($viplevel);
                    //下面拼接四位的二进制字符串。如果是5位状态码，$num=5，十进制转的二进制不足$num的用 0 补齐。
               /*     $str_static = 0;
                    $num = 4;
                    $several_times = $num - strlen($viplevel_bin);
                    $str_complete = str_repeat($str_static, $several_times);
                    $str_combine = $str_complete . $viplevel_bin;
                    $take_viplevel = substr($str_combine, 1, 1);*/
                    $cal_viplevel=$viplevel&4;
                    if ($cal_viplevel >= 4) {
                        if ($language == "en") {
                            $firm = json_encode(
                                json_decode($basicInfoEn)->firm
                            );
                            $item->lawyerId = $lawyer_id;
                            $item->name = json_decode($basicInfoEn)->name;
                            $item->firm = json_decode($firm)->name;

                            if (!empty(json_decode($basicInfoEn)->photo)) {
                                $item->photo = json_decode($basicInfoEn)->photo;
                            }

                            $item->title = json_decode($basicInfoEn)->title;
                        } else {
                            $firm = json_encode(json_decode($basicInfo)->firm);
                            $item->lawyerId = $lawyer_id;
                            $item->name = json_decode($basicInfo)->name;
                            $item->firm = json_decode($firm)->name;

                            if (!empty(json_decode($basicInfo)->photo)) {
                                $item->photo = json_decode($basicInfo)->photo;
                            }
                            $item->title = json_decode($basicInfo)->title;
                        }
                        $its_arr = $this->trans_one($item);
                        array_push($recommendItems, $its_arr);
                    }
                }
                $recommendLawyer->type = "recommendLawyer";
                $recommendLawyer->items = $recommendItems;
            }
            if (count(array_unique($lawyerIds)) > 0) {
                $awardItems = array();
                $lawyerId_push = array();
                foreach ($lawyerIds as $lawyerId) {
                    $lawyer = MidLawyer::findFirstByguid($lawyerId);
                    $basicInfo = $lawyer->basicInfo;
                    $basicInfoEn = $lawyer->basicInfo_en;
                    if (in_array($lawyerId, $lawyerIds_awards)) {
                        if ($language == "en") {
                            $firm = json_encode(
                                json_decode($basicInfoEn)->firm
                            );
                            $item->lawyerId = $lawyerId;
                            $item->name = json_decode($basicInfoEn)->name;
                            $item->firm = json_decode($firm)->name;
                            if (!empty(json_decode($basicInfoEn)->photo)) {
                                $item->photo = json_decode($basicInfoEn)->photo;
                            }
                            $item->title = json_decode($basicInfoEn)->title;
                        } else {
                            $firm = json_encode(json_decode($basicInfo)->firm);
                            $item->lawyerId = $lawyerId;
                            $item->name = json_decode($basicInfo)->name;
                            $item->firm = json_decode($firm)->name;

                            if (!empty(json_decode($basicInfo)->photo)) {

                                $item->photo = json_decode($basicInfo)->photo;
                            }

                            $item->title = json_decode($basicInfo)->title;
                        }
                        $item_awards = $this->trans_one($item);
                        if (!in_array($lawyerId, $lawyerId_push)) {
                            array_push($lawyerId_push, $lawyerId);
                            array_push($awardItems, $item_awards);
                        }
                    }
                }
                $recommend_filter=$this->unique_arr($awardItems,$recommendItems,'lawyerId');
                $recommendLawyer->items = array_merge(
                    $recommend_filter, $awardItems
                );
                $awardLawyer->type = "awardLawyer";
                $awardLawyer->items = $awardItems;
                array_push($lawyersInfos, $recommendLawyer);
                array_push($lawyersInfos, $awardLawyer);
            }
            $data->lawyersInfo = $lawyersInfos;
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

    public function unique_arr($arr, $brr, $column)
    {
        $ak_arr = array();
        foreach ($arr as $av) {
                array_push($ak_arr, $av[$column]);
        }
        foreach ($brr as $bk=>$bv) {
                if(in_array($bv[$column],$ak_arr)){
                    unset($brr[$bk]);
                };
        }
        return $brr;
    }

    public function fullinfoAction()
    {
        $resutltDto = new resutltDto();
        $categoryId = null;
        $language = "zh";
        if ($this->request->isGet()) {

            if (isset($_GET["firmId"])) {
                $guid = $this->request->getQuery("firmId");
            } else {
                $resutltDto->state = false;
                $resutltDto->msg = "The request has no parameters albid";
                echo json_encode($resutltDto);
                exit;
            }

            if (isset($_GET["categoryId"])) {
                $categoryId = $this->request->getQuery("categoryId", "int");
            }

            if (isset($_GET["language"])) {
                $language = $this->request->getQuery("language");
            }

            $lawfirm = MidLawfirm::findFirstByguid($guid);

            if (!$lawfirm) {
                $resutltDto->state = false;
                $resutltDto->msg = "Could not find the data";
                echo json_encode($resutltDto);
                exit;
            }
            $lawfirmInfos = array();
            if (is_object($lawfirm)) {
                foreach ($lawfirm as $key => $value) {
                    $lawfirmInfos[$key] = $value;
                }
            }

            $data = new lawfirmDto();
            $data->id = $guid;
            $data->guid = $guid;

            if ($language == 'en') {
                $basicInfos = $lawfirmInfos['basicInfo_en'];
                $infos = $lawfirmInfos['info_en'];
            } else {
                $basicInfos = $lawfirmInfos['basicInfo'];
                $infos = $lawfirmInfos['info'];

            }
            $basicInfo = array(
                'name' => json_decode($basicInfos)->name,
                'id' => json_decode($basicInfos)->id,
                'guid' => $guid,
                'homepage' => json_decode($basicInfos)->homepage,
            );
            $data->vip = json_decode($basicInfos)->vip;
            $data->basicInfo = $basicInfo;
//定义数组$arr，$brr,拼接新数组使用。
            $arr = array();
            $brr = array();
            if (empty($infos) || count($infos) == 0) {
                $resutltDto->state = false;
                $resutltDto->msg = "Could not find the data！";
                $resutltDto->data = null;
                echo json_encode($resutltDto);
                exit;
            }
            foreach (json_decode($infos) as $vinfo) {
                foreach ($vinfo as $key => $value) {
                    $arr[$key] = $value;
                    $arr = array_reverse($arr);
                }
                if ($arr['type'] == 'basic') {
                    $content_info = $arr['content'];
                    foreach ($content_info as $content_val) {
                        if ($content_val->type == 'awards') {
                            $details = $content_val->detail;
                            foreach ($details as $filter_details) {
                                unset($filter_details->updateTime);
                                unset($filter_details->albdetailitemid);
                            }
                            $thisyear_num = date('Y', time());
//                            此处为最优写法，出现一个问题，未解决，之后做修改。
                            $detail_arr_all = array();

                            for ($i = 2013; $i <= $thisyear_num; $i++) {
                                $const = 'detail_arr_' . $i;
                                $$const = array();
                                foreach ($details as $details_val) {
                                    if ($details_val->year == "$i") {
                                        array_push($$const, $details_val);
                                    }
                                }
                                if (empty($$const)) {
                                    $$const = null;
                                };
                                $$const = array_reverse($$const);
                                $detail_arr = [
                                    'year' => "$i", 'items' => $$const
                                ];
                                array_push($detail_arr_all, $detail_arr);
                            }
                            $detail_arr_all = array_reverse($detail_arr_all);
                            $content_val->detail = $detail_arr_all;


//                            如果需要进行排序，下面是按照albid 降序排列。下面是一个示范的例子
                            //对象-->数组,然后利用函数，排序。
                            /* $detail_arr = $this->trans($detail_arr);
                               $detail_arr = $this->array_sort(
                                   $detail_arr, 'albid', 'desc'
                               );
                            */
                        } elseif ($content_val->type == 'keyClient') {
                            $keyClient_detail = $content_val->detail;
                            $keyClient_infos = $keyClient_detail[0]->detail;
                            $baby_Words = explode(';', $keyClient_infos);

                            foreach ($baby_Words as $k => $baby_word) {
                                $baby_Words[$k] = array('detail' => $baby_word);
                            }
                            $content_val->detail = $baby_Words;
                        } elseif ($content_val->type == 'office') {
                            $office_detail = $content_val->detail;
                            $office_infos = $office_detail[0]->detail;
                            $Baby_office = str_replace(',', '、', $office_infos);
                            $office_all
                                = array(array('detail' => $Baby_office));
                            $content_val->detail = $office_all;
                        } else {
                            $content_val->detail = $content_val->detail;
                        }
                    }

                    $reArr = array();
                    foreach ($arr['content'] as $k => $v) {
                        if ($v->type && is_string($v->type)) {
                            if ('richIntro' === $v->type) {
                                $reArr[0] = $v;
                            }
                            if ('practiceArea' === $v->type) {
                                $reArr[1] = $v;
                            }
                            if ('keyClient' === $v->type) {
                                $reArr[2] = $v;
                            }
                            if ('office' === $v->type) {
                                $reArr[3] = $v;
                            }
                            if ('awards' === $v->type) {
                                $reArr[4] = $v;
                            }
                        }

                    }
                    ksort($reArr);
                    foreach ($reArr as $k => $v_reArr) {
                        foreach ($v_reArr->detail as $filter_reArr) {
                            $count = count($filter_reArr);
                            if (is_object($filter_reArr)) {
                                if ($filter_reArr->detail == '') {
                                    unset($reArr[$k]);
                                }
                            }
                        }
                    }
                    $arr['content'] = array_values($reArr);
                    array_push($brr, $arr);
                } elseif ($arr['type'] == 'practicearea') {
                    $content_info = $arr['content'];
                    foreach ($content_info as $content_vals) {
                        $practice_contents = $content_vals->contents;
                        foreach ($practice_contents as $con_val) {
                            if ($con_val->type == 'analysis') {
                                $p_details = $con_val->details;
                                foreach ($p_details as $p_filter_details) {
                                    $items_Infos = $p_filter_details->items;
                                    $items_arr = array();
                                    foreach (
                                        $p_filter_details->items as $items_info
                                    ) {
                                        $items_one = array(
                                            'num' => intval(
                                                $items_info->number
                                            ),
                                            'name' => $items_info->typeName
                                        );

                                        array_push($items_arr, $items_one);
                                    }
                                    $p_filter_details->items = $items_arr;
                                }
                            }
                        }
                    }
                    array_push($brr, $arr);
                } elseif ($arr['type'] == 'lawfirminfo') {
                    array_push($brr, $arr);
                }

            }
        }
        $data->Info = $brr;
        $resutltDto->state
            = true;
        $resutltDto->msg
            = "Successful access to information！";
        $resutltDto->data
            = $data;

        echo json_encode($resutltDto);
        exit;
    }

    /*这个方法是把多维数组进行排序，
     * $arr 必须是数组 array() 类型
     * $keys,根据键值进行排序，
     * 默认是按照 asc 正序排序。
     */
    function array_sort($arr, $keys, $type = 'asc')
    {

        $keysvalue = $new_array = array();
        foreach ($arr as $k => $v) {
            $keysvalue[$k] = $v[$keys];
        }
        if ($type == 'asc') {
            asort($keysvalue);
        } else {
            arsort($keysvalue);
        }
        reset($keysvalue);
        foreach ($keysvalue as $k => $v) {
            $new_array[] = $arr[$k];
        }
        return $new_array;
    }

//这个方法用于数组和对象之间的转换 ：把对象转换为数组，限于一维
    function trans_one($awards_obj)
    {
        $all_arrs = array();
        foreach ($awards_obj as $k => $value) {
            $all_arrs[$k] = $value;
        }
        return $all_arrs;
    }

    //这个方法用于数组和对象之间的转换 ：把对象转换为数组，限于二维
    function trans($awards_arr)
    {
        $new_arr = array();
        $all_arrs = array();
        foreach ($awards_arr as $value) {
            if (is_object($value)) {
                foreach ($value as $key => $av) {
                    $new_arr[$key] = $av;
                }
            }
            array_push($all_arrs, $new_arr);
        }
        return $all_arrs;
    }
}





   
