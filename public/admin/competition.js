function loadReport() {
  $("#div_report").html(
    '<div class="progress"><div class="progress-bar progress-bar-indeterminate" role="progressbar"></div></div>'
  );
  var data = $("#form_filter").serialize();
  $.post($("#btn_filter_data").data("href"), data, function (data, status) {
    $("#div_report").html(data);
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

function submitAddForm() {
  showProgress("add");
  clearMessages("form_add");
  var data = $("#form_add").serialize();
  $.post($("#addModalAccept").data("href"), data, function (data, status) {
    $("#addModal").modal("hide");
    $("#form_submit_message_span").html(data.message);
    $("#form_submit_message").modal("show");
    clearProgress("add");
    loadReport();
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

$("#btn_filter_data").click(function () {
  loadReport();
});

$("#btnFabAdd").click(function () {
  const href = $(this).data("href");
  $.get(href, function (data, status) {
    $("#addModal").html(data).trigger("create");
    $("#addModal").modal("show");
    $("#addModalAccept").click(function () {
      $("#addModalAccept").prop('disabled', true);
      submitAddForm();
    });
  });
});

function loadStaffData(that) {
  const href = $(that).data("href");
  $("#editModal").html("").trigger("create");
  $.get(href, function (data, status) {
    $("#editModal").html(data).trigger("create");
    $("#editModal").modal("show");
    // $("#class_id").select2({ width: "resolve" });
    $("#editModalAccept").click(function () {
      submitStaffData();
    });
  });
}

function submitStaffData() {
  showProgress("activity");
  clearMessages("form_admission_activity");
  var data = $("#form_edit").serialize();
  $.post(
    $("#editModalAccept").data("href"),
    data,
    function (data, status) {
      $("#editModal").modal("hide");
      $("#form_submit_message_span").html(data.message);
      $("#form_submit_message").modal("show");
      clearProgress("activity");
      loadReport();
    }
  ).fail(function (response) {
    $("#form_admission_activity .post_error").each(function () {
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
  });
}

function loadActivityData(that) {
  const href = $(that).data("href");
  $("#div_edit").html("").trigger("create");
  $.get(href, function (data, status) {
    $("#addInwardModal").html(data).trigger("create");
    $("#addInwardModal").modal("show");
    $("#status").select2({ width: "resolve" });
    $("#activity_id").select2({ width: "resolve" });
    $("#proficiency").select2({ width: "resolve" });

    $("#addActivityModalAccept").click(function () {
      submitActivityInward();
    });
  });
}

function submitActivityInward() {
  showProgress("add");
  clearMessages("form_add_inward");
  var data = $("#form_add_inward").serialize();
  $.post($("#addActivityModalAccept").data("href"), data, function (data, status) {
    $("#addInwardModal").modal("hide");
    $("#form_submit_message_span").html(data.message);
    $("#form_submit_message").modal("show");
    clearProgress("add");
    loadReport();
  }).fail(function (response) {
    $("#form_add .post_error").each(function () {
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
  });
}

function viewActivityData(that) {
  const href = $(that).data("href");
  $("#div_edit").html("").trigger("create");
  $.get(href, function (data, status) {
    $("#addInwardModal").html(data).trigger("create");
    $("#addInwardModal").modal("show");
  });
}


function onLevelChange(that) {
  console.log(that.value);
  const href = $(that).data("href") + "/" + that.value;
  console.log(href);
  $("#divOnChangeLevel").html("").trigger("create");
  $.get(href, function (data, status) {
    $("#divOnChangeLevel").html(data).trigger("create");
    $("#district").select2({ width: "resolve" });
  });
}
