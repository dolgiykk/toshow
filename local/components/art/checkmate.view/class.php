<?php

use \Bitrix\Main\Loader;
use \Bitrix\Main\Engine\Contract;
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Application;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

class CheckmateView extends CBitrixComponent implements Contract\Controllerable
{
    const CHECKMATE = 'Checkmate'; //HL блок шахматка

    private function prepComponent()
    {
        Loader::includeModule('crm');
    }

    //Получаем элемент HL-блока и инфу о сделке
    private function getHlBlockElement($id)
    {
        $entity = ArtPobedaHelper::getHlClassByName(self::CHECKMATE);
        $rsData = $entity::getList(array(
            'order' => array(),
            'select' => array('*'),
            'filter' => array('UF_DEAL' => $id)
        ));
        if ($rsElem = $rsData->fetch()) {
            $this->arResult['ELEMENT']['ID'] = $rsElem['ID'];
            $this->arResult['DEAL']['ID'] = $rsElem['UF_DEAL'];
            $this->arResult['ELEMENT']['FLOOR_COUNT'] = $rsElem['UF_FLOOR_COUNT'];
            $this->arResult['ELEMENT']['FLATS'] = json_decode($rsElem['UF_FLATS'], true);

            //Получаем необходимую инфу о сделке
            $rsDeal = CCrmDeal::GetList(
                array('ID' => 'ASC'),
                array(
                    'ID' => array($rsElem['UF_DEAL']),
                    "CHECK_PERMISSIONS" => 'N'
                ),
                array("ID", "TITLE", "UF_CRM_LIMITEDCART"),
                false
            );
            if ($arDeal = $rsDeal->GetNext()) {
                $this->arResult['DEAL']['TITLE'] = $arDeal['TITLE'];
                $this->arResult['DEAL']['LIMITED_CARD'] = $arDeal['UF_CRM_LIMITEDCART'];
            }
        }
    }

    public function executeComponent()
    {
        $this->prepComponent();
        $this->getHlBlockElement($this->arParams['ID']);
        $this->includeComponentTemplate();
    }

    public function configureActions(): array
    {
        $this->prepComponent();
        // TODO: Implement configureActions() method.
        return [];
    }
}