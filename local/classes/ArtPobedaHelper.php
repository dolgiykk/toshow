<?php
use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Iblock\IblockTable;
use Bitrix\Main\Grid\Declension;
use Bitrix\Main\Loader;
use Bitrix\Main\Type\Date;
/**
 * Class Helper
 */
class ArtPobedaHelper
{
    /**
     * @param $name
     * @param null $hlData
     * @return \Bitrix\Main\ORM\Data\DataManager
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public static function getHlClassByName($name, &$hlData = null)
    {
        $hlData = self::getHlDataByName($name);
        return HighloadBlockTable::compileEntity($hlData)->getDataClass();
    }
}