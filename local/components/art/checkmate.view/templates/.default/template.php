<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Page;
use Bitrix\Main\Application;

Page\Asset::getInstance()->addCss("/dist/libs/fancybox/css/jquery.fancybox.css");
Page\Asset::getInstance()->addJs("/dist/libs/fancybox/js/jquery.fancybox.js");
Page\Asset::getInstance()->addJs('/dist/libs/slimselect.min.js');
Page\Asset::getInstance()->addCss('/dist/libs/slimselect.min.css');
Page\Asset::getInstance()->addCss('/dist/css/refbook.css');
CJSCore::Init(array('jquery2'));

$worktypes = [];
$entity_data_class = ArtPobedaHelper::getHlClassByName('RefBookMain');//Проверяем ЖК если нет добавляем если есть то берем ИД
$rsData = $entity_data_class::getList(array(
    'order' => array('ID' => 'ASC'),
    'select' => array('*'),
));

while ($res = $rsData->Fetch()) {
    $worktypes[$res['ID']] = $res['UF_NAME'];
}

$request = Application::getInstance()->getContext()->getRequest();
$worktype = $request->getPost('worktype-filter');
?>

<a href="/checkmate/">Вернуться к списку </a>
<? echo $arResult['DEAL']['LIMITED_CARD'] ? '<a style="float: right;" href="' . $arResult['DEAL']['LIMITED_CARD'] . '">Сводная таблица</a>' : '' ?>
<div class="checkmate-header">
    <table>
        <tbody>
        <tr>
            <td>Фильтр по виду работ:</td>
            <td>
                <select multiple class="slim-select worktype-filter" style="width: 300px;">
                    <? foreach ($worktypes as $key => $value): ?>
                        <option value="<?= $key ?>" <? if (in_array($key, $worktype)) echo 'selected'; ?>><?= $value ?></option>
                    <? endforeach; ?>
                </select>
            </td>
        </tr>
        </tbody>
    </table>
    <table>
        <tr>
            <td class="legend work-carried"></td>
            <td> - Выполненные работы</td>
        </tr>
        <tr>
            <td class="legend work-noncarried"></td>
            <td> - Работы не выполнялись</td>
        </tr>
        <tr>
            <td class="legend unfilter"></td>
            <td> - Не удовлетворяют условиям фильтра</td>
        </tr>
    </table>
</div>
<hr/>
<div class="data-class" data-deal="<?= $arResult['DEAL']['ID'] ?>" style="display: none"></div>
<table class="checkmate">
    <? $floorCount = $arResult['ELEMENT']['FLOOR_COUNT'] ?>
    <? for ($floor = $floorCount; $floor >= 1; $floor--): ?>
        <? $flats = $arResult['ELEMENT']['FLATS'][$floor]; ?>
        <tr>
            <td>
                <label><?= $floor ?></label>
            </td>
            <? $underLiner = 0; ?>
            <? $flatCounter = 1; ?>
            <? foreach ($flats as $flat => $rooms): ?>
                <? for ($roomNumber = 1; $roomNumber <= $rooms['ROOMS_COUNT']; $roomNumber++): ?>
                    <? $counter = 1; ?>
                    <? $str = ''; ?>
                    <? foreach ($rooms[$roomNumber]['WORKTYPE'] as $key => $value): ?>
                        <? $str .= $counter . ') ' . $worktypes[$value] . '<br>' ?>
                        <? $counter++; ?>
                    <? endforeach; ?>
                    <td class="flat" data-floor="<?= $floor ?>"
                        data-flatinfo="<?= $flat . '-' . $roomNumber . '-' . $flatCounter ?>"
                        data-rooms_count="<?= $rooms['ROOMS_COUNT'] ?>"
                        data-worktype="<?= implode('-', $rooms[$roomNumber]['WORKTYPE']) ?>" <? echo ($rooms[$roomNumber]['WORKTYPE']) ? 'style = "background-color:#CCFFCC"' : 'style = "background-color:#ffdb8b"' ?>>
                        <? echo('<div class = "flatinfo"><b>' . $flatCounter . '-' . $roomNumber . '</b></div>' . ($str ? '<div style = "padding-left:10px;">' . $str . '</div>' : '<div style = "text-align:center;">-</div>')) ?>
                    </td>
                    <? $underLiner++; ?>
                <? endfor; ?>
                <? $flatCounter++; ?>
            <? endforeach; ?>
        </tr>
        <tr>
            <td colspan="<?= $underLiner ?>" class="delimeter"></td>
        </tr>
    <? endfor; ?>
</table>


