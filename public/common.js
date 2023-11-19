
function handleFailedResponse(form_id, response) {
    $("#"+form_id+" .post_error").each(function () {
        this.innerHTML = "";
    });
    if (response.responseJSON.errors) {
        $.each(response.responseJSON.errors, function (key, value) {
            $("#"+form_id+" small[id=" + key + "_error]")
                .html(value)
                .css("color", "red");
        });
    }
}

function handleGetResponse(){

}

function loadCommonFormData(that) {
    const href = $(that).data("href");
    $("#editModal").html("").trigger("create");
    $.get(href, function (data, status) {
        $("#editModal").html(data).trigger("create");
        $("#editModal").modal("show");

        if($("#event_staff_id")) {
          $("#event_staff_id").select2({ width: "resolve" });
        }
        
        $("#editModalAccept").click(function () {
            submitCommonForm();
        });
    });
  }

function submitCommonForm() {
    showProgress("add");
    clearMessages("form_add");
    var data = $("#form_add").serialize();
    $("#editModalAccept").prop('disabled', true);
    $.post($("#editModalAccept").data("href"), data, function (data, status) {
        $("#editModal").modal("hide");
        $("#form_submit_message_span").html(data.message);
        $("#form_submit_message").modal("show");
        clearProgress("add");
        loadReport();
    }).fail(function (response) {
        handleFailedResponse("form_add", response);
        $("#editModalAccept").prop('disabled', false);
    });
}


  function loadCommonReport(that) {
    const href = $(that).data("href");
    $("#editModal").html("").trigger("create");
    $.get(href, function (data, status) {
        $("#editModal").html(data).trigger("create");
        $("#editModal").modal("show");
    });
  }



function onDeleteRecord(that, view_modal, callBack = null) {
    console.log("onDeleteRecord", $(that).data("href"));
    if (confirm('Sure? Deleted entry will not be recovered.')) {
        $.post($(that).data("href"), null, function (data, status) {
            $("#"+view_modal).modal("hide");
            alert(data.message);
            if(callBack)
                callBack();
        }).fail(function (response) {
            alert('Some technical error.');
        });
    }
}


function onValidateRecord(that, view_modal) {
    console.log("onDeleteRecord", $(that).data("href"));
    $("#"+view_modal).html("").trigger("create");
    $.get($(that).data("href"), function (data, status) {
        $("#"+view_modal).html(data).trigger("create");
        $("#"+view_modal).modal("show");
    });
}


////////////////////////////////////////////////////////////////

function loadDetails(that) {
    const href = $(that).data("href");
    const details_key = $(that).data("details_key");
    $.get(href+"?details_key="+details_key, function (data, status) {
        $(getMyModelId(details_key)).html(data).trigger("create");
        $(getMyModelId(details_key)).modal("show");
        
        $(getAcceptModelId(details_key)).click(function () {
          $(getAcceptModelId(details_key)).prop('disabled', true);
          submitData(details_key);
        });
    });
  };
  
  function getMyModelId(details_key) {
    return "#"+details_key+"Modal";
  }
  
  function getAcceptModelId(model_key) {
    return "#"+model_key+"Accept";
  }
  
  
  function submitData(details_key) {
    showProgress(details_key);
    clearMessages("form_"+details_key);
    if (details_key =="importExcel")
    {
        // var data = new FormData();
        // var data = $("#form_"+details_key)[0]; 
        // console.log(data);
        // data.append('file', files); 
        var data = new FormData(); 
        var files = $('#file')[0].files[0]; 
        data.append('file', files);

        $.ajax({ 
            url: $(getAcceptModelId(details_key)).data("href"), 
            type: 'post', 
            data: data, 
            contentType: false, 
            processData: false, 
            success: function(response){ 
                if(response != 0){ 
                    // alert('file uploaded'); 
                    $(getMyModelId(details_key)).modal("hide");
            
                    $("#form_submit_message_span").html(response.message);
                    $("#form_submit_message").modal("show");
                    clearProgress(details_key);
                    // location.reload(true)
                    loadReport();
                } 
                else{ 
                    alert('file not uploaded'); 
                    $("#form_"+details_key+" .post_error").each(function () {
                        this.innerHTML = "";
                    });
                    console.log(response.responseJSON.errors);
                    if (response.responseJSON.errors) {
                        $.each(response.responseJSON.errors, function (key, value) {
                            console.log(key + ": " + value, $("#" + key + "_error"));
                            $("#" + key + "_error")
                                .html(value)
                                .css("color", "red");
                        });
                    }
                    $(getAcceptModelId(details_key)).prop('disabled', false);
                } 
            }, 
        });
    }
    else {
        var data = $("#form_"+details_key).serialize();
        $.post($(getAcceptModelId(details_key)).data("href"), data, function (data, status) {
            $(getMyModelId(details_key)).modal("hide");
            
            $("#form_submit_message_span").html(data.message);
            $("#form_submit_message").modal("show");
            clearProgress(details_key);
            if($('#boutKey') == undefined) {
                loadReport();
            }
        }).fail(function (response) {
            $("#form_"+details_key+" .post_error").each(function () {
                this.innerHTML = "";
            });
            console.log(response.responseJSON.errors);
            if (response.responseJSON.errors) {
                $.each(response.responseJSON.errors, function (key, value) {
                    console.log(key + ": " + value, $("#" + key + "_error"));
                    $("#" + key + "_error")
                        .html(value)
                        .css("color", "red");
                });
            }
            $(getAcceptModelId(details_key)).prop('disabled', false);
        });
    }
  }

  function loadContentDetails(that, details_key, clear_me) {
    const href = $(that).data("href");
    const data_details_key = $(that).data("details_key");
    $('li[name="li_'+details_key+'"]').removeClass("active");
    $(that).addClass("active");
    
    $("#div_" + details_key).html(
        '<div class="progress"><div class="progress-bar progress-bar-indeterminate" role="progressbar"></div></div>'
    );

    $.get(href, function (data, status) {
        $('#div_'+details_key).html(data).trigger("create");
        $('#div_'+clear_me).html("").trigger("create");
        if(data_details_key != undefined) {
            $(getAcceptModelId(data_details_key)).click(function () {
                $(getAcceptModelId(data_details_key)).prop('disabled', true);
                submitData(data_details_key);
                // const flag = loadBoutDetailsFromSubmit(); 
            });
        }
    });
  };

//   function loadBoutDetailsFromSubmit() {
//     if($('#boutKey')) {
//         const boutKey = $('#boutKey').value;
//         console.log(boutKey)
//         console.log($('#'+boutKey));
//         // loadContentDetails($('#'+boutKey), 'participants', 'karate_ka');
//         return false;
//         // console.log($('#'+$('#boutKey')));
//     } else {
//         return true;
//     }
//     // li_participants_69_0_191
//   }