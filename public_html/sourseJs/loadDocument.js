
//include(cmf.ajax.js);
//include(crawler.js);
$(document).ready(function(){
    cmf.loadDocument();
});
cmf.ready(function() {

    $("#tabsList").each(function() {
        $('li', this).each(function() {
            $(this).click(function() {
                var id = $('a', this).attr('name');
                $('.tabsList').hide();
                $('#'+ id).show();
                $('#tabsList li').each(function() {
                    if(id==$('a', this).attr('name')) {
                        $(this).attr('id', 'v_active');
                    } else {
                        $(this).attr('id', '');
                    }
                });
                cmf.main.scroll.height(id);
            })
        });
    });


	$("a.fancybox").fancybox({

	});


   /* $('#mycrawler a img').each(function() {
        cmf.image.add($(this).attr('src'));
    });*/
//    $('#mycrawler a img').addClass('img');
//    marqueeInit({
//        uniqueid: 'mycrawler',
//        style: {
//            'padding': '0 0 0 0',
//            'width': '760px',
//            'height': '63px'
//        },
//        inc: 8, //�������� ��������� ��������� ������
//        mouse: 'cursor driven', //�� ������
//        moveatleast: 1, // �������� ��������� ���������
//        neutral: 75, // �������� ����� ��� ������ �� ������������ ��������	(�������� ������������� �� ������ ����� � ����)
//        savedirection: true, // �� ������
//        stopMarquee1: true
//    });
//
//
//    $('#slider').nivoSlider({
//        effect:'fade', // ������� ���: 'random, fold, fade, sliceDown'
//        slices:15,
//        animSpeed:800,
//        pauseTime:sliderTime*1000,
//        directionNav:false, //Next & Prev
//        directionNavHide:true, //Only show on hover
//        controlNav:false, //1,2,3...
//        keyboardNav:true, //Use left & right arrows
//        pauseOnHover:false, //Stop animation while hovering
//        manualAdvance:false, //Force manual transitions
//        captionOpacity:0.8, //Universal caption opacity
//        beforeChange: function(){},
//        afterChange: function(){},
//        slideshowEnd: function(){} //Triggers after all slides have been shown
//    });

});