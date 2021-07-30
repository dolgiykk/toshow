<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

use Bitrix\Main\Application;
use Bitrix\Main\Page;

$request = Application::getInstance()->getContext()->getRequest();
$worktype = $request->getPost('worktype');
$roomNumber = $request->getPost('room_number');
$floor = $request->getPost('floor');
$flat = $request->getPost('flat');
$deal = $request->getPost('deal');

$entity = ArtPobedaHelper::getHlClassByName('Checkmate');
$filter = array('UF_DEAL' => $deal);
$rsData = $entity::getList(array(
    'order' => array('ID' => 'ASC'),
    'select' => array('*'),
    'filter' => $filter
));

if($el = $rsData->fetch()) {
    $arrayData = json_decode($el['UF_FLATS'], true);
    $arrayData[$floor][$flat][$roomNumber]['WORKTYPE'] = $worktype;

    $arrayData = json_encode($arrayData, true);
    @$entity::update($el['ID'], array('UF_FLATS' => $arrayData));
    echo 'Success!';
}
else echo 'Fail!';