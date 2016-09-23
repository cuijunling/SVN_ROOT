<?php

class MidActivitynews extends \Phalcon\Mvc\Model
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
    public $guid;

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
     * @var integer
     */
    public $newsIndex;

    /**
     *
     * @var string
     */
    public $date;

    /**
     *
     * @var integer
     */
    public $flag;

    /**
     *
     * @var string
     */
    public $updateTime;

    /**
     *
     * @var string
     */
    public $sign;

    /**
     *
     * @var integer
     */
    public $updated;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'mid_activitynews';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return MidActivitynews[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return MidActivitynews
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
