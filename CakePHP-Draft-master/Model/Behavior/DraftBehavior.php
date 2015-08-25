<?php
class DraftBehavior extends ModelBehavior{

    public $options = array(
        'conditions' => array('online' => -1)
    );

    public function setup(Model $model, $config = array()){
        $this->options[$model->alias] = array_merge($this->options, $config);
    }

    public function getDraftId(Model $model, $conditions = array()){
        $conditions = array_merge( $this->options[$model->alias]['conditions'], $conditions);
        $result = $model->find('first', array(
            'fields' => $model->primaryKey,
            'conditions' => $conditions
        ));
        if(!empty($result)){
            return $result[$model->alias][$model->primaryKey];
        }else{
            $model->create($conditions);
            $model->save(null, false);
            return $model->id;
        }
    }

    public function cleanDrafts(Model $model){
        return $model->deleteAll(array('online' => -1));
    }

}
