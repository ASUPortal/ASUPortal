<?php

class CWorkPlanTermSectionsTotal extends CAbstractPrintClassField {
    public function getFieldName()
    {
        return "Общая нагрузка. Всего";
    }

    public function getFieldDescription()
    {
        return "Используется при печати рабочей программы, принимает параметр id с Id рабочей программы";
    }

    public function getParentClassField()
    {

    }

    public function getFieldType()
    {
        return self::FIELD_TEXT;
    }

    public function execute($contextObject)
    {
    	$result = 0;
    	$terms = array();
    	foreach ($contextObject->terms->getItems() as $term) {
    		$terms[] = "sum(if(l.term_id = ".$term->getId().", l.value, 0)) as t_".$term->getId();
    	}
    	$auditorTotal = 0;
    	if (count($terms) > 0) {
    		$query = new CQuery();
    		$query->select(join(", ", $terms))
	    		->from(TABLE_WORK_PLAN_CONTENT_LOADS." as l")
	    		->innerJoin(TABLE_TAXONOMY_TERMS." as term", "term.id = l.load_type_id")
	    		->innerJoin(TABLE_WORK_PLAN_CONTENT_SECTIONS." as section", "l.section_id = section.id")
	    		->innerJoin(TABLE_WORK_PLAN_CONTENT_CATEGORIES." as category", "section.category_id = category.id")
	    		->condition("category.plan_id = ".$contextObject->getId()." and l._deleted = 0 and category._deleted = 0")
	    		->group("l.load_type_id")
	    		->order("term.name");
    		$objects = $query->execute();

    		foreach ($objects->getItems() as $key=>$value) {
    			$arr = array_values($value);
    			$dataRow = array();
    			for ($i = 0; $i <= count($value)-1; $i++) {
    				$dataRow[$i] = $arr[$i];
    			}
    			foreach($dataRow as $i) {
    				$auditorTotal += $i;
    			}
    		}
    	}
    	$selfWorkValueOfLoad = 0;
    	foreach ($contextObject->corriculumDiscipline->labors->getItems() as $labor) {
    		if ($labor->type->getAlias() == CWorkPlanLoadTypeConstants::CURRICULUM_LABOR_SELF_WORK) {
    			$selfWorkValueOfLoad = $labor->value;
    		}
    	}
    	
    	$result = $auditorTotal+$selfWorkValueOfLoad;
    	return $result;
    }
}