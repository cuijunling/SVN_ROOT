<?php

class MidAlblist extends \Phalcon\Mvc\Model
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
    public $summary;

    /**
     *
     * @var string
     */
    public $summary_en;

    /**
     *
     * @var string
     */
    public $homePicture;

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
     * @var integer
     */
    public $year;

    /**
     *
     * @var integer
     */
    public $month;

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
    public $method;

    /**
     *
     * @var string
     */
    public $method_en;

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
    public $index;

    /**
     *
     * @var string
     */
    public $isWin;

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
    public $updated;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'mid_alblist';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return MidAlblist[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return MidAlblist
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
