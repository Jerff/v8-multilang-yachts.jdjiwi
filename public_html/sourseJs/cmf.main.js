
//include(cmf.ajax.js);
cmf.ajax.runController = function() {
    return cmf.ajax.drive.start(cmf.ajax.getUrl() + arguments[0], arguments[1], arguments[2]);
};
cmf.main = new(function() {
    var t = this;


    /* scroll */
    t.scroll = new(function() {
        var scroll = this;
        scroll.init = function(count, limit, func, id) {
            ready(function(){
                if($('#scroll_vertical')) {
                     $('#scroll_vertical').ajaxScroll({
                        updateBatch: func,
                        batchNum: count,
                        batchSize: limit,
                        maxOffset: count*limit
                    });
                    scroll.height(id);
                }
            });
        };
        scroll.initFull = function(id) {
            ready(function(){
                $('#scroll_vertical').ajaxScroll();
                scroll.height(id);
            });
        };
        scroll.height = function(id) {
            if(!id) id = '';
            var index = (is = (id!='yachts' && id.indexOf('yachts')!=-1)) ? '#'+ id +' #scroll_vertical' : '#scroll_vertical';
            if($(index).get(0)) {
                //pre($(index));
                $height = $('#content>.left_block').height()-$('#content>.right_block').height();

                if(!$(index)) return;
                if($height>50) {
                    $height = parseInt($(index).css('maxHeight'))+$height;
                    if($height>800) $height = 800;
                    $(index).css('maxHeight', $height +'px');
                }
                if(id=='yachts' || id.indexOf('yachts')!=-1) {
                    $('.block_yachts').addClass('block_yachts_2').removeClass('block_yachts');
                    if(is) {
                        $height = parseInt($(index).css('maxHeight'));
                        if($(index).height()<$height) {
                            $('.block_yachts_2').addClass('block_yachts').removeClass('block_yachts_2');
                        }
                    } else if($(index).height()>=$(index +'>div').height()) {
                        $('.block_yachts_2').addClass('block_yachts').removeClass('block_yachts_2');
                    }
                }
           }
        };
    });


    /* slides */
    t.slides = new(function() {
        var slides = this;
        slides.init = function() {
        	hs.graphicsDir = 'images/graphics/';
        	hs.align = 'center';
        	hs.transitions = ['expand', 'crossfade'];
        	hs.fadeInOut = true;
        	hs.dimmingOpacity = 0.8;
        	hs.wrapperClassName = 'borderless floating-caption';
        	hs.captionEval = 'this.thumb.alt';
        	hs.marginLeft = 100; // make room for the thumbstrip
        	hs.marginBottom = 80; // make room for the controls and the floating caption
        	hs.numberPosition = 'caption';
        	hs.lang.number = '%1/%2';

        	// Add the slideshow providing the controlbar and the thumbstrip
        	hs.addSlideshow({
        		//slideshowGroup: 'group1',
        		interval: 5000,
        		repeat: false,
        		useControls: true,
        		overlayOptions: {
        			className: 'text-controls',
        			position: 'bottom center',
        			relativeTo: 'viewport',
        			offsetX: 50,
        			offsetY: -5

        		},
        		thumbstrip: {
        			position: 'middle right',
        			mode: 'vertical',
        			relativeTo: 'viewport'
        		}
        	});

        	// Add the simple close button
        	hs.registerOverlay({
        		html: '<div class="closebutton" onclick="return hs.close(this)" title="Close"></div>',
        		position: 'top right',
        		fade: 2 // fading the semi-transparent overlay looks bad in IE
        	});
        };
    });

    /* catalog */
    t.search = new(function() {
        var search = this;

        search.start = function() {
            var name = cmf.getId('searchName').value;
            if(!name) return false;
            if(name=='Поиск...') return false;
            return true;
        };
    });


    /* imageLoad */
    t.imageLoad = new(function() {
        var imageLoad = this;
        imageLoad.list = new Array();
        imageLoad.id = 0;

        imageLoad.add = function(img) {

            imageLoad.list[imageLoad.id] = new Image();
            imageLoad.list[imageLoad.id].src = img;
            imageLoad.id++;

        };
    });

});