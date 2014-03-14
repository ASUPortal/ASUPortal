<?php
/**
 * Created by JetBrains PhpStorm.
 * User: aleksandr
 * Date: 27.02.14
 * Time: 21:12
 * To change this template use File | Settings | File Templates.
 */

class CSearchCatalogTaxonomy extends CComponent implements ISearchCatalogInterface{
    public $taxonomy;

    public function actionTypeAhead($lookup)
    {
        $result = array();
        // таксономия
        // будет с поддержкой кеша
        $cache_id = $this->taxonomy."_".md5($lookup);
        if (is_null(CApp::getApp()->cache->get($cache_id))) {
            $taxonomy = CTaxonomyManager::getTaxonomy($this->taxonomy);
            $query = new CQuery();
            $query->select("distinct(taxonomy.id) as id, taxonomy.name as name, taxonomy.taxonomy_id as tax")
                ->from(TABLE_TAXONOMY_TERMS." as taxonomy")
                ->condition("taxonomy.name like '%".$lookup."%' and taxonomy.taxonomy_id =".$taxonomy->getId())
                ->limit(0, 10);
            foreach ($query->execute()->getItems() as $item) {
                $result[$item["id"]] = $item["name"];
            }
            CApp::getApp()->cache->set($cache_id, $result);
        }
        return CApp::getApp()->cache->get($cache_id);
    }

    public function actionGetItem($id)
    {
        $result = array();
        // теперь будет с поддержкой кэша
        $cache_id = $this->taxonomy."_".$id;
        if (is_null(CApp::getApp()->cache->get($cache_id))) {
            $term = CTaxonomyManager::getTerm($id);
            if (!is_null($term)) {
                $result[$term->getId()] = $term->getValue();
            }
            CApp::getApp()->cache->set($cache_id, $result, 30);
        }
        return CApp::getApp()->cache->get($cache_id);
    }

    public function actionGetViewData()
    {
        $cache_id = $this->taxonomy."_getViewData";
        if (is_null(CApp::getApp()->cache->get($cache_id))) {
            $taxonomy = CTaxonomyManager::getTaxonomy($this->taxonomy);
            $result = $taxonomy->getTermsList();
            CApp::getApp()->cache->set($cache_id, $result);
        }
        return CApp::getApp()->cache->get($cache_id);
    }
}