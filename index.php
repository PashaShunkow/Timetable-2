<?php


//$db = new System\DbAdapter();
//$collection = new BO\Entities\Teacher\Collection($db);
//$teacher = new BO\Entities\Teacher\Model($db, $collection);
//
////$collection1 = $teacher->getCollection()->load();
////
////$items = $collection1->getItems();
//
////$teacher->load('54161ecef80410f010000029');
////$teacher->setFirstname('ivanttttt')->save();
////$teacher->delete();
//
//$teacher->setData(array(
//    'firstname' => 'oooooooo',
//    'lastname'  => 'Ivanov',
//    'age'       => '34'
//));
//
//$teacher->save();

require_once('app' . DIRECTORY_SEPARATOR . 'App.php');
App::run();


?>