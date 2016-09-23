<?php

class Attachment extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var string
     */
    public $md5;

    /**
     *
     * @var string
     */
    public $fileBody;

    /**
     *
     * @var string
     */
    public $extName;

    /**
     *
     * @var string
     */
    public $cType;

    /**
     *
     * @var string
     */
    public $guid;

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'attachment';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Attachment[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Attachment
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
