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

    <?php
   /*
                   'user_id' => $arr['userId'],
                   'author_name' => $arr['username'],
                   'img_name' => $arr['imageName'],
                   'thumb_name' => "tn-".$arr['imageName'],
                   'create_time' => $arr['createTime'],
                   'edit_link' => '<a href="">редактировать</a>',
   */
        foreach(app\models\Gallery::$gallery as $arr)
        {
            echo '<div class="picture">
<a class="gallery" href="/pictures/'.$arr['img_name'].'"><img src="/pictures/'.$arr['thumb_name'].'" width="160" height="128" alt=""/></a>            
            ';
            echo '<div>'.$arr['create_time'].'</div>';
            echo '<div id="pic_'.$arr['img_id'].'" onmouseover="show_author(\''.$arr['author_name_full'].'\', '.$arr['img_id'].')"  onmouseout="hide_author(\''.$arr['author_name_crop'].'\', '.$arr['img_id'].')">Автор: '.$arr['author_name_crop'].'</div>';
            echo isset($arr['edit_link'])? '<div>'.$arr['edit_link'].'</div>' : null;
            echo isset($arr['delete_link'])? '<div>'.$arr['delete_link'].'</div>' : null;
            echo '</div>';
        }
    ?>

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

    <div class="clear">
    </div>

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
