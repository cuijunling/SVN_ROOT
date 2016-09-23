<?php

class MidAlb extends \Phalcon\Mvc\Model
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
    public $name;

    /**
     *
     * @var string
     */
    public $method;

    /**
     *
     * @var string
     */
    public $name_en;

    /**
     *
     * @var string
     */
    public $method_en;

    /**
     *
     * @var string
     */
    public $mainType;

    /**
     *
     * @var string
     */
    public $segmentType;

    /**
     *
     * @var string
     */
    public $segmentItems;

    /**
     *
     * @var string
     */
    public $segmentItems_en;

    /**
     *
     * @var string
     */
    public $isWin;

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
        return 'mid_alb';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return MidAlb[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return MidAlb
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
