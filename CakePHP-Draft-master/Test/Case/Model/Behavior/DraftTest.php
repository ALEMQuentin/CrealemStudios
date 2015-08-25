<?php
class Post extends CakeTestModel{
    public $actsAs = array('Draft.Draft');
}
class Post2 extends CakeTestModel{
    public $useTable = 'posts';
    public $actsAs = array('Draft.Draft' => array('conditions' => array('draft' => 1)));
}

class DraftTestCase extends CakeTestCase{

    public $fixtures = array('plugin.draft.post');

    public function setUp(){
        parent::setUp();
        $this->Post = new Post();
        $this->Post2 = new Post2();
    }

    public function testGetDraftIdWithoutDraft(){
        $id = $this->Post->getDraftId();
        $this->assertEqual(2, $id);
    }

    public function testGetDraftIdWithDraft(){
        $this->Post->save(array('online' => -1));
        $id = $this->Post->id;
        $draft_id = $this->Post->getDraftId();
        $this->assertEqual($id, $draft_id);
    }

    public function testGetDraftIdWithConditions(){
        $draft_id = $this->Post->getDraftId(array('user_id' => 2));
        $post = $this->Post->findById($draft_id);
        $this->assertEqual(-1, $post['Post']['online']);
        $this->assertEqual(2, $post['Post']['user_id']);
    }

    public function testGetDraftIdWithOptions(){
        $draft_id = $this->Post2->getDraftId();
        $this->Post2->id = $draft_id;
        $this->assertEqual(1, $this->Post2->field('draft'));
        $this->assertEqual(0, $this->Post2->field('online'));
    }

    public function testCleanDrafts(){
        $this->Post->getDraftId();
        $this->assertEqual(2, $this->Post->find('count'));
        $this->Post->cleanDrafts();
        $this->assertEqual(1, $this->Post->find('count'));
    }

}
