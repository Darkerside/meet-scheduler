$(document).ready(async () => {
  $('#reservationdatetime').datetimepicker({ icons: { time: 'far fa-clock' } });

  $('#summernote').summernote({
    height: 300,
    minHeight: null,
    maxHeight: null,
    toolbar: [
      ['style', ['bold', 'italic', 'underline', 'clear']],
      ['font', ['strikethrough', 'superscript', 'subscript']],
      ['fontsize', ['fontsize']],
      ['color', ['color']],
      ['para', ['ul', 'ol', 'paragraph']],
      ['height', ['height']]
    ]
  })

  const externalParticipants = []

  getUserDropdown()
  getDivisionDropdown()
  initButtonAdd()

  $('#select-internal').select2();

  async function success() {
    Swal.fire({
      icon: 'success',
      title: 'Success',
    })
    .then(() => {
      $('#detailModal').modal('hide')
      window.location.href = "../meets";
    })
  }
  
  function showError(data) {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      html: data.responseJSON.data
    })
  }

  async function getDataDropdown(url) {
    const response = $.ajax({
      type: "GET",
      url,
      cache: false,
      success: response => {
        return response
      },
      error: error => {
        showError(error)
      }
    });
    return response
  }

  async function getUserDropdown() {
    const response = await getDataDropdown('../management/users/getAllActive')
    let options = '';
    response.data.forEach(item => {
      options += `<option value="${item.id}">${item.full_name}</option>`
    })
    $("#select-internal").html(options);
  }

  async function getDivisionDropdown() {
    const response = await getDataDropdown('../management/divisions/getAllActive')
    let options = '<option selected disabled>Choose...</option>';
    response.data.forEach(item => {
      options += `<option value='${item.id}'>${item.division_name}</option>`
    })
    $("#meet-division").html(options);
  }

  async function initButtonAdd() {
    $('#add-external-participant').click(async () => {
      const newParticipant = {
        name: $('#external-participant-name').val(),
        email: $('#external-participant-email').val(),
      }
      const regex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/

      if (!!newParticipant.name && !!newParticipant.email && regex.test(newParticipant.email)) {
        console.log(newParticipant)
        $('#external-participant-name').val('')
        $('#external-participant-email').val('')
        if (!externalParticipants.includes(newParticipant)) {
          externalParticipants.push(newParticipant)
          if (externalParticipants.length === 1) $('#external-table tbody').html('')
          $('#external-table tbody').append(`
          <tr id="external-participant-id${externalParticipants.length}">
            <td>${newParticipant.name}</td>
            <td>${newParticipant.email}</td>
            <td><button class='btn btn-sm btn-danger' participant-id="${externalParticipants.length}" id="delete-external-${externalParticipants.length}"><i class='fas fa-trash'></i></button></td>
          </tr>
          `)
          await externalButtonDelete(externalParticipants.length, newParticipant)
        }
      }
    })
  }

  async function externalButtonDelete(id, data) {
    document.querySelector(`#delete-external-${id}`).addEventListener("click", () => {
      if (externalParticipants.includes(data)) {
        externalParticipants.splice(externalParticipants.indexOf(data), 1)
        $(`#external-participant-id${id}`).remove()
      }
      if (externalParticipants.length === 0) {
        $('#external-table tbody').append(`
        <tr>
          <td colspan='3' class='text-center'>No External Participant</td>
        </tr>
        `)
      }
    })
  }

  $('#btn-create-meet').submit(async function (ev) {
    ev.preventDefault();
    const meetBody = $('#summernote').summernote('code')
    const internalParticipants = $('#select-internal').val()
    const data = {
      title: $('#meet-title').val(),
      body: meetBody,
      url: $('#meet-url').val(),
      division_id: $('#meet-division').val(),
      users: JSON.stringify(internalParticipants),
      ext_users: JSON.stringify(externalParticipants),
      timedate: $('#meet-timedate').val(),
    }
    await createMeet(data)
  })
  async function createMeet(data) {
    await $.ajax({
      type: "POST",
      dataType: 'json',
      url: './meets/add',
      cache: false,
      data: data,
      success: (response) => {
        console.log(response)
        Swal.fire({
          showCancelButton: true,
          showConfirmButton: true,
          confirmButtonText: 'Yes',
          cancelButtonText: 'No',
          icon: 'success',
          title: 'Success Create Meet Schedule',
          text: 'Send Meet Reminder via Email to All Participant?',
          showLoaderOnConfirm: true,
          preConfirm: async () => {
            return $.ajax({
              type: "POST",
              url: '../management/mailer/send',
              data: { id: response.data.id },
              cache: false,
            })
              .then(response => {
                console.log(response)
                if (!response.text === 'Success') {
                  throw new Error(response.statusText)
                }
                return true
              })
              .catch(error => {
                Swal.showValidationMessage(
                  `Request failed: ${error}`
                )
              })
          },
          allowOutsideClick: () => !Swal.isLoading()
        })
          .then(async () => {
            await success()
          })

      },
      error: error => { showError(error) }
    });
  }
})