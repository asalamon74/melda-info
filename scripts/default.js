$(document).ready(function() {
$(".fancybox").fancybox({
    openEffect	: 'elastic',
    closeEffect	: 'elastic',
    helpers : {
        title : {
            type : 'inside'
        },
        thumbs : {
            width : 50,
            height : 50
        },
        buttons : {
        }
    },
//    afterShow: function() {
//        $(".fancybox-wrap").swipe({
//            swipe : function(event, direction) {
//                if (direction === 'left' || direction === 'up') {
//                    $.fancybox.prev( direction );
//                } else {
//                    $.fancybox.next( direction );
//                }
//            }
//        });        
//    },    
//    afterLoad : function() {
//    }
});
});
