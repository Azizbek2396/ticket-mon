<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$this->title = 'Рассадка';

?>

<div class="site-index">
    <div class="row" style="margin-bottom: 20px;">
        <div class="col-lg-4">
            <input type="text" name="zarep_name" id="zakreptext" class="form-control" oninput="zakrepTextInput()" placeholder="Введите комментарий..." />
        </div>
        <div class="col-lg-2"><button id="zakrep" class="btn btn-primary disabled" disabled>Закрепить</button></div>
        <div class="col-lg-2"><a href="<?= \yii\helpers\Url::toRoute(["saver/index",'id'=>$id])?>" class="btn btn-success">Редактировать</a></div>
        <div class="col-lg-2"><a href="?r=site/scheme" class="btn btn-success">Обновить</a></div>
        <div class="col-lg-2"><a href="?r=site/download&id=<?=$id?>" class="btn btn-success">Скачать EXCEL</a></div>
    </div>
    <?php $form = ActiveForm::begin([
        'action' => ['site/scheme'],
        'method' => 'post',
    ]); ?>
        <div class="row" style="margin-bottom: 50px;">

            <div class="col-lg-4"><?=Html::dropDownList('id', $id, $events, ['class'=>'form-control'])?></div>
            <div class="col-lg-4"><?= Html::submitButton('Перейти', ['class' => 'btn btn-primary']) ?></div>

        </div>
    <?php ActiveForm::end(); ?>

<!--    <div id="accessable_places"><span id='accessable_color_span'></span> Свободные места: <span id='accessable_count'></span></div>-->
    <div class="row">
        <div class="col-12">
            <ul class="comments">
<!--                <li class="comment_item"><span class="comment_color"></span><h4 class="comment">GABT</h4> : <p>23</p></li>-->
<!--                <li class="comment_item"><span class="comment_color"></span><h4 class="comment">GABT</h4> : <p>23</p></li>-->
<!--                <li class="comment_item"><span class="comment_color"></span><h4 class="comment">GABT</h4> : <p>23</p></li>-->
<!--                <li class="comment_item"><span class="comment_color"></span><h4 class="comment">GABT</h4> : <p>23</p></li>-->
<!--                <li class="comment_item"><span class="comment_color"></span><h4 class="comment">GABT</h4> : <p>23</p></li>-->
<!--                <li class="comment_item"><span class="comment_color"></span><h4 class="comment">GABT</h4> : <p>23</p></li>-->
            </ul>
        </div>
    </div>

    <script src="https://code.jquery.com/ui/1.11.3/jquery-ui.js"></script>
    <link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/overcast/jquery-ui.css" />

    <script src="./svg-pan-zoom.js" ></script>

    <div id="pan-zoom-container" >
        <?= file_get_contents($path) ?>

    </div>
</div>

<div id="seat-alert" class="alert alert-success dispnone seatcomment">

</div>


<script src="/js/svg-pan-zoom.js"></script>
    <script>


        var panZoomTiger = svgPanZoom('#tiger', {
           // viewportSelector: '.svg-pan-zoom_viewport',
           // panEnabled: true,
           controlIconsEnabled: true,
           zoomEnabled: true,
           // dblClickZoomEnabled: true,
           // mouseWheelZoomEnabled: true,
           // preventMouseEventsDefault: true,
           // zoomScaleSensitivity: 0.2,
           // minZoom: 0.5,
           // maxZoom: 10,
           fit: true,
           // contain: false,
           center: true,
           // refreshRate: 'auto',
           // beforeZoom: function(){},
           // onZoom: function(){},
           // beforePan: function(){},
           // onPan: function(){},
           // onUpdatedCTM: function(){},
           // customEventsHandler: {},
           // eventsListenerElement: null
        });

        function init() {
            $("g.zakrep").hover(
                function() {
                    const text = this.getAttribute("title");
                    $("#seat-alert").html(text);
                    $("#seat-alert").removeClass('dispnone');
                    $("#seat-alert").addClass('dispblock');
                    $("#seat-alert").css('top', $(this).offset().top-30)
                    $("#seat-alert").css('left', $(this).offset().left+40)
                    // console.log($(this).offset());
                }, function() {
                    $("#seat-alert").html("");
                    $("#seat-alert").removeClass('dispblock');
                    $("#seat-alert").addClass('dispnone');
                }
            );

            $("g.zakrep").click(
                function() {
                    const text = this.getAttribute("title");
                    console.log(text);
                    $("#seat-alert").html(text);
                    $("#seat-alert").removeClass('dispnone');
                    $("#seat-alert").addClass('dispblock');
                    $("#seat-alert").css('top', $(this).offset().top-30)
                    $("#seat-alert").css('left', $(this).offset().left+40)
                    // console.log($(this).offset());
                },
                // function() {
                //     $("#seat-alert").html("");
                //     $("#seat-alert").removeClass('dispblock');
                //     $("#seat-alert").addClass('dispnone');
                // }
            )
        }

        const seatCallback = function (data) {
            $.each(data.seats,function(index,value) {
                $('#'+index)
                    .addClass('zakrep')
                    .attr("title", "<span class='rectangle'></span>" + "<strong>" + value.comment + "</strong>" + " - " + value.place_title);
                $('#'+index+' path').css('fill', '#'+value.color);
            });

            $.each(data.counts, function(index,value) {
                // console.log(index);
                $('.comments')
                    .append('<li class="comment_item"><span class="comment_color" style="background-color: #' + value.color + '"></span><h4 class="comment">'+ value.comment + '</h4> : <p>' + value.count +' </p></li>');
                // $('.comment_color')
                //     .css('background-color', '#' + value.color);
            });
            init();
        };

        const url = window.location.origin + "/index.php?r=seat/index&id=<?=$id?>";

        fetch(url)
            .then(response => response.json())
            .then(seatCallback);


        async function postData(url = '', data = {}) {
            // Default options are marked with *
            const response = await fetch(url, {
                method: 'POST', // *GET, POST, PUT, DELETE, etc.
                mode: 'cors', // no-cors, *cors, same-origin
                cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
                credentials: 'same-origin', // include, *same-origin, omit
                headers: {
                    'Content-Type': 'application/json'
                    // 'Content-Type': 'application/x-www-form-urlencoded',
                },
                redirect: 'follow', // manual, *follow, error
                referrerPolicy: 'no-referrer', // no-referrer, *client
                body: JSON.stringify(data) // body data type must match "Content-Type" header
            });
            return await response.json(); // parses JSON response into native JavaScript objects
        }

        postData(window.location.origin + "/index.php?r=seat/update", { comment: "Hi", seats: [
                {'id': "seat-2"}
                // {'place_title': },
            ]
        })
            .then((data) => {
                // console.log(data); // JSON data parsed by `response.json()` call
            });

        var seats = {};
        var comment = '';

        $( ".svg-pan-zoom_viewport g g g g" ).click(function() {
         if($(this).hasClass('active')){
           $(this).removeClass('active');
         }
         else{
           if(!$(this).hasClass('zakrep')){
               $(this).addClass('active');
           }
         }
        });

        $( "#zakrep" ).click(function() {
           $('.svg-pan-zoom_viewport g g g g.active').each(function(i, obj) {
               $(obj).addClass('zakrep');
               seats[i] = {};
               seats[i]['seatid'] = $(obj).attr('id');
               seats[i]['title'] = $(obj).attr('data-original-title');
           });
           comment = $('#zakreptext').val();
           $.post('?r=site/saver&id=<?=$id?>', {seats: seats, comment: comment}, function(data){});
        });

        function zakrepTextInput(){
           if($('#zakreptext').val().length>0){
               $('#zakrep').removeClass('disabled');
               $('#zakrep').prop("disabled", false);
           }
           else{
               $('#zakrep').addClass('disabled');
               $('#zakrep').prop("disabled", true);
           }
        }
        function toHexString(n) {
           if(n < 0) {
               n = 0xFFFFFFFF + n + 1;
           }
           return "0x" + ("00000000" + n.toString(16).toUpperCase()).substr(-8);
        }
                
    </script>
    <style type="text/css">
        #pan-zoom-container {
            width: 100%;
            height: 80vh;
            margin-left: auto;
            margin-right: auto;
            border:1px solid black;
            background-color: #DDDDDD;
        }

        #tiger{
            width: 100%;
            height: 100%;
        }

        body > div.wrap > div.container{
            width: 100%;
        }
        .wrap > .container {
            padding: 10px 15px 20px;
        }
        .wrap > nav{
            display: none;
        }
        /*#schemeid{*/
        /*    width: 100%;*/
        /*    height: 50%;*/
        /*    overflow: scroll;*/
        /*    margin-top: 30px;*/
        /*    text-align: center;*/
        /*}*/
        /*#schemeid svg{*/
        /*    width: 100%;*/
        /*    transform: translate3d(0px, 0px, 0px);*/

        /*}*/
        .active path{
            fill: yellow;
        }
        .active text{
            fill: black;
        }

        .zakrep path{
            fill: #7656FD;
        }
        .zakrep text{
            fill: white;
        }
        .dispnone{
            display: none;
        }

        .dispblock {
            display: block;
        }
        .alert-success{
            position: absolute;
            min-width: 100px;
        }
        .seatcomment{
            position: absolute;
        }

        .rectangle {
            position: relative;
        }
        .rectangle:after {
            content: "";
            position: absolute;
            top: 50%;
            left: -43px;
            margin-top: -15px;
            border-width: 15px;
            border-style: solid;
            border-color: transparent #dff0d8 transparent transparent;
        }

        /*#accessable_places{*/
        /*    position: absolute;*/
        /*    top: 140px;*/
        /*}*/

        .comments {
            padding: 0;
            height: 100px;
            overflow: auto;
            margin-bottom: 20px;
            background-color: rgba(0, 0, 0, 0.05);
        }

        .comments > li {
            list-style-type: none;
        }
        .comment_item {
            display: flex;
        }

        .comment_item p,
        .comment_item h4 {
            margin: 0;
        }

        .comment_item p {
            margin-left: 8px;
        }
        .comment_color {
            /*background-color: rgb(217, 171, 10);*/
            width: 10px;
            height: 10px;
            display: inline-block;
            margin-right: 8px;
            margin-top: 5px;
        }
    </style>