/**
 * Created by JetBrains PhpStorm.
 * User: Rustem
 * Date: 23.10.10
 * Time: 12:47
 * To change this template use File | Settings | File Templates.
 */
(function($) {
    $.fn.openNodeDialog = function(url,title,form,clear) {
        $('body').css('cursor', 'progress');
        $(form).dialog({ autoOpen: true, title: title, draggable:  true,
            modal: true, resizable:  true, closeOnEscape:  true, width:  610,  height:  560 ,
            open: function(event, ui) {
                $(clear).empty();
                $.get(url, {}, function(data, textStatus) {
                    $(form).html(data).show();
                }, 'html');
            },
            close: function(event, ui) {
                $(clear).empty();
                $('body').css('cursor', 'auto');
            }
        });
        return false;
    };
    $.fn.openNode = function(url,title,form,clear) {
        $('body').css('cursor', 'progress');
        $.get(url, {}, function(data, textStatus) {
            $(clear).empty();
            $(form).html(data).fadeIn('slow').focus();
            $('body').css('cursor', 'auto');
        },'html');
        return false;
    };

    $.fn.setRank = function(url, title, id, operation) {
        $.ajax({
            type: "POST",
            url: url,
            data: "&operation=" + operation,
            success: function(html){
              var node="#menu-node"+id+"-term50";
              $(node).html(html);
            },
            error: function(html){
              alert(html);
            }
        });
    }

    $.fn.toBookmark = function(id,url,btitle,bdesc){
        new Ya.share({
            element: id,
            elementStyle: {
                type: 'link',
                linkUnderline: false,
                linkIcon: true,
                border: false,
                quickServices: []
            },
            link:'http://rif.name'+url,
            title:btitle,
			/*image:'http://rif.name/image/rif.ico'*/
			serviceSpecific: {
				twitter: {
					title:btitle+'.. Риф Закиров http://rif.name'+url,
                                        link: ' '
				}
            },			
            description:btitle+' Риф Закиров',
            popupStyle: {
                copyPasteField: false,
                blocks: { // блоки
                'Поделитесь с друзьями!': ['twitter','gplus','vkontakte','odnoklassniki','moimir','facebook','yaru','lj','blogger','moikrug'
					],
                'Поделитесь по-другому!': ['yazakladki', 'linkedin', 'greader', 
					'delicious', 'juick', 'friendfeed','evernote','digg','myspace'
					]
                }
				
            }
        });
    }

    $.fn.initMenu = function() {
        $('#jqmenu ul').hide();
        $('#jqmenu ul:first').show();
        $('#jqmenu li a').click(function() {
            var checkElement = $(this).next();
            if((checkElement.is('ul')) && (checkElement.is(':visible'))) {
                return false;
            }
            if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
                $('#jqmenu ul:visible').slideUp('normal');
                checkElement.slideDown('normal');
                return false;
            }
        });
  };

})(jQuery);