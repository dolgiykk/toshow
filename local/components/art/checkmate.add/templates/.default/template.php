<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Page;

Page\Asset::getInstance()->addJs('/dist/libs/slimselect.min.js');
Page\Asset::getInstance()->addCss('/dist/libs/slimselect.min.css');

CJSCore::Init(array('jquery2'));
?>
<a href="/checkmate/" class="href-back">Вернуться к списку </a>
<form action="" method="POST" class="checkmate-add">
    <table class="card-add">
        <tr>
            <td>Выберите объект:</td>
            <td>
                <select class="slim-select deal" id="deal" name="deal">
                    <option value="0" selected>Не установлено</option>
                    <?
                    foreach ($arResult['DEALS'] as $deal): ?>
                        <option value="<?= $deal['ID'] ?>" <? if (in_array($deal['ID'], $arResult['USED_DEALS'])) echo 'disabled' ?>> <?= $deal['TITLE'] ?> </option>
                    <? endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>* Количество этажей:</td>
            <td><input type="number" min="0" class="floor-count" name="floor-count"></td>
        </tr>
        <tr>
            <td>** Количество n-комнатных квартир на этаже:</td>
            <td>
                <div class="room-count__error">
                    <? for ($i = 1; $i < 8; $i++): ?>
                        <span class="nroom">
                            <label><?= $i . 'К ' ?></label>
                            <input type='number' min="0" class="<?= 'n-room' ?>" name="<?= $i . '-room' ?>" placeholder="0">
                        </span><br/>
                    <? endfor; ?>
                </div>
            </td>
        </tr>
    </table>
    <div class="note">* - Максимальное количество этажей - 50 <br>** - Максимальное количество квартир на этаж - 25
    </div>
    <div class="button-footer">
        <div class="block-error"></div>
        <div class="block-good"></div>
        <input type="submit" value="Сохранить" name="save" class="ui-btn ui-btn-sm ui-btn-light-border ui-btn-round">
    </div>

</form>
