$(function(){





});





/*
 *  *Show info message
 *   * input JSON object with bool STATUS and string MESSAGE
 */
 
 
function getStatus( data )
{
    if (data.status == "0" ) { 
        $("<span>"+data.msg+"</span>").csInfo("cs-gradient-red");
 
    }   
    else if (data.status == "1" ){
        $("<span>"+data.msg+"</span>").csInfo("cs-gradient-green");
    
    }   
}
