<?php

class MidFile extends \Phalcon\Mvc\Model
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
    public $type;

    /**
     *
     * @var string
     */
    public $title;

    /**
     *
     * @var string
     */
    public $title_en;

    /**
     *
     * @var integer
     */
    public $likes;

    /**
     *
     * @var string
     */
    public $lawyerBasicModel;

    /**
     *
     * @var string
     */
    public $detail;

    /**
     *
     * @var string
     */
    public $lawyerBasicModel_en;

    /**
     *
     * @var string
     */
    public $detail_en;

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
        return 'mid_file';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return MidFile[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return MidFile
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
