jQuery(document).ready(function($){
    el = $('a.email_address');
    el.each(function(){
        el.attr('href','mailto:' + el.attr('href').replace('|','@').replace('/','').replace(':','.'));
        el.attr('title',el.attr('href').replace('|','@').replace('/','').replace(':','.').replace('mailto.','Email: '));
        el.html(el.html().replace('|','@').replace('/','').replace(':','.'));
    });
});
