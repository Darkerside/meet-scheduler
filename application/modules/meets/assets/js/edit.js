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

  initButtonAdd()
  // initButtonDelete()
  initExternalParticipants()

  $('#select-internal').select2();

  async function success() {
    Swal.fire({
      icon: 'success',
      title: 'Success',
    })
  }

  function showError(data) {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      html: data.responseJSON.data
    })
  }

  // async function initButtonDelete() {
  //   const btnDelete = document.querySelectorAll('.btn-delete')
  //   if (btnDelete.length > 0) {
  //     btnDelete.forEach(btn => {
  //       btn.addEventListener('click', async () => {
          
  //       })
  //     });
  //   }
  // }

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
          <tr class='external-participant' id="external-participant-${newParticipant.name.replace(' ', '_')}">
            <td>${newParticipant.name}</td>
            <td>${newParticipant.email}</td>
            <td><span class='btn btn-sm btn-danger' external-member="${newParticipant.name.replace(' ', '_')}" id="delete-external-${newParticipant.name.replace(' ', '_')}"><i class='fas fa-trash'></i></span></td>
          </tr>
          `)
          await externalButtonDelete(newParticipant.name, newParticipant)
        }
      }
    })
  }

  async function initExternalParticipants() {
    const rowParticipants = document.querySelectorAll('.external-participant')
    if (rowParticipants.length > 0) {
      rowParticipants.forEach(item => {
        const data = {
          name: item.children[0].innerText,
          email: item.children[1].innerText
        }
        externalParticipants.push(data)
        const btn = document.querySelector(`#delete-external-${item.children[0].innerText.replace(' ', '_')}`)
        btn.addEventListener('click', async () => {
          if (externalParticipants.includes(data)) {
            externalParticipants.splice(externalParticipants.indexOf(data), 1)
            $(`#external-participant-${item.children[0].innerText.replace(' ', '_')}`).remove()
          }
          if (externalParticipants.length === 0) {
            $('#external-table tbody').append(`
            <tr>
              <td colspan='3' class='text-center'>No External Participant</td>
            </tr>
            `)
          }
        })
      })

    }
  }

  async function externalButtonDelete(id, data) {
    document.querySelector(`#delete-external-${id.replace(' ', '_')}`).addEventListener("click", () => {
      if (externalParticipants.includes(data)) {
        externalParticipants.splice(externalParticipants.indexOf(data), 1)
        $(`#external-participant-${id.replace(' ', '_')}`).remove()
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

  $('#btn-update-meet').submit(async function (ev) {
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
    console.log(data)
    await updateMeet(data)
  })
  async function updateMeet(data) {
    const meetId = window.location.pathname.substring(window.location.pathname.lastIndexOf('/') + 1)
    await $.ajax({
      type: "POST",
      dataType: 'json',
      url: `../update/${meetId}`,
      cache: false,
      data: data,
      success: () => {
        Swal.fire({
          showCancelButton: true,
          showConfirmButton: true,
          confirmButtonText: 'Yes',
          cancelButtonText: 'No',
          icon: 'success',
          title: 'Success Update Meet Schedule',
          text: 'Send New Meet Reminder via Email to All Participant?',
          showLoaderOnConfirm: true,
          preConfirm: async () => {
            return $.ajax({
              type: "POST",
              url: '../../management/mailer/send',
              data: { id: meetId },
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
            $('#detailModal').modal('hide')
            window.location.href = "../../meets";
          })
      },
      error: error => { showError(error) }
    });
  }
})