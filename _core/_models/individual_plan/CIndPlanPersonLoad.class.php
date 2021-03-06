<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 05.08.13
 * Time: 20:54
 * To change this template use File | Settings | File Templates.
 */

/**
 * Class CIndPlanPersonLoad
 *
 * @property CPerson person
 */
class CIndPlanPersonLoad extends CActiveModel{
    protected $_table = TABLE_IND_PLAN_LOADS;
    protected $_person = null;
    protected $_works = null;
    protected $_year = null;
    protected $_order = null;
    private $_loadTable = null;
    public $person_id;

    public function relations() {
        return array(
            "person" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_person",
                "storageField" => "person_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getPerson"
            ),
            "works" => array(
                "relationPower" => RELATION_COMPUTED,
                "storageProperty" => "_works",
                "relationFunction" => "getWorks",
            ),
            "year" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_year",
                "storageField" => "year_id",
                "managerClass" => "CTaxonomyManager",
                "managerGetObject" => "getYear"
            ),
            "order" => array(
                "relationPower" => RELATION_HAS_ONE,
                "storageProperty" => "_order",
                "storageField" => "order_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getOrder"
            ),
            "orders" => array(
                "relationPower" => RELATION_MANY_TO_MANY,
                "storageProperty" => "_orders",
                "joinTable" => TABLE_IND_PLAN_LOADS_ORDERS,
                "leftCondition" => "ip_load_id = ". (is_null($this->getId()) ? 0 : $this->getId()),
                "rightKey" => "order_id",
                "managerClass" => "CStaffManager",
                "managerGetObject" => "getOrder"
            )
        );
    }
    
    public function attributeLabels() {
        return array(
                "orders" => "Приказы",
                "_edit_restriction" => "Ограничение редактирования"
        );
    }
    
    protected function modelValidators() {
        return array(
            new CIndPlanPersonLoadModelOptionalValidator()
        );
    }
    
    public function getType() {
        if (is_numeric($this->type)) {
            return CTaxonomyManager::getTerm($this->type);
        } else {
            return $this->type;
        }
    }

    protected function getWorks() {
        if (is_null($this->_works)) {
            $this->_works = new CArrayList();
            if (!is_null($this->getId())) {
                foreach (CActiveRecordProvider::getWithCondition(TABLE_IND_PLAN_WORKS, "load_id=".$this->getId())->getItems() as $ar) {
                    $work = new CIndPlanPersonWork($ar);
                    $this->_works->add($work->getId(), $work);
                }
            }
        }
        return $this->_works;
    }

    /**
     * @param $type
     * @return CArrayList
     */
    public function getWorksByType($type) {
        $result = new CArrayList();
        foreach ($this->works->getItems() as $work) {
            if ($work->work_type == $type) {
                $result->add($work->getId(), $work);
            }
        }
        return $result;
    }

    /**
     * Таблица учебной нагрузки. Отдельным классом проще
     *
     * @return CIndPlanPersonLoadTable
     */
    public function getStudyLoadTable() {
        if (is_null($this->_loadTable)) {
            $this->_loadTable = new CIndPlanPersonLoadTable($this);
        }
        return $this->_loadTable;
    }

    /**
     * Показывать в учебной форме разделение на бюджет и контракт
     *
     * @return bool
     */
    public function isSeparateContract() {
        return $this->separate_contract == "1";
    }
    public function remove() {
        foreach ($this->getWorks()->getItems() as  $ar) {
            $ar->remove();
        }
        parent::remove();
    }
    
    /**
     * Отмечена ли запись как нередактируемая
     *
     * @return bool
     */
    public function isEditRestriction() {
    	return $this->_edit_restriction == 1;
    }
    
    /**
     * Атрибут нередактируемой записи
     *
     * @return string
     */
    public function restrictionAttribute() {
    	$attribute = "";
    	if ($this->isEditRestriction()) {
    		$attribute = "readonly";
    	}
    	return $attribute;
    }
}