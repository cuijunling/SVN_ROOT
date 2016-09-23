<?php

class Hashmap extends \Phalcon\Mvc\Model
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
    public $type;

    /**
     *
     * @var string
     */
    public $guid;

    /**
     *
     * @var string
     */
    public $MD5;

    /**
     *
     * @var string
     */
    public $fileName;

    /**
     *
     * @var integer
     */
    public $flag;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'hashmap';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Hashmap[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Hashmap
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
