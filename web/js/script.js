$(function () {
   //Отправка номера страницы для валидации по нажатию на номер страницы
    $('body').on('click', '.paginate__link', function(event){
        var Number = $(this).data('id');//Вытаскиваем номер новый страницы
        $.ajax
        ({
            url: "/list-items/",
            type: "get",
            data: {Number: Number},
            success: function success(data) {
                $('#insert-items').html(data.html);//Если успешно добавляем выводим посты с выбранной страницы
            }
        })
    });

    //Делаем плавное выдвижение textarea для добавления коммента
    $('#list-comments__button-comment').on('click', function () {
        if($('#list-comments__input').css('display') == 'none')
        {
            $('#list-comments__input').slideDown();
        }
        else
            $('#list-comments__input').slideUp();
    });

    //Валидация формы нового комментария
    $('#list-comments__form').validate({
        rules: {
            listcommentstextarea: { required: true } //Одно правило на обязательность заполения
        },
        messages: {
            listcommentstextarea: "Комментарий не может быть пустым..."
        },
        errorPlacement: function errorPlacement(error, element) {
            element.addClass('valid-error__textarea');//Добавим класс с выделением border'a красным
            element.attr('placeholder',error.text());//Выведем сообщение об ошибке
        }
    });

    //Отправка данных для добавления коммента
    $('#list-comments__form').on('submit',function(event) {
        event.preventDefault();
        var comment = $('#list-comments__textarea').val(); //Текст коммента
        var type = $('#list-comments__form').attr('method'); // Тип запроса с формы
        var url = $('#list-comments__form').attr('action'); //Url для отправки
        var item_id=$('#list-comments__form').attr('data-id'); //Внешний ключ для добавления к нужному посту коммента
        $(this).ajaxSubmit
        ({
            data: {comment:comment, item_id:item_id},
            beforeSubmit: function beforeSubmit() {
                return $('#list-comments__form').valid(); //Вызов валидации перед отправкой
            },
            success: function success(data) {
                $('#list-comment__insert').append(data.html); //Сразу добавим коммент на страницу
                $('#list-comments__form')[0].reset();
            }
        })
    });

    //Валидация данных для авторизации
    $('#authorization-window__form').validate({
        rules: {
            authorizationwindowlogin: { required: true },
            authorizationwindowpassword: {required:true}
        },
        messages: {
            authorizationwindowlogin: "Логин не может быть пустым",
            authorizationwindowpassword: "Пароль не может быть пустым"
        },
        errorPlacement: function errorPlacement(error, element) {
            element.addClass('valid-error__input');
            element.attr('placeholder',error.text());
        }
    });

    //Отправка данных для авторизации
    $('#authorization-window__form').on('submit',function(event) {
        event.preventDefault();
        var type = $('#authorization-window__form').attr('method');
        var url = $('#authorization-window__form').attr('action');
        var login = $('#authorization-window__login').val();
        var password = $('#authorization-window__password').val();
        $(this).ajaxSubmit
        ({
            data: {login: login, password:password},
            beforeSubmit: function beforeSubmit() {
                return $('#authorization-window__form').valid();
            },
            error: function(xhr){
                if(xhr.status=='401') //Если вернется 401 (неавторизован) скажем об этом юзеру
                {
                    $('#authorization-window__span').text('Неверный логин/пароль');
                }
            },
            success: function success(data) {
                window.location.href = "http://"+window.location.host+"/"; //передадресация на главную
            }

        })
    });

    //Валидация данных нового поста
    $('#new-post__form').validate({
        rules: {
            newpostname: { required: true },
            newpostdescription: {required:true}
        },
        messages: {
            newpostname: "Название не может быть пустым",
            newpostdescription: "Текст поста не может быть пустым"
        },
        errorPlacement: function errorPlacement(error, element) {
            element.addClass('valid-error__input');
            element.attr('placeholder',error.text());
        }
    });

    //Отправка данных нового поста
    $('#new-post__form').on('submit',function(event) {
        event.preventDefault();
        var type = $('#new-post__form').attr('method');
        var url = $('#new-post__form').attr('action');
        var name = $('#new-post__name').val();
        var description = $('#new-post__description').val();
        $(this).ajaxSubmit
        ({
            data: {name: name, description:description},
            beforeSubmit: function beforeSubmit() {
                return $('#new-post__form').valid();
            },
            success: function success(data) {
                window.location.href = "http://"+window.location.host+"/single-item/?number="+data.id; //Сразу откроем новый пост

            }
        })
    });
});
