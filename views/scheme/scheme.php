<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$this->title = 'Рассадка';

?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<div class="site-index">


    <script src="https://code.jquery.com/ui/1.11.3/jquery-ui.js"></script>
    <link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/overcast/jquery-ui.css" />

    <script src="./js/svg-pan-zoom.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <div id="pan-zoom-container" >
        <?= $svg ?>

    </div>
</div>




<script>


    var panZoomTiger = svgPanZoom('#Layer_1', {
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

    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>
<script src="/js/svg-pan-zoom.js"></script>
    <style type="text/css">
        #pan-zoom-container {
            width: 100%;
            height: 80vh;
            margin-left: auto;
            margin-right: auto;
            border:1px solid black;
            background-color: #DDDDDD;
        }

        #Layer_1{
            width: 100%;
            height: 100%;
        }

    </style>