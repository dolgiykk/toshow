$(document).ready(function () {
    new SlimSelect({
        select: ".worktype-filter",
        showSearch: true,
        placeholder: 'Не установлено',
        beforeOpen: function beforeOpen() {
        }
    });

    $('.flat').click(function () {
        let data = {};
        data['floor'] = $(this).data("floor");//этаж
        data['flatinfo'] = $(this).data("flatinfo");//№ квартиры и комнаты
        data['deal'] = $('.data-class').data('deal');//ID сделки
        data['worktype'] = $(this).data("worktype");//виды работ в комнате
        data['rooms_count'] = $(this).data("rooms_count");//количество комнат в квартире
        $.fancybox.open({
            src: "/local/components/art/checkmate.view/templates/.default/ajax/flat_view.php",
            type: "ajax",
            width: 600,
            height: 485,
            ajax: {
                settings: {
                    type: "POST",
                    data: data
                }
            }
        });
    });

    $('.worktype-filter').on('change', function () {
        let worktypes_filter = $(this).val();

        if (!worktypes_filter) {
            $('.flat').each(function () {
                $(this).removeClass('needless');
            });
        } else {
            $('.flat').each(function () {
                let flag = true;

                let worktypes = String($(this).data('worktype'));

                if (worktypes != '') ;
                worktypes = worktypes.split('-');
                worktypes_filter.forEach(function (el) {
                    if (!worktypes.includes(el)) {
                        flag = false;
                    }
                });
                if (flag)
                    $(this).removeClass('needless');
                else
                    $(this).addClass('needless');
            })
        }
    });

    let maxBoxHeight = 0;
    $('.flat').each(function () {

        var height = $(this).height();

        if (height > maxBoxHeight) {
            maxBoxHeight = height;
        }
    });

    $('.flat').css('height', maxBoxHeight + 'px');
});