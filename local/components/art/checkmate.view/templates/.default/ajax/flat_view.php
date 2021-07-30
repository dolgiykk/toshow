<?php require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

use Bitrix\Main\Application;
use Bitrix\Main\Page;

Page\Asset::getInstance()->addJs('/dist/libs/slimselect.min.js');
Page\Asset::getInstance()->addCss('/dist/libs/slimselect.min.css');

$request = Application::getInstance()->getContext()->getRequest();
$floor = $request->getPost('floor');//Этаж
$flatinfo = explode('-', $request->getPost('flatinfo'));//номер квартиры и номер комнаты

$flat = $flatinfo[0];//номер квартиры в HL-блоке
$roomNumber = $flatinfo[1];//номер комнаты
$flatCounter = $flatinfo[2];//номер квартиры в представлении

$deal = $request->getPost('deal');//Элемент в HL блоке
$worktype = $request->getPost('worktype');//вид работ
$roomsCount = $request->getPost('rooms_count');//количество комнат
if ($worktype)
    $worktype = explode('-', $worktype);

$worktypes = [];
$entity_data_class = ArtPobedaHelper::getHlClassByName('RefBookMain');//справочник с видами работ
$rsData = $entity_data_class::getList(array(
    'order' => array('ID' => 'ASC'),
    'select' => array('*'),
));

while ($res = $rsData->Fetch()) {
    $worktypes[$res['ID']] = $res['UF_NAME'];
}
?>

<div style="width: 600px;">
    <h2><? echo('Этаж ' . $floor . '  квартира № ' . $flatCounter . ', комната № ' . $roomNumber . ', ' . $roomsCount . ' комнатная') ?></h2>
    <div class="data-helper" style="display:none;" data-floor="<?= $floor; ?>"
         data-flatinfo="<?= $flat . '-' . $roomNumber; ?>"
         data-deal="<?= $deal ?>"></div>
    <div id="checkmate-view">
        <table>
            <tr>
                <td>
                    <label style="float:right;">Выберите вид работ:</label>
                </td>
                <td>
                    <select multiple class="slim-select worktype" id="worktype"
                            name="worktype" <? /*echo ($worktype) ? 'disabled' : '' */ ?> style="width: 300px">
                        <? foreach ($worktypes as $key => $value): ?>
                            <option value="<?= $key ?>" <? if (in_array($key, $worktype)) echo 'selected'; ?>><?= $value ?></option>
                        <? endforeach; ?>
                    </select>
                </td>
            </tr>
        </table>
        <? //if (!$worktype): ?>
        <div class="button-footer" style="position: absolute;
    bottom: 20px;
    left: 0;
    right: 0;
    margin: 0 auto;">
            <div class="block-error"></div>
            <div class="block-good"></div>
            <input type="submit" value="Сохранить" class="ui-btn ui-btn-sm ui-btn-light-border ui-btn-round save-flat">
        </div>
        <? //endif; ?>
    </div>
</div>
<script>
    new SlimSelect({
        select: "#worktype",
        showSearch: true,
        placeholder: 'Не установлено',
        beforeOpen: function beforeOpen() {
        }
    });

    $('#worktype').on('change', function () {
        if ($(this).val() == '0') {
            $(this).addClass('incorrect-data');
        } else {
            $(this).removeClass('incorrect-data');
        }
    });

    $('.save-flat').on('click', function () {
        let worktype = $('#worktype').val();
        if (worktype == null)
            worktype = '';
        $('.block-error').text('');
        let data = {};
        let floor = $('.data-helper').data('floor');
        let flatinfo = $('.data-helper').data('flatinfo').split('-');
        let flat = flatinfo[0];
        let room_number = flatinfo[1];
        let deal = $('.data-helper').data('deal');


        data['floor'] = floor;
        data['flat'] = flat;
        data['worktype'] = worktype;
        data['deal'] = deal;
        data['room_number'] = room_number;
        $.ajax({
            url: "/local/components/art/checkmate.view/templates/.default/ajax/save.php",
            type: "POST",
            data: data,
            success: function (result) {
                if (result == "Success!") {
                    $('.block-good').text('Данные успешно обновлены!');
                    console.log(result);
                    location.href = '/checkmate/view-checkmate/?DEAL_ID=' + deal;
                    $('.save-flat').hide();
                } else {
                    $('.block-error').text('Произошла ошибка!');
                    console.log("не удалось подключить аякс");
                }
            }
        });
    });
</script>
