<?php

class MidPracticeareas extends \Phalcon\Mvc\Model
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
    public $areaId;

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
    public $icon;

    /**
     *
     * @var string
     */
    public $icon_en;

    /**
     *
     * @var integer
     */
    public $index;

    /**
     *
     * @var integer
     */
    public $index_en;

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
        return 'mid_practiceareas';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return MidPracticeareas[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return MidPracticeareas
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
