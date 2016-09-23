<?php
 
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;


class MidCategoryhomeController extends ControllerBase
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for mid_categoryHome
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'MidCategoryhome', $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "id";

        $mid_categoryHome = MidCategoryhome::find($parameters);
        if (count($mid_categoryHome) == 0) {
            $this->flash->notice("The search did not find any mid_categoryHome");

            $this->dispatcher->forward(array(
                "controller" => "mid_categoryHome",
                "action" => "index"
            ));

            return;
        }

        $paginator = new Paginator(array(
            'data' => $mid_categoryHome,
            'limit'=> 10,
            'page' => $numberPage
        ));

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Displays the creation form
     */
    public function newAction()
    {

    }

    /**
     * Edits a mid_categoryHome
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {

            $mid_categoryHome = MidCategoryhome::findFirstByid($id);
            if (!$mid_categoryHome) {
                $this->flash->error("mid_categoryHome was not found");

                $this->dispatcher->forward(array(
                    'controller' => "mid_categoryHome",
                    'action' => 'index'
                ));

                return;
            }

            $this->view->id = $mid_categoryHome->id;

            $this->tag->setDefault("id", $mid_categoryHome->id);
            $this->tag->setDefault("guid", $mid_categoryHome->guid);
            $this->tag->setDefault("basicInfo", $mid_categoryHome->basicInfo);
            $this->tag->setDefault("basicInfo_en", $mid_categoryHome->basicInfo_en);
            $this->tag->setDefault("albAwards", $mid_categoryHome->albAwards);
            $this->tag->setDefault("flag", $mid_categoryHome->flag);
            $this->tag->setDefault("sign", $mid_categoryHome->sign);
            $this->tag->setDefault("sign_en", $mid_categoryHome->sign_en);
            $this->tag->setDefault("updatetime", $mid_categoryHome->updatetime);
            $this->tag->setDefault("updated", $mid_categoryHome->updated);
            
        }
    }

    /**
     * Creates a new mid_categoryHome
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward(array(
                'controller' => "mid_categoryHome",
                'action' => 'index'
            ));

            return;
        }

        $mid_categoryHome = new MidCategoryhome();
        $mid_categoryHome->guid = $this->request->getPost("guid");
        $mid_categoryHome->basicInfo = $this->request->getPost("basicInfo");
        $mid_categoryHome->basicInfo_en = $this->request->getPost("basicInfo_en");
        $mid_categoryHome->albAwards = $this->request->getPost("albAwards");
        $mid_categoryHome->flag = $this->request->getPost("flag");
        $mid_categoryHome->sign = $this->request->getPost("sign");
        $mid_categoryHome->sign_en = $this->request->getPost("sign_en");
        $mid_categoryHome->updatetime = $this->request->getPost("updatetime");
        $mid_categoryHome->updated = $this->request->getPost("updated");
        

        if (!$mid_categoryHome->save()) {
            foreach ($mid_categoryHome->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward(array(
                'controller' => "mid_categoryHome",
                'action' => 'new'
            ));

            return;
        }

        $this->flash->success("mid_categoryHome was created successfully");

        $this->dispatcher->forward(array(
            'controller' => "mid_categoryHome",
            'action' => 'index'
        ));
    }

    /**
     * Saves a mid_categoryHome edited
     *
     */
    public function saveAction()
    {

        if (!$this->request->isPost()) {
            $this->dispatcher->forward(array(
                'controller' => "mid_categoryHome",
                'action' => 'index'
            ));

            return;
        }

        $id = $this->request->getPost("id");
        $mid_categoryHome = MidCategoryhome::findFirstByid($id);

        if (!$mid_categoryHome) {
            $this->flash->error("mid_categoryHome does not exist " . $id);

            $this->dispatcher->forward(array(
                'controller' => "mid_categoryHome",
                'action' => 'index'
            ));

            return;
        }

        $mid_categoryHome->guid = $this->request->getPost("guid");
        $mid_categoryHome->basicInfo = $this->request->getPost("basicInfo");
        $mid_categoryHome->basicInfo_en = $this->request->getPost("basicInfo_en");
        $mid_categoryHome->albAwards = $this->request->getPost("albAwards");
        $mid_categoryHome->flag = $this->request->getPost("flag");
        $mid_categoryHome->sign = $this->request->getPost("sign");
        $mid_categoryHome->sign_en = $this->request->getPost("sign_en");
        $mid_categoryHome->updatetime = $this->request->getPost("updatetime");
        $mid_categoryHome->updated = $this->request->getPost("updated");
        

        if (!$mid_categoryHome->save()) {

            foreach ($mid_categoryHome->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward(array(
                'controller' => "mid_categoryHome",
                'action' => 'edit',
                'params' => array($mid_categoryHome->id)
            ));

            return;
        }

        $this->flash->success("mid_categoryHome was updated successfully");

        $this->dispatcher->forward(array(
            'controller' => "mid_categoryHome",
            'action' => 'index'
        ));
    }

    /**
     * Deletes a mid_categoryHome
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $mid_categoryHome = MidCategoryhome::findFirstByid($id);
        if (!$mid_categoryHome) {
            $this->flash->error("mid_categoryHome was not found");

            $this->dispatcher->forward(array(
                'controller' => "mid_categoryHome",
                'action' => 'index'
            ));

            return;
        }

        if (!$mid_categoryHome->delete()) {

            foreach ($mid_categoryHome->getMessages() as $message) {
                $this->flash->error($message);
            }

            $this->dispatcher->forward(array(
                'controller' => "mid_categoryHome",
                'action' => 'search'
            ));

            return;
        }

        $this->flash->success("mid_categoryHome was deleted successfully");

        $this->dispatcher->forward(array(
            'controller' => "mid_categoryHome",
            'action' => "index"
        ));
    }

}
