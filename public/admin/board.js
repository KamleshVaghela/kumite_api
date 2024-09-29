function loadReport() {
  $("#div_report").html(
    '<div class="progress"><div class="progress-bar progress-bar-indeterminate" role="progressbar"></div></div>'
  );
  $.get($("#btn_filter_data").data("href"), function (data, status) {
    $("#div_report").html(data);
    // if(document.getElementById('resizeMe')) {
    // createResizableTable(document.getElementById('resizeMe'));
    // }
    if (document.getElementById('datatables-example')) {
      $('#datatables-example').DataTable({
        fixedHeader: {
          header: true,
          footer: true
        }
      });
    }
  });
}

$(document).ready(function () {
  $("#searchBranch").select2({ width: "resolve" });
  loadReport();
});

function clearMessages(form_id) {
  $("#" + form_id + " .post_error").each(function () {
    this.innerHTML = "";
  });
}

function clearProgress(type) {
  $("#div_ajax_" + type).html("");
}

function showProgress(type) {
  $("#div_ajax_" + type).html(
    '<div class="progress"><div class="progress-bar progress-bar-indeterminate" role="progressbar"></div></div>'
  );
}

function loadLoadDetails(that) {

  const href = $(that).data("href");
  console.log(href);
  $.get(href, function (data, status) {
    $("#addModal").html(data).trigger("create");
    $("#addModal").modal("show");
    $("#addModalAccept").click(function () {
      $("#addModalAccept").prop('disabled', true);
      submitMyForm();
    });
  });
};

function submitMyForm() {
  showProgress("add");
  clearMessages("form_add");
  var data = $("#form_add").serialize();
  $.post($("#addModalAccept").data("href"), data, function (data, status) {
    $("#addModal").modal("hide");
    $("#form_submit_message_span").html(data.message);
    $("#form_submit_message").modal("show");

  }).fail(function (response) {
    $("#form_add .post_error").each(function () {
      this.innerHTML = "";
    });

    if (response.responseJSON.errors) {
      $.each(response.responseJSON.errors, function (key, value) {

        $("#" + key + "_error")
          .html(value)
          .css("color", "red");
      });
    }
    $("#addModalAccept").prop('disabled', false);
  });
}