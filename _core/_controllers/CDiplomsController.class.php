<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 24.02.13
 * Time: 17:19
 * To change this template use File | Settings | File Templates.
 */
class CDiplomsController extends CBaseController {
    public function __construct() {
        if (!CSession::isAuth()) {
            if (!in_array(CRequest::getString("action"), $this->allowedAnonymous)) {
                $this->redirectNoAccess();
            }
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Дипломные темы студентов");

        parent::__construct();
    }
    public function actionIndex() {
        $set = new CRecordSet();
        $query = new CQuery();
        $query->select("diplom.*")
        ->from(TABLE_DIPLOMS." as diplom")
        ->order("diplom.dipl_name asc");
        $set->setQuery($query);
        
        if (CRequest::getString("order") == "st_group.name") {
        	$direction = "asc";
        	if (CRequest::getString("direction") != "") {
        		$direction = CRequest::getString("direction");}
        		$query->innerJoin(TABLE_STUDENTS." as student", "diplom.student_id=student.id");
        		$query->innerJoin(TABLE_STUDENT_GROUPS." as st_group", "student.group_id = st_group.id");
        		$query->order("st_group.name ".$direction);
        }
        elseif (CRequest::getString("order") == "dipl_prew.date_preview") {
        	$direction = "asc";
        	if (CRequest::getString("direction") != "") {
        		$direction = CRequest::getString("direction");}
        		$query->innerJoin(TABLE_STUDENTS." as student", "diplom.student_id=student.id");
        		$query->innerJoin(TABLE_DIPLOM_PREVIEWS." as dipl_prew", "student.id = dipl_prew.student_id");
        		$query->order("dipl_prew.date_preview ".$direction);
        }
        elseif (CRequest::getString("order") == "prepod.fio") {
        	$direction = "asc";
        	if (CRequest::getString("direction") != "") {
        		$direction = CRequest::getString("direction");}
        		$query->innerJoin(TABLE_STUDENTS." as student", "diplom.student_id=student.id");
        		$query->innerJoin(TABLE_PERSON." as prepod", "diplom.kadri_id = prepod.id");
        		$query->order("prepod.fio ".$direction);
        }        
        $diploms = new CArrayList();
        foreach ($set->getPaginated()->getItems() as $item) {
            $diplom = new CDiplom($item);
            $diploms->add($diplom->getId(), $diplom);
        }  
        /**
         * Просмотр тем выбранных преподавателей
         */
		$this->addJQInlineInclude('
        $("#kadri_id").change(function(){
        if ($("#kadri_id").val() != 0) {
			window.location.href = "?action=index&filter=kadri_id:" + $("#kadri_id").val();
			}
                });
            ');
		
		// запросы для фильтров
		$queryGroups = new CQuery();
		$queryGroups->select("diplom.*")
		->from(TABLE_DIPLOMS." as diplom")
		->order("diplom.kadri_id asc");
		// фильтры
		$selectedPerson = null;
		// фильтр по руководителю
		if (!is_null(CRequest::getFilter("kadri_id"))) {
			$query->innerJoin(TABLE_PERSON." as prepod", "diplom.kadri_id = prepod.id".CRequest::getFilter("kadri_id"));
			$selectedPerson = CRequest::getFilter("kadri_id");
		}
		// параметры фильтров
		$groups = array();
		foreach ($queryGroups->execute()->getItems() as $item) {
			$groups[$item["id"]] = $item["kadri_id"];
		}
		
        $this->addJSInclude(JQUERY_UI_JS_PATH);
        $this->addCSSInclude(JQUERY_UI_CSS_PATH);      
        $this->setData("diploms", $diploms);
        $this->setData("paginator", $set->getPaginator());
        $this->renderView("_diploms/index.tpl");
    }
    public function actionAdd() {
        $diplom = new CDiplom();
        $this->setData("diplom", $diplom);
        $this->addActionsMenuItem(array(
            array(
                "title" => "Назад",
                "link" => WEB_ROOT."_modules/_diploms/",
                "icon" => "actions/edit-undo.png"
            )
        ));		
        $this->renderView("_diploms/add.tpl");
    }
    public function actionEdit() {
        $diplom = CStaffManager::getDiplom(CRequest::getInt("id"));
        // сконвертим дату из MySQL date в нормальную дату
        $diplom->date_act = date("d.m.Y", strtotime($diplom->date_act));
        $this->setData("diplom", $diplom);
        $this->addActionsMenuItem(array(
            array(
                "title" => "Назад",
                "link" => WEB_ROOT."_modules/_diploms/",
                "icon" => "actions/edit-undo.png"
            ),
            array(
                "title" => "Печать по шаблону",
                "link" => "#",
                "icon" => "devices/printer.png",
                "template" => "formset_diploms"
            )
        ));	
        $this->renderView("_diploms/edit.tpl");
    }
    public function actionSave() {
        $diplom = new CDiplom();
        $diplom->setAttributes(CRequest::getArray($diplom::getClassName()));
        $oldDate = $diplom->date_act;
        if ($diplom->validate()) {
            // дату нужно сконвертить в MySQL date
            $diplom->date_act = date("Y-m-d", strtotime($diplom->date_act));
            $diplom->save();
            if ($this->continueEdit()) {
                $this->redirect("?action=edit&id=".$diplom->getId());
            } else {
                $this->redirect(WEB_ROOT."_modules/_diploms/");
            }
            //$this->redirect("?action=index");
            return true;
        }
        // сконвертим дату из MySQL date в нормальную дату
        $diplom->date_act = date("d.m.Y", strtotime($diplom->date_act));
        $commissions = array();
        foreach (CSABManager::getCommissionsList() as $id=>$c) {
            $commission = CSABManager::getCommission($id);
            $nv = $commission->title;
            if (!is_null($commission->manager)) {
                $nv .= " ".$commission->manager->getName();
            }
            if (!is_null($commission->secretar)) {
                $nv .= " (".$commission->secretar->getName().")";
            }
            $cnt = 0;
            foreach ($commission->diploms->getItems() as $d) {
                if (strtotime($diplom->date_act) == strtotime($d->date_act)) {
                    $cnt++;
                }
            }
            $nv .= " ".$cnt;
            $commissions[$commission->getId()] = $nv;
        }
        if (!array_key_exists($diplom->gak_num, $commissions)) {
            $diplom->gak_num = null;
        }
        $reviewers = CStaffManager::getPersonsListWithType(TYPE_REVIEWER);
        if (!array_key_exists($diplom->recenz_id, $reviewers)) {
            $reviewer = CStaffManager::getPerson($diplom->recenz_id);
            if (!is_null($reviewer)) {
                $reviewers[$reviewer->getId()] = $reviewer->getName();
            }
        }
        $this->setData("reviewers", $reviewers);
        $this->setData("commissions", $commissions);
        $this->setData("diplom", $diplom);
        $this->renderView("_diploms/edit.tpl");
    }
    public function actionGetAverageMark() {
    	$mark = 0;
    	$diplom = CStaffManager::getDiplom(CRequest::getInt("id"));
    	if (!is_null($diplom)) {
            $precise = 2;
            if (CRequest::getInt("p") != 0) {
                $precise = CRequest::getInt("p");
            }
    		$mark = $diplom->getAverageMarkComputed($precise);
    	}
    	if ($mark !== 0) {
    		echo $mark;
    	}
    }
 public function actionSearch() {
    	$res = array();
    	$term = CRequest::getString("query");
    	/**
    	 * Поиск по теме диплома
    	*/
    	$query = new CQuery();
    	$query->select("distinct(diplom.id) as id, diplom.dipl_name as title")
    	->from(TABLE_DIPLOMS." as diplom")
    	->condition("diplom.dipl_name like '%".$term."%'")
    	->limit(0, 5);
    			foreach ($query->execute()->getItems() as $item) {
    				$res[] = array(
    						"field" => "id",
    						"value" => $item["id"],
    						"label" => $item["title"],
    						"class" => "CDiplom"
    				);
    			}
    	/**
    	* Поиск по ФИО студента
    	*/
    	$query = new CQuery();
    	$query->select("distinct(student.id) as id, student.fio as name")
    	->from(TABLE_STUDENTS." as student")
    	->condition("student.fio like '%".$term."%'")
    	->limit(0, 5);
    			foreach ($query->execute()->getItems() as $item) {
    				$res[] = array(
    						"field" => "id",
    						"value" => $item["id"],
    						"label" => $item["name"],
    						"class" => "CStudent"
    				);
    			}
    	/**
    	 * Поиск по степени утверждения диплома
    	*/
    	/*$query = new CQuery();
    	$query->select("distinct(diplom_confirms.id) as id, diplom_confirms.name as title")
    	->from(TABLE_DIPLOM_CONFIRMATIONS." as diplomConf")
    	->condition("diplomConf.name like '%".$term."%'")
    	->limit(0, 5);
    			foreach ($query->execute()->getItems() as $item) {
    				$res[] = array(
    						"field" => "id",
    						"value" => $item["id"],
    						"label" => $item["title"],
    						"class" => "CDiplom"
    				);
    			}*/
    	/**
    	* Поиск по месту практики
    	*/
    	$query = new CQuery();
    	$query->select("distinct(diplom.id) as id, diplom.pract_place as title")
    	->from(TABLE_DIPLOMS." as diplom")
    	->condition("diplom.pract_place like '%".$term."%'")
    	->limit(0, 5);
    			foreach ($query->execute()->getItems() as $item) {
    				$res[] = array(
    						"field" => "id",
    						"value" => $item["id"],
    						"label" => $item["title"],
    						"class" => "CDiplom"
    				);
    			}
    	/**
    	* Поиск по преподавателю
    	*/
		/*$query = new CQuery();
    	$query->select("distinct(diplom.kadri_id) as id, prepod.fio as title");
    	$query->innerJoin(TABLE_STUDENTS." as student", "diplom.student_id=student.id");
    	$query->innerJoin(TABLE_PERSON." as prepod", "diplom.kadri_id = prepod.id")
    	->from(TABLE_DIPLOMS." as diplom")
    	->condition("prepod.fio like '%".$term."%'")
    	->limit(0, 5);
    			foreach ($query->execute()->getItems() as $item) {
    				$res[] = array(
    						"field" => "id",
    						"value" => $item["id"],
    						"label" => $item["title"],
    						"class" => "CDiplom"
    				);
    			}
    			
    	/**
    	* Поиск по группе
    	*/
    	/*$query = new CQuery();
    	$query->select("distinct(study_groups.id) as id, study_groups.name as title")
    	->from(TABLE_STUDENT_GROUPS." as group")
    	->condition("group.name like '%".$term."%'")
    	->limit(0, 5);
    			foreach ($query->execute()->getItems() as $item) {
    				$res[] = array(
    						"field" => "id",
    						"value" => $item["id"],
    						"label" => $item["title"],
    						"class" => "CStudentGroup"
    				);
    			}*/
    			

    			
    	echo json_encode($res);
    }  
}

