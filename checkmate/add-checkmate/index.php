<?php
include($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Добавить шахматку");
use Bitrix\Main\Page;
Page\Asset::getInstance()->addCss('/dist/css/refbook.css');
?>
<div class="main-block-doc">
    <?
    $APPLICATION->IncludeComponent(
        "art:checkmate.add",
        "",
        false
    );
    ?>
</div>
<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");