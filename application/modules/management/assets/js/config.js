$(document).ready(async () => {
  $('#form-config').submit(async function (ev) {
    ev.preventDefault();
    $('#confirmModal').modal('show')
  })

  $('#form-confirm').submit(async function (ev) {
    ev.preventDefault();
    $(document.body).css({ 'cursor': 'wait' });

    const formData = {
      email: $("#Email").val(),
      password: btoa($("#Password").val()),
      emailName: $("#Name").val(),
      subject: $("#Subject").val(),
      adminPassword: btoa($("#admin-password").val()),
    };
    console.log(formData)

    await $.ajax({
      type: "POST",
      //set the data type
      dataType: 'json',
      url: './configuration/update',
      cache: false,
      //set body
      data: formData,
      //check this in Firefox browser
      success: () => { showSuccess() },
      error: function (response) { showError(response) }
    });
    return false;
  });

  function showSuccess() {
    $("#admin-password").val(null)
    $('#confirmModal').modal('hide')
    $(document.body).css({ 'cursor': 'default' });
    Swal.fire({
      icon: 'success',
      title: 'Success',
    })
  }

  function showError(data) {
    $(document.body).css({ 'cursor': 'default' });
    Swal.fire({
      icon: 'error',
      title: 'Error',
      html: data.responseJSON.data
    })
  }

  async function initClick() {
    $('#open-confirm-modal').click(async () => {
      event.stopPropagation();
      event.stopImmediatePropagation();
      console.log('Save')
      $('#confirmModal').modal('show')
    })
  }
})