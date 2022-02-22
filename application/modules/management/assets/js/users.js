$(document).ready(async () => {
  initClick()
  getRoleDropdown()
  getDivisionDropdown()
  let submitType = 'create'
  let userId = null

  $("#user-table").DataTable({
    "responsive": true, "lengthChange": false, "autoWidth": false,
  })

  const btnCreate = $('button.btn-create')
  $('#user-table_wrapper .row .col-sm-12').first().html(btnCreate)

  $('#createUsers').submit(async function (ev) {
    ev.preventDefault();
    $(document.body).css({ 'cursor': 'wait' });

    let url = (submitType === 'create') ? './users/add' : `./users/update/${userId}`
    const formData = {
      username: $("#username").val(),
      full_name: $("#full-name").val(),
      email: $("#email").val(),
      roleId: $("#user-role").val(),
      divisionId: $("#user-division").val(),
      password: $("#password").val(),
      retype: $("#retype").val(),
      isActive: $("#is-active").val(),
    };
    console.log(url, formData)

    await $.ajax({
      type: "POST",
      dataType: 'json',
      url,
      cache: false,
      data: formData,
      success: function (response) { showSuccess(response) },
      error: function (response) { showError(response) }
    });
    $(document.body).css({ 'cursor': 'default' });
    await initClick()
    return false;
  });

  function showSuccess(data) {
    $("#username").val(null)
    $("#full-name").val(null)
    $("#user-role").val(null)
    $("#user-division").val(null)
    $("#email").val(null)
    $("#password").val(null)
    $("#retype").val(null)
    $("#is-active").val(null)
    $('#createModal').modal('hide')
    Swal.fire({
      icon: 'success',
      title: 'Success',
    })
    let tbody = ''
    data.data.forEach(item => {
      tbody += `<tr>
        <td>${item.id}</td>
        <td>${item.username}</td>
        <td>${item.email}</td>
        <td>${item.role_name}</td>
        <td>${item.division_name}</td>
        <td>${(item.is_active === '1') ? 'Active' : 'Non-Active'}</td>
        <td>
        <button class='btn btn-sm btn-success mr-1 btn-view' user-data='${item.id}'><i class='fas fa-eye'></i></button>
        <button class='btn btn-sm btn-primary mr-1 btn-edit' user-data='${item.id}'><i class='fas fa-edit'></i></button>
        <button class='btn btn-sm btn-danger btn-delete' user-data='${item.id}'><i class='fas fa-trash'></i></button>
        </td>
      </tr>
      `
    });
    $('#user-table tbody').html(tbody)
  }

  function showError(data) {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      html: data.responseJSON.data
    })
  }

  async function deleteRow(id) {
    await $.ajax({
      type: "POST",
      dataType: 'json',
      url: `./users/delete/${id}`,
      cache: false,
      success: function (response) { showSuccess(response) },
      error: function (response) { showError(response) }
    });
    $(document.body).css({ 'cursor': 'default' });
    await initClick()
    return false;
  }

  async function getDataById(id) {
    await $.ajax({
      type: "GET",
      dataType: 'json',
      url: `./users/get/${id}`,
      cache: false,
      success: function (response) {
        $("#username").val(response.data.username)
        $("#full-name").val(response.data.full_name)
        $("#email").val(response.data.email)
        $("#user-role").val(response.data.role_id)
        $("#user-division").val(response.data.division_id)
        $("#password").val('********')
        $("#retype").val('********')
        $("#is-active").val(response.data.is_active)
        $('#user-created').val(moment.unix(response.data.created_at).format('DD MMM YYYY'))
        $(document.body).css({ 'cursor': 'default' });
        $('#createModal').modal('show')
      },
      error: function (response) { showError(response) }
    });
    return false;
  }

  async function getRoleDropdown() {
    $.ajax({
      type: "GET",
      dataType: 'json',
      url: './roles/getAll',
      cache: false,
      success: function (response) {
        let options = '<option selected disabled>Choose...</option>';
        response.data.forEach(item => {
          options += `<option value='${item.id}'>${item.role_name}</option>`
        })
        $("#user-role").html(options);
      },
      error: function (response) { showError(response) }
    });
    return false;
  }

  async function getDivisionDropdown() {
    $.ajax({
      type: "GET",
      dataType: 'json',
      url: './divisions/getAll',
      cache: false,
      success: function (response) {
        let options = '<option selected disabled>Choose...</option>';
        response.data.forEach(item => {
          options += `<option value='${item.id}'>${item.division_name}</option>`
        })
        $("#user-division").html(options);
      },
      error: function (response) { showError(response) }
    });
    return false;
  }

  async function initClick() {
    const btnDelete = document.querySelectorAll('.btn-delete')
    btnDelete.forEach(item => {
      item.addEventListener('click', async () => {
        $(document.body).css({ 'cursor': 'wait' });
        console.log('delete')
        Swal.fire({
          showDenyButton: true,
          showCancelButton: true,
          showConfirmButton: false,
          denyButtonText: 'Yes',
          icon: 'question',
          title: 'Delete Data',
          text: `Are you sure deleting data id ${item.getAttribute('user-data')}`,
        })
          .then((result) => {
            if (result.isDenied) {
              deleteRow(item.getAttribute('user-data'))
            } else {
              $(document.body).css({ 'cursor': 'default' });
            }
          })
      })
    })

    const btnDetail = document.querySelectorAll('.btn-view')
    btnDetail.forEach(item => {
      item.addEventListener('click', async () => {
        $(document.body).css({ 'cursor': 'wait' });
        console.log('view')
        usersId = item.getAttribute('user-data')
        $("#username").attr('disabled', true)
        $("#full-name").attr('disabled', true)
        $("#email").attr('disabled', true)
        $("#user-role").attr('disabled', true)
        $("#user-division").attr('disabled', true)
        $("#password").attr('disabled', true)
        $("#retype").attr('disabled', true)
        $("#is-active").attr('disabled', true)
        $('#createModalLabel').html('View user')
        $('#createUsers .btn-submit').addClass('d-none')
        getDataById(item.getAttribute('user-data'))
      })
    })

    const btnEdit = document.querySelectorAll('.btn-edit')
    btnEdit.forEach(item => {
      item.addEventListener('click', async () => {
        $(document.body).css({ 'cursor': 'wait' });
        console.log('view')
        submitType = 'update'
        userId = item.getAttribute('user-data')
        await getDataById(item.getAttribute('user-data'))
        $('#createModalLabel').html('Edit user')
        $('#createUsers .btn-submit').addClass('d-none')
        $("#username").attr('disabled', false)
        $("#full-name").attr('disabled', false)
        $("#email").attr('disabled', false)
        $("#user-role").attr('disabled', false)
        $("#user-division").attr('disabled', false)
        $("#password").attr('disabled', true)
        $("#retype").attr('disabled', true)
        $("#is-active").attr('disabled', false)
        $('#createUsers .btn-submit').html('Update').removeClass('d-none')
      })
    })

    $('.btn-create').click(() => {
      event.stopPropagation();
      event.stopImmediatePropagation();
      console.log('create')
      submitType = 'create'
      getRoleDropdown()
      getDivisionDropdown()
      $('#createModalLabel').html('Create user')
      $('#createUsers .btn-submit').html('Create').removeClass('d-none')
      $("#username").attr('disabled', false).val(null)
      $("#full-name").attr('disabled', false).val(null)
      $("#email").attr('disabled', false).val(null)
      $("#user-role").attr('disabled', false).html("<option value='null' disabled>Loading...</option>").val('null')
      $("#user-division").attr('disabled', false).html("<option value='null' disabled>Loading...</option>").val('null')
      $("#password").attr('disabled', false).val(null)
      $("#retype").attr('disabled', false).val(null)
      $("#is-active").attr('disabled', false).val('null')
      $('#user-created').val(moment().format('DD MMM YYYY'))
      $('#createModal').modal('show')
    })
  }
})