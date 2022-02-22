$(document).ready(async function () {

  $('#change-password').submit(async function (ev) {
    ev.preventDefault();
    $(document.body).css({ 'cursor': 'wait' });

    const formData = {
      oldPassword: btoa($("#old-password").val()),
      newPassword: btoa($("#new-password").val()),
      retypePassword: btoa($("#retype-password").val()),
    };
    console.log(formData)

    await $.ajax({
      type: "POST",
      //set the data type
      dataType: 'json',
      url: '/management/users/change_password',
      cache: false,
      //set body
      data: formData,
      //check this in Firefox browser
      success: function () { showSuccess() },
      error: function (response) { showError(response) }
    });
    return false;
  });

  function showSuccess() {
    $("#old-password").val(null)
    $("#new-password").val(null)
    $("#retype-password").val(null)
    $('#changePasswordModal').modal('hide')
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

  $('#btn-change-password').click(async function () {
    event.stopPropagation();
    event.stopImmediatePropagation();
    console.log('Save')
    $('#changePasswordModal').modal('show')
  })
})