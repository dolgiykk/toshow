<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Engine\Contract;
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Application;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

class CheckmateAdd extends CBitrixComponent implements Contract\Controllerable
{
    const UF_WORK_TYPES = 'UF_CRM_1574257195'; //Поле тип работ
    const CHECKMATE = 'Checkmate'; //HL блок шахматка
    const MAX_ROOMS = 8; //Максимальное количество комнат в квартире

    private function prepComponent()
    {
        Loader::includeModule('crm');
    }

    /**
     * @param $type
     * @return array
     * Возвращает список объектов по указанному направлению
     */
    private function getDealsByType($type)
    {
        $arReturn = [];
        $workTypes = [];
        $rsEnum = CUserFieldEnum::GetList([], ['USER_FIELD_NAME' => self::UF_WORK_TYPES]);//подразделения
        while ($arItem = $rsEnum->Fetch()) {
            $workTypes[$arItem['VALUE']] = $arItem['ID'];
        }

        if (!$workTypes[$type])
            return null;

        $arFilter = [
            "CHECK_PERMISSIONS" => 'N',
            self::UF_WORK_TYPES => $workTypes[$type]
        ];

        $rsDeal = CCrmDeal::GetList(
            array('ID' => 'ASC'),
            $arFilter,
            array('ID', 'TITLE'),
            false
        );

        while ($res = $rsDeal->Fetch()) {
            $arReturn[] = $res;
        }
        return $arReturn;
    }

    private function getCheckmateDeals()
    {
        $arReturn = [];

        $entity = ArtPobedaHelper::getHlClassByName(self::CHECKMATE);
        $rsData = $entity::getList(array(
            'order' => array(),
            'select' => array('*'),
            'filter' => array()
        ));

        while ($el = $rsData->Fetch()) {
            $arReturn[] = $el['UF_DEAL'];
        }
        return $arReturn;
    }


    /**
     * @return array
     * Добавлеяет новую шахматку в справочник
     */
    private function save()
    {
        $houseInfo = [];
        $request = Application::getInstance()->getContext()->getRequest();

        $floorCount = $request->getPost('floor-count');

        $roomNumber = 1;
        for ($floor = 1; $floor <= $floorCount; $floor++) {
            for ($roomCount = 1; $roomCount <= self::MAX_ROOMS; $roomCount++) {
                if ($room = $request->getPost($roomCount . '-room')) {
                    for ($flat = 1; $flat <= $room; $flat++) {
                        $houseInfo[$floor][$roomNumber]['ROOMS_COUNT'] = $roomCount;
                        for ($i = 1; $i <= $roomCount; $i++) {
                            $houseInfo[$floor][$roomNumber][$i]['WORKTYPE'] = "";
                        }
                        $roomNumber++;
                    }
                }
            }
        }

        $deal = $request->getPost('deal');

        $entity = ArtPobedaHelper::getHlClassByName(self::CHECKMATE);
        $arCheckmate = [
            'UF_FLATS' => json_encode($houseInfo, true),
            'UF_FLOOR_COUNT' => $floorCount,
            'UF_DEAL' => $deal
        ];
        $entity::Add($arCheckmate)->getId();

    }

    public function executeComponent()
    {
        $this->prepComponent();
        $request = Application::getInstance()->getContext()->getRequest();
        if ($request->isPost() && trim($request->getPost("save"))) {
            $deal = $request->getPost('deal');
            $this->save();
            LocalRedirect('/checkmate/view-checkmate/?DEAL_ID=' . $deal);
            die();
        }
        $this->arResult['DEALS'] = $this->getDealsByType('Кондиционирование');
        $this->arResult['USED_DEALS'] = $this->getCheckmateDeals();
        $this->includeComponentTemplate();
    }

    public function configureActions(): array
    {
        $this->prepComponent();
        // TODO: Implement configureActions() method.
        return [];
    }
}

