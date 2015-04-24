<?php
/* @var $this yii\web\View */
//http://forum.ru-board.com/topic.cgi?forum=31&topic=19256
$this->title = 'Галерея
';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Галерея</h1>
        <p class="lead">рисунки canvas</p>
    </div>

    <a class="gallery" href="/pictures/39-zcdK5DrJa-CG74kuI1IT8wJz9DmIn3RK.png"><img src="/pictures/tn-39-zcdK5DrJa-CG74kuI1IT8wJz9DmIn3RK.png" width="160" height="128" alt=""/></a>

    <script type="text/javascript">
        window.onload = function() {
            $('.gallery').fancybox({
                openEffect  : 'none',
                closeEffect : 'none',
                helpers : {
                    media : {}
                }
            });
        }
    </script>


    <div class="jumbotron">
        <h1>Тестовое задание</h1>
        <p class="lead">для php-программиста</p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>Задание</h2>
                <ol>
                    <li>Необходимо разработать интерфейс списка "рисунков" - см. ниже по атрибутам доступа, указанным при сохранении.</li>
                    <li>Необходимо разработать на HTML5 с использованием JS и Canvas публичный интерфейс для монохромного рисования мышкой в выделенной области.</li>
                    <li>Необходимо сохранять изображение на сервере. При сохранении устанавливается пароль на доступ к редактированию.</li>
                    <li>Интерфейс должен позволять просматривать "рисунки" и редактировать их после проверки доступа.</li>
                </ol>
            </div>
            <div class="col-lg-4">
                <h2>Технологии</h2>
                <ul>
                    <li>Язык разработки серверной части - PHP 5.</li>
                    <li>Хранение данных в mysql.</li>
                    <li>Допускается использованием любых js- и php- фреймворков. Серверная часть на чистом php будет плюсом.</li>
                </ul>    
            </div>
            <div class="col-lg-4">
                <h2>Основные критерии оценки результата</h2>
                <ol>
                    <li>Безопасный код.</li>
                    <li>Рабочий функционал.</li>
                    <li>Желательно использование паттернов проектирования (как минимум mvc).</li>
                    <li>Аккуратный документированный код.</li>
                    <li>Юниттесты будут плюсом.</li>
                </ol>
            </div>
        </div>

    </div>
</div>
