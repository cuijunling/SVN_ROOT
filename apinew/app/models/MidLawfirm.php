<?php

class MidLawfirm extends \Phalcon\Mvc\Model
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
    public $basicInfo;

    /**
     *
     * @var string
     */
    public $basicInfo_en;

    /**
     *
     * @var string
     */
    public $albAwards;

    /**
     *
     * @var string
     */
    public $albAwards_en;

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
        return 'mid_lawfirm';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return MidLawfirm[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return MidLawfirm
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
