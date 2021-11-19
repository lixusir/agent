function getQueryString(name) {
    var reg = new RegExp('(^|&)' + name + '=([^&]*)(&|$)', 'i');
    var r = window.location.search.substr(1).match(reg);
    if (r != null) {
        return unescape(r[2]);
    }
    return null;
}

function get_field_image( params, callback ){

    var url = location.href;
    url = url.replace(/r=[\w]+\.[\w]+/,'');

    url +=  '&r=tool.field_image';

    url += '&name=' + params.name;
    if(params.value){
        url += '&value=' + params.value;
    }

    axios.get( url ).then(function( res ){


        if( params.append_dom ){
            $( params.append_dom ).append( res.data );
        }


        typeof callback == 'function'?callback( res ):'';

    });

}