$(document).ready(() => {
  initClick()

  function showError(data) {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      html: data.responseJSON.data
    })
  }

  async function getDataById(id) {
    await $.ajax({
      type: "GET",
      url: `./meets/get/${id}`,
      cache: false,
      success: function (response) {
        setModalData(id, response.data)
      },
      error: function (response) { showError(response) }
    });
  }

  async function sendMail(id) {
    Swal.fire({
      showCancelButton: true,
      showConfirmButton: true,
      confirmButtonText: 'Yes',
      icon: 'question',
      title: 'Send E-Mail',
      text: `Are you sure to send Meeting Information via Email to every participant?`,
    })
      .then(async (result) => {
        if (result.isConfirmed) {
          console.log('sended')
          await $.ajax({
            type: "POST",
            url: './management/mailer/send',
            data: { id },
            cache: false,
          })
            .then(async response => {
              await success()
              $('#detailModal').modal('hide')
              // console.log(response)
            })
            .catch(error => {
              // console.log(error)
            })

        } else {
          console.log('canceled')
        }
      })
  }

  async function success() {
    Swal.fire({
      icon: 'success',
      title: 'Success',
    })
  }

  async function setModalData(id, data) {

    $('#meeting-title').html(data.title)
    $('#meeting-time').html(`${moment(data.timedate, 'YYYY-MM-DD hh:mm:ss').format('hh:mm')} WIB`)
    $('#meeting-date').html(moment(data.timedate, 'YYYY-MM-DD hh:mm:ss').format('ddd, DD MM YYYY'))
    $('#meeting-division').html(`${data.division_name}`)
    $('#meeting-url').html(`<a href="${data.url}" target="_blank" class=""><i class="fas fa-external-link-alt mr-2"></i>${data.url}</a>`)
    $('#meeting-body').html(data.body)
    $('#btn-send-mail').attr('meet-id', id)


    let tbody = ''
    let extTbody = ''
    let countData = 0
    let extCountData = 0
    data.users.forEach(item => {
      countData += 1
      tbody += `<tr>
        <td>${countData}</td>
        <td>${item.full_name}</td>
        <td>${item.division_name}</td>
      </tr>
      `
    });

    $('#internal-user-table tbody').html(tbody)

    if (!!data.ext_users) {
      data.ext_users.forEach(item => {
        extCountData += 1
        extTbody += `<tr>
          <td>${extCountData}</td>
          <td>${item.name}</td>
          <td>${item.email}</td>
        </tr>
        `
      });

      $('#external-user-table tbody').html(extTbody)
    }
  }

  async function initClick() {
    const btnDetail = document.querySelectorAll('.btn-detail')
    btnDetail.forEach(item => {
      item.addEventListener('click', async () => {
        console.log('view')
        $('#detailModal').modal('show')

        getDataById(item.getAttribute('meet-id'))
      })
    })

    $('#btn-send-mail').click(async () => {
      console.log('send mail')
      await sendMail($('#btn-send-mail').attr('meet-id'))
    })
  }
})