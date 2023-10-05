function loadReport() {
  $("#div_report").html(
      '<div class="progress"><div class="progress-bar progress-bar-indeterminate" role="progressbar"></div></div>'
  );
  // var data = $("#form_filter").serialize();
  // $.post($("#btn_filter_data").data("href"), data, function (data, status) {
  //     $("#div_report").html(data);
  // });
    $.get($("#btn_filter_data").data("href"), function (data, status) {
      $("#div_report").html(data);
      // $("#divOnChangeLevel").html(data).trigger("create");
      // $("#district").select2({ width: "resolve" });
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
          submitAddForm();
      });
  });
};

