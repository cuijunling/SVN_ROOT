<?php

class MidGcperson extends \Phalcon\Mvc\Model
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
    public $company;

    /**
     *
     * @var string
     */
    public $title;

    /**
     *
     * @var string
     */
    public $photo;

    /**
     *
     * @var string
     */
    public $introduce;

    /**
     *
     * @var string
     */
    public $name_en;

    /**
     *
     * @var string
     */
    public $company_en;

    /**
     *
     * @var string
     */
    public $title_en;

    /**
     *
     * @var string
     */
    public $introduce_en;

    /**
     *
     * @var integer
     */
    public $eliteIndex;

    /**
     *
     * @var string
     */
    public $horizonPhoto;

    /**
     *
     * @var integer
     */
    public $type;

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
        return 'mid_gcperson';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return MidGcperson[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return MidGcperson
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
