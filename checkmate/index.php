<?php
include($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Список шахматок");
use Bitrix\Main\Page;
Page\Asset::getInstance()->addCss('/dist/css/refbook.css');

$entity_data_class = ArtPobedaHelper::getHlClassByName('Checkmate');//шахматка
$checkmates = [];

$rsData = $entity_data_class::getList(array(
    'order' => array('ID' => 'ASC'),
    'select' => array('*'),
    'filter' => array(),
));

while ($el = $rsData->Fetch()){
    $arFilter = [
        'CHECK_PERMISSIONS' => 'N',
        'ID' => $el['UF_DEAL']
    ];

    $rsDeal = CCrmDeal::GetList(
        array('ID' => 'ASC'),
        $arFilter,
        array('ID', 'TITLE'),
        false
    );

    if($res = $rsDeal->Fetch()) {
        $checkmates[$res['ID']] = $res['TITLE'];
    }
}
?>
<style>
    .new-checkmate{
        color:white;
    }

    .create{
        margin-bottom:20px;
    }
</style>
<div class="main-block-doc">
    <div style="float: right;" class="ui-btn-split ui-btn-primary create">
        <span class="ui-btn-main">
            <a href = "/checkmate/add-checkmate/" class="new-checkmate">Создать новую</a>
        </span>
    </div>
    <table class="card-add" id="worktable">
    <? foreach($checkmates as $key => $value): ?>
        <tr>
            <td>
                <a href = "/checkmate/view-checkmate/?DEAL_ID=<?=$key?>"><?= $value?></a>
            </td>
        </tr>
    <? endforeach; ?>
    </table>

<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");