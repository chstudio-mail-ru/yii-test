if(window.addEventListener) {
    window.addEventListener('load', function () {

    var canvas, context, tool;

    function init () {
        // Находим canvas элемент
        canvas = document.getElementById('imageView');

        if (!canvas) {
            //нет canvas элемента
            return;
        }

        if (!canvas.getContext) {
            alert('Ошибка: canvas.getContext не существует!');
            return;
        }

        // Получаем 2D canvas context.
        context = canvas.getContext('2d');
        if (!context) {
            alert('Ошибка: getContext(\'2d\')! не существует');
            return;
        }

        tool = new tool_pencil();
        canvas.addEventListener('mousedown', ev_canvas, false);
        canvas.addEventListener('mousemove', ev_canvas, false);
        canvas.addEventListener('mouseup',   ev_canvas, false);
    }

    // Здесь мы будем ловить движения мыши
    function tool_pencil () {
        var tool = this;
        this.started = false;

        //Событие при нажатии кнопки мыши
        this.mousedown = function (ev) {
            context.beginPath();
            //в firefox нужно отнимать от координат координаты этого canvas
            if(ev.offsetX==undefined && (ev.layerX || ev.layerX == 0))
            {
                context.moveTo(ev._x - canvas.offsetLeft, ev._y - canvas.offsetTop);
            }
            else    
            {
                context.moveTo(ev._x, ev._y);
            }
            tool.started = true;
        };

        // Эта функция вызывается каждый раз, когда вы перемещаете мышь.
        // Но рисование происходит только когда вы удерживаете кнопку мыши
        // нажатой.
        this.mousemove = function (ev) {
            if (tool.started) { //если кнопка нажата
                //в firefox нужно отнимать от координат координаты этого canvas
                if(ev.offsetX==undefined && (ev.layerX || ev.layerX == 0))
                {
                    //если в пределах прямоугольника - рисуем (прибавляем canvas.offsetLeft и canvas.offsetTop)    
                    if(ev._x > (0 + canvas.offsetLeft) && ev._y > (0 + canvas.offsetTop) && ev._x < (canvas.width + canvas.offsetLeft) && ev._y < (canvas.height + canvas.offsetTop))
                    {
                        context.lineTo(ev._x - canvas.offsetLeft, ev._y - canvas.offsetTop);
                        context.stroke();
                    }
                    else
                    {  //при выходе за пределы прямоугольника - автоматически отпускаем кнопку
                        tool.started = false;    
                    }
                }
                //иначе просто проверяем координаты - в canvas или нет, если да - рисуем
                else if(ev._x > 0 && ev._y > 0 && ev._x < canvas.width && ev._y < canvas.height)
                {   
                    context.lineTo(ev._x, ev._y);
                    context.stroke();
                }
                else
                {  //при выходе за пределы прямоугольника - автоматически отпускаем кнопку
                    tool.started = false;    
                }
            }
        };

        // Событие при отпускании мыши
        this.mouseup = function (ev) {
            if (tool.started) {
                tool.mousemove(ev);
                tool.started = false;
            }
        };
    }

    // Эта функция определяет позицию курсора относительно холста
    function ev_canvas (ev) {
        if (ev.layerX || ev.layerX == 0) { // Firefox
            ev._x = ev.layerX;
            ev._y = ev.layerY;
        } else if (ev.offsetX || ev.offsetX == 0) { // Opera
            ev._x = ev.offsetX;
            ev._y = ev.offsetY;
        }

        // Вызываем обработчик события tool
        var func = tool[ev.type];
        if (func) {
            func(ev);
        }
    }

    init();

}, false); }

    //очистка прямоугольника
    function canvasClear() {
        // Находим canvas элемент
        canvas = document.getElementById('imageView');

        if (!canvas) {
            //нет canvas элемента
            return;
        }

        if (!canvas.getContext) {
            alert('Ошибка: canvas.getContext не существует!');
            return;
        }

        // Получаем 2D canvas context.
        context = canvas.getContext('2d');
        if (!context) {
            alert('Ошибка: getContext(\'2d\')! не существует');
            return;
        }

        //console.log(context);
        context.clearRect(0, 0, canvas.width, canvas.height);
    }

    //Сохранение картинки
    function canvasSave() {
        // Находим canvas элемент
        canvas = document.getElementById('imageView');

        if (!canvas) {
            //нет canvas элемента
            return;
        }

        if (!canvas.getContext) {
            alert('Ошибка: canvas.getContext не существует!');
            return;
        }

        // Получаем 2D canvas context.
        context = canvas.getContext('2d');
        if (!context) {
            alert('Ошибка: getContext(\'2d\')! не существует');
            return;
        }

        img = canvas.toDataURL("image/png");

        //сохраняем
        $.ajax({
            url: '/index.php/site/save/',
            type: 'POST',
            data: {'data': img},
            success: function(data) {
                //alert(data);
            }
        });
    }

