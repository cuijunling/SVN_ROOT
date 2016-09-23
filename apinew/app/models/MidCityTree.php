<?php
use Phalcon\Mvc\Model\Behavior\Timestampable;

class MidCityTree extends \Phalcon\Mvc\Model
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
    public $name;

    /**
     *
     * @var string
     */
    public $enname;

    /**
     *
     * @var string
     */
    public $fid;

     /**
     *
     * @var string
     */
    public $flag;


   
    /**
     *
     * @var string
     */
    public $sort;
  
    /**
     *
     * @var string
     */
    public $ensort;


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
            
       
    }



    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'cityTree';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return MidActivity[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return MidActivity
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
