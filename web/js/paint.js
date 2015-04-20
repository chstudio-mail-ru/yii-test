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
            context.moveTo(ev._x, ev._y);
            tool.started = true;
        };

        // Эта функция вызывается каждый раз, когда вы перемещаете мышь.
        // Но рисование происходит только когда вы удерживаете кнопку мыши
        // нажатой.
        this.mousemove = function (ev) {
            if (tool.started) { //если кнопка нажата
                //если в пределах прямоугольника - рисуем
                if(ev._x > 0 && ev._y > 0 && ev._x < canvas.width && ev._y < canvas.height) {
                    context.lineTo(ev._x, ev._y);
                    context.stroke();
                }
                else {  //при выходе за пределы прямоугольника - автоматически отпускаем кнопку
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

