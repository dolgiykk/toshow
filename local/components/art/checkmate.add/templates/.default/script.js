$(document).ready(function () {
    new SlimSelect({
        select: ".deal",
        showSearch: true,
        placeholder: 'Не установлено',
        beforeOpen: function beforeOpen() {
        }
    });

    $('.deal').on('change', function () {
        if ($(this).val() == '0') {
            $(this).addClass('incorrect-data');
            $('.block-error').text('Некорректные данные');
        } else {
            $(this).removeClass('incorrect-data');
            $('.block-error').text('');
        }
    });

    $('.floor-count').on('input', function () {
        if ($(this).val() == '' || $(this).val() == 0 || $(this).val() > 50) {
            $(this).addClass('incorrect-data');
            $('.block-error').text('Некорректные данные');
        } else {
            $(this).removeClass('incorrect-data');
            $('.block-error').text('');
        }
    });

    $('.n-room').on('input', function () {
        let flat_count = 0;
        $('.n-room').each(function () {
            if ($(this).val())
                flat_count += parseInt($(this).val());
        })

        if ((flat_count > 25) || (flat_count == 0)) {
            $('.n-room').each(function () {
                $(this).addClass('incorrect-data');
            });
            $('.block-error').text('Некорректные данные');
        } else {
            $('.n-room').each(function () {
                $(this).removeClass('incorrect-data');
            });
            $('.block-error').text('');
        }
    })

    $("form.checkmate-add").submit(function (e) {
        let flag = true;
        let deal_id = $('.deal').val();
        let floor_count = $('.floor-count').val();
        if (deal_id == '0') {
            $('.deal').addClass('incorrect-data');
            flag = false;
        }

        if (floor_count == '') {
            $('.floor-count').addClass('incorrect-data');
            flag = false;
        }

        let flat_count = 0;
        $('.n-room').each(function () {
            if ($(this).val())
                flat_count += parseInt($(this).val());
        })

        if ((flat_count > 25) || (flat_count == 0)) {
            $('.n-room').each(function () {
                $(this).addClass('incorrect-data');
                flag = false;
            });
        } else {
            $('.n-room').each(function () {
                $(this).removeClass('incorrect-data');
            });
        }

        if (!flag) {
            $('.block-error').text('Некорректные данные');
            e.preventDefault();
        } else {
            var formData = new FormData(this);
            BX.showWait();
            BX.ajax.runComponentAction('art:checkmate.add',
                'save', {
                    mode: 'class',
                    data: formData,
                })
                .then(function (response) {
                    if (response.status == "success") {
                        if (response.data.ok == 1)
                            $('.block-good').text('Шахматка успешно добавлена');
                    } else {
                        $('.block-error').text('Произошла ошибка');
                    }
                    BX.closeWait();
                });
        }
    })
});
