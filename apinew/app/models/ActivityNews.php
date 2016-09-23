<?php
use Phalcon\Mvc\Model\Behavior\Timestampable;

class ActivityNews extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $newsId;

    /**
     *
     * @var string
     */
    public $activityId;

    /**
     *
     * @var string
     */
    public $content;

    /**
     *
     * @var string
     */
    public $content_en;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $name_en;

    /**
     *
     * @var string
     */
    public $photo;

    /**
     *
     * @var string
     */
    public $newsIndex;

    /**
     *
     * @var string
     */
    public $date;

    /**
     *
     * @var string
     */
    public $flag;
  
    /**
     *
     * @var string
     */
    public $updatetime;


    public function initialize()
    {
        date_default_timezone_set('Asia/Shanghai');
        $this->addBehavior(
            new Timestampable(
                array(
                    'beforeSave' => array(
                    'field'    => 'updateTime',
                    'format' => 'Y-m-d H:i:s'
                    )
                    
                )
            )
        );

        $this->setConnectionService('mysql_ranking');
    }



    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'activitynews';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return MidActivity[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return MidActivity
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
