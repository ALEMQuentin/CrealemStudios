CakePHP 2.X Plugin : Draft
=============

Draft will give you the ability to quickly generate draft for your model.

If you are using CakePHP 3.X : https://github.com/Romano83/CakePHP3-draft

Installation
---------------------

1. Clone or Copy the plugin in your Plugin directory
2. In your bootstrap.php load this plugin using CakePlugin::load (or loadAll())

The plugin is now loaded and you can add the Draft.Draft Behavior to your Model (by default the Plugin uses a "online" field to set the state of a content)

* online = -1, when the content is a draft
* online = 0, when it's offline
* online = 1, when it's online

If you want to use a custom field you can configure it using the behavior

    $actsAs = array('Draft.Draft' => array(
        'conditions' => array('draft' => 1)
    ));

For instance this code will use a draft field (set to 1) to create a Draft.

Methods
---------------------

With this behavior attached the model will have a new method getDraftId($conditions = array()) that returns the ID of the draft

    $this->Post->getDraftId(); // Get the last draft id (create a new one if needed)
    $this->Post->getDraftId(array('user_id' => 3)); // Get a draft Id for a content belonging to user 3

If you want to clean your table from drafts :

    $this->Post->cleanDrafts();
