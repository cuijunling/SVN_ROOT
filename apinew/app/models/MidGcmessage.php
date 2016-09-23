<?php

class MidGcmessage extends \Phalcon\Mvc\Model
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
    public $date;

    /**
     *
     * @var string
     */
    public $picture;

    /**
     *
     * @var string
     */
    public $gc;

    /**
     *
     * @var string
     */
    public $gc_en;

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
        return 'mid_gcmessage';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return MidGcmessage[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return MidGcmessage
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
