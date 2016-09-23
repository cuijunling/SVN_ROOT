<?php

use Phalcon\Mvc\Model\Behavior\Timestampable;

class Feedbackanswer extends \Phalcon\Mvc\Model
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
    public $feedbackId;

    /**
     *
     * @var string
     */
    public $questionId;

    /**
     *
     * @var string
     */
    public $content;

    

    public function initialize()
    {
        $this->setConnectionService('mysql_ranking');
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'feedbackanswer';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Feedbackanswer[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Feedbackanswer
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
