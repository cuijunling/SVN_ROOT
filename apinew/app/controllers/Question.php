<?php
use Phalcon\Mvc\Model\Behavior\Timestampable;

class Question extends \Phalcon\Mvc\Model
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
    public $questionId;

    /**
     *
     * @var string
     */
    public $question;

    /**
     *
     * @var string
     */
    public $question_en;

    /**
     *
     * @var string
     */
    public $answer;


    /**
     *
     * @var string
     */
    public $answer_en;

    /**
     *
     * @var string
     */
    public $type;

    /**
     *
     * @var string
     */
    public $flag;

    /**
     *
     * @var string
     */
    public $updatetime;


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
            
//        $this->setConnectionService('mysql_ranking');
    }
   
    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'question';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return MidProject[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return MidProject
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
