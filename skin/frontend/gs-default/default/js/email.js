if (window.jQuery) { 
    jQuery(document).ready(function($){
        $('a.email_address').each(function(itr,el){
            $(el).attr('href','mailto:' + $(el).attr('href').replace('|','@').replace('/','').replace(':','.'));
            $(el).attr('title',$(el).attr('href').replace('|','@').replace('/','').replace(':','.').replace('mailto.','Email: '));
            $(el).html($(el).html().replace('|','@').replace('/','').replace(':','.'));
        });
    });
}
