$(document).ready(async () => {
  initClick()
  let submitType = 'create'
  let divisionId = null

  $("#division-table").DataTable({
    "responsive": true, "lengthChange": false, "autoWidth": false,
  })

  const btnCreate =  $('button.btn-create')
  $('#division-table_wrapper .row .col-sm-12').first().html(btnCreate)

  $('#createDivision').submit(async function (ev) {
    ev.preventDefault();
    $(document.body).css({ 'cursor': 'wait' });

    let url = (submitType === 'create') ? './divisions/add' : `./divisions/update/${divisionId}`
    const formData = {
      name: $("#name").val(),
      isActive: $("#is-active").val(),
    };

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
    $("#name").val(null)
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
        <td>${item.division_name}</td>
        <td>${(item.is_active === '1') ? 'Active' : 'Non-Active'}</td>
        <td>
        <button class='btn btn-sm btn-success mr-1 btn-view' division-data='${item.id}'><i class='fas fa-eye'></i></button>
        <button class='btn btn-sm btn-primary mr-1 btn-edit' division-data='${item.id}'><i class='fas fa-edit'></i></button>
        <button class='btn btn-sm btn-danger btn-delete' division-data='${item.id}'><i class='fas fa-trash'></i></button>
        </td>
      </tr>
      `
    });
    $('#division-table tbody').html(tbody)
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
      url: `./divisions/delete/${id}`,
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
      url: `./divisions/get/${id}`,
      cache: false,
      success: function (response) {
        $("#name").val(response.data.division_name)
        $("#is-active").val(response.data.is_active)
        $(document.body).css({ 'cursor': 'default' });
        $('#createModal').modal('show')
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
          text: `Are you sure deleting data id ${item.getAttribute('division-data')}`,
        })
          .then((result) => {
            if (result.isDenied) {
              deleteRow(item.getAttribute('division-data'))
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
        divisionId = item.getAttribute('division-data')
        $("#name").attr('disabled', true)
        $("#is-active").attr('disabled', true)
        $('#createModalLabel').html('View Division')
        $('#createDivision .btn-submit').addClass('d-none')
        await getDataById(item.getAttribute('division-data'))
      })
    })

    const btnEdit = document.querySelectorAll('.btn-edit')
    btnEdit.forEach(item => {
      item.addEventListener('click', async () => {
        $(document.body).css({ 'cursor': 'wait' });
        console.log('view')
        submitType = 'update'
        userId = item.getAttribute('division-data')
        await getDataById(item.getAttribute('division-data'))
        $('#createModalLabel').html('Edit Division')
        $('#createDivision .btn-submit').addClass('d-none')
        $("#name").attr('disabled', false)
        $("#is-active").attr('disabled', false)
        $('#createDivision .btn-submit').html('Create').removeClass('d-none')
      })
    })

    $('.btn-create').click(() => {
      event.stopPropagation();
      event.stopImmediatePropagation();
      console.log('create')
      submitType = 'create'
      $('#createModalLabel').html('Create Division')
      $('#createDivision .btn-submit').html('Create').removeClass('d-none')
      $("#name").attr('disabled', false).val(null)
      $("#is-active").attr('disabled', false).val('null')
      $('#createModal').modal('show')
    })
  }
})