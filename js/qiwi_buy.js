function QiwiBuyFrom( data )
{
  var opt = {
    beforeSubmit: CheckQiwiForm,
    success: phoxy.ApiAnswer,
    url: "buy/qiwi/request",
    type: "post",
    dataType: "json"
  };
  $('#qiwi_form').ajaxForm(opt);
}

function CheckQiwiForm( form )
{
  return true;
}