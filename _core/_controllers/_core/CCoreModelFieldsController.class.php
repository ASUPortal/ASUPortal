<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 14.07.13
 * Time: 14:48
 * To change this template use File | Settings | File Templates.
 */

class CCoreModelFieldsController extends CBaseController {
    public function __construct() {
        if (!CSession::isAuth()) {
            //$this->redirectNoAccess();
        }

        $this->_smartyEnabled = true;
        $this->setPageTitle("Управление полями моделей данных");

        parent::__construct();
    }
    public function actionAdd() {
        $field = new CCoreModelField();
        $field->model_id = CRequest::getInt("id");
        $this->setData("field", $field);
        $this->renderView("_core/field/add.tpl");
    }
    public function actionEdit() {
        $field = CCoreObjectsManager::getCoreModelField(CRequest::getInt("id"));
        $this->setData("field", $field);
        $this->renderView("_core/field/edit.tpl");
    }
    public function actionSave() {
        $field = new CCoreModelField();
        $field->setAttributes(CRequest::getArray($field::getClassName()));
        $cacheKey = "core_model_field_".$field->getId();
        if ($field->validate()) {
        	CApp::getApp()->cache->set($cacheKey, $field);
            $field->save();
            if ($this->continueEdit()) {
                $this->redirect("fields.php?action=edit&id=".$field->getId());
            } else {
                $this->redirect("models.php?action=edit&id=".$field->model_id);
            }
            return true;
        }
        $this->setData("field", $field);
        $this->renderView("_core/field/edit.tpl");
    }
    public function actionDelete() {
        $field = CCoreObjectsManager::getCoreModelField(CRequest::getInt("id"));
        $model = $field->model_id;
        $field->remove();
        $this->redirect("models.php?action=edit&id=".$model);
    }
    public function actionChangeExport() {
        $field = CCoreObjectsManager::getCoreModelField(CRequest::getInt("id"));
        $field->export_to_search = (1 - $field->export_to_search);
        $field->save();

        echo $field->export_to_search;
    }
    public function actionChangeReaders() {
        $field = CCoreObjectsManager::getCoreModelField(CRequest::getInt("id"));
        $field->is_readers = (1 - $field->is_readers);
        $field->save();

        echo $field->is_readers;
    }
    public function actionChangeAuthors() {
        $field = CCoreObjectsManager::getCoreModelField(CRequest::getInt("id"));
        $field->is_authors = (1 - $field->is_authors);
        $field->save();

        echo $field->is_authors;
    }
}