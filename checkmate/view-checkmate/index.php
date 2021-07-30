<?php
use Bitrix\Main\Application;

include($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Просмотр шахматки");
$request = Application::getInstance()->getContext()->getRequest();
?>
    <div class="main-block-doc">
        <?
        $APPLICATION->IncludeComponent(
            "art:checkmate.view",
            "",
            ['ID' => $request->getQuery('DEAL_ID')]
        );
        ?>
    </div>
<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");