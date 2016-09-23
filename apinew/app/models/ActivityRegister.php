<?php

use Phalcon\Mvc\Model\Validator\Email as Email;
use Phalcon\Mvc\Model\Behavior\Timestampable;

class Activityregister extends \Phalcon\Mvc\Model
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
     * @var integer
     */
    public $activityId;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $mobilephone;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var string
     */
    public $title;

    /**
     *
     * @var string
     */
    public $company;

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
     * Validations and business logic
     *
     * @return boolean
     */


    public function initialize()
    {
            date_default_timezone_set('Asia/Shanghai');
            $this->addBehavior(
            new Timestampable(
                array(
                    'beforeSave' => array(
                    'field'    => 'updateTime',
                    'format' => 'Y-m-d H:i:s'
                        )
                    
                    )
                )
            );
            $this->setConnectionService('mysql_ranking');
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'activityregister';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Activityregister[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Activityregister
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
