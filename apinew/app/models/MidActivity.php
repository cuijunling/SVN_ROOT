<?php

class MidActivity extends \Phalcon\Mvc\Model
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
    public $date;

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
    public $place;

    /**
     *
     * @var string
     */
    public $place_en;

    /**
     *
     * @var string
     */
    public $photo;

    /**
     *
     * @var string
     */
    public $contents;

    /**
     *
     * @var string
     */
    public $contents_en;

    /**
     *
     * @var integer
     */
    public $flag;

    /**
     *
     * @var string
     */
    public $sign;

    /**
     *
     * @var string
     */
    public $sign_en;


     /**
     *
     * @var string
     */
    public $questionnarieId;

    /**
     *
     * @var string
     */
    public $updatetime;

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
        return 'mid_activity';
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
