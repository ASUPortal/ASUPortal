<?php
class CWorkPlanTermsController extends CBaseController{
    protected $_isComponent = true;

    public function __construct() {
        if (!CSession::isAuth()) {
            $action = CRequest::getString("action");
            if ($action == "") {
                $action = "index";
            }
            if (!in_array($action, $this->allowedAnonymous)) {
                $this->redirectNoAccess();
            }
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Семестры");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $set->setQuery($query);
        $query->select("t.*")
            ->from(TABLE_WORK_PLAN_TERMS." as t")
            ->order("t.ordering asc")
            ->condition("plan_id=".CRequest::getInt("plan_id")." and _deleted=0");
        $objects = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $ar) {
            $object = new CWorkPlanTerm($ar);
            $objects->add($object->getId(), $object);
        }
        $this->setData("objects", $objects);
        $this->setData("paginator", $set->getPaginator());
        
        $plan = CWorkPlanManager::getWorkplan(CRequest::getInt("plan_id"));
        $discipline = $plan->corriculumDiscipline;
        
        // семестры дисциплины учебного плана
        $sectionsDisciplines = array();
        foreach ($discipline->sections->getItems() as $section) {
        	$sectionsDisciplines[] = $section->id;
        }
        $this->setData("sectionsDisciplines", $sectionsDisciplines);
        
        // семестры нагрузок рабочей программы
        $loadTerms = array();
        foreach ($plan->categories->getItems() as $category) {
        	foreach ($category->sections->getItems() as $section) {
        		foreach ($section->loadsDisplay->getItems() as $load) {
        			$loadTerms[] = $load->term->getId();
        		}
        	}
        }
        $this->setData("loadTerms", $loadTerms);
        
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(array(
            "title" => "Обновить",
            "link" => "workplanterms.php?action=index&plan_id=".CRequest::getInt("plan_id"),
            "icon" => "actions/view-refresh.png"
        ), array(
            "title" => "Загрузить из дисциплины",
            "link" => "workplanterms.php?action=update&plan_id=".CRequest::getInt("plan_id"),
            "icon" => "actions/format-indent-more.png"
        ), array(
            "title" => "Добавить семестр",
            "link" => "workplanterms.php?action=add&id=".CRequest::getInt("plan_id"),
            "icon" => "actions/list-add.png"
        )));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/terms/index.tpl");
    }
    public function actionAdd() {
        $object = new CWorkPlanTerm();
        $object->plan_id = CRequest::getInt("id");
        $plan = CWorkPlanManager::getWorkplan(CRequest::getInt("id"));
        $object->ordering = $plan->terms->getCount() + 1;
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(
            "title" => "Назад",
            "link" => "workplanterms.php?action=index&plan_id=".$object->plan_id,
            "icon" => "actions/edit-undo.png"
        ));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/terms/add.tpl");
    }
    public function actionEdit() {
        $object = CBaseManager::getWorkPlanTerm(CRequest::getInt("id"));
        $this->setData("object", $object);
        /**
         * Генерация меню
         */
        $this->addActionsMenuItem(array(array(
            "title" => "Назад",
            "link" => "workplanterms.php?action=index&plan_id=".$object->plan_id,
            "icon" => "actions/edit-undo.png"
        )));
        /**
         * Отображение представления
         */
        $this->renderView("_corriculum/_workplan/terms/edit.tpl");
    }
    public function actionDelete() {
        $object = CBaseManager::getWorkPlanTerm(CRequest::getInt("id"));
        $plan = $object->plan;
        $object->markDeleted(true);
        $object->save();
        $order = 1;
        foreach ($plan->terms as $term) {
        	$term->ordering = $order++;
        	$term->save();
        }
        $this->redirect("workplanterms.php?action=index&plan_id=".$plan->getId());
    }
    public function actionSave() {
        $object = new CWorkPlanTerm();
        $object->setAttributes(CRequest::getArray($object::getClassName()));
        if ($object->validate()) {
            $object->save();
            if ($this->continueEdit()) {
                $this->redirect("workplanterms.php?action=edit&id=".$object->getId());
            } else {
                $this->redirect("workplanterms.php?action=index&plan_id=".$object->plan_id);
            }
            return true;
        }
        $this->setData("object", $object);
        $this->renderView("_corriculum/_workplan/terms/edit.tpl");
    }
    public function actionUpdate() {
    	$plan = CWorkPlanManager::getWorkplan(CRequest::getInt("plan_id"));
    	if (!is_null($plan->corriculumDiscipline)) {
    		foreach (CActiveRecordProvider::getWithCondition(TABLE_CORRICULUM_DISCIPLINE_SECTIONS, "discipline_id=".$plan->corriculumDiscipline->getId())->getItems() as $ar) {
    			$term = new CActiveModel($ar);
    			$object = new CWorkPlanTerm();
    			$object->plan_id = $plan->getId();
    			$object->number = $term->getId();
    			$object->save();
    		}
    	}
    	$this->redirect("workplanterms.php?action=index&plan_id=".$plan->getId());
    }
}