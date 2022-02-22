$(document).ready(async () => {
  initClick()

  function showError(data) {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      html: data.responseJSON.data
    })
  }

  async function success() {
    Swal.fire({
      icon: 'success',
      title: 'Success',
    })
  }

  async function getDataById(id) {
    const url = (window.location.href.includes('division')) ? `../meets/get/${id}` : `./meets/get/${id}`
    await $.ajax({
      type: "GET",
      url,
      cache: false,
      success: function (response) {
        setModalData(id, response.data)
      },
      error: function (response) { showError(response) }
    });
  }

  async function sendMail(id) {
    const url = (window.location.href.includes('division')) ? `../management/mailer/send` : `./management/mailer/send`
    Swal.fire({
      showCancelButton: true,
      showConfirmButton: true,
      confirmButtonText: 'Yes',
      icon: 'question',
      title: 'Send E-Mail',
      text: `Are you sure to send Meeting Information via Email to every participant?`,
      showLoaderOnConfirm: true,
      preConfirm: async () => {
        return $.ajax({
          type: "POST",
          url,
          data: { id },
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
      })
  }

  async function setModalData(id, data) {
    const url = (window.location.href.includes('division')) ? '../meets/edit/' : './meets/edit/'
    console.log(data)

    $('#meeting-title').html(data.title)
    $('#meeting-time').html(`${moment(data.timedate, 'YYYY-MM-DD hh:mm:ss').format('hh:mm')} WIB`)
    $('#meeting-date').html(moment(data.timedate, 'YYYY-MM-DD hh:mm:ss').format('ddd, DD MM YYYY'))
    $('#meeting-division').html(`${data.division_name}`)
    $('#meeting-url').html(`<a href="${data.url}" target="_blank" class=""><i class="fas fa-external-link-alt mr-2"></i>${data.url}</a>`)
    $('#meeting-body').html(data.body)
    $('#btn-send-mail').attr('meet-id', id)

    const editBtn = document.getElementById('btn-edit-meet')
    if (editBtn) $('#btn-edit-meet').attr('href', `${url}${id}`)
    else {
      if (data.created_by.username === $('.userinfo .d-block').first().text()) {
        const btn = `<a class="btn btn-warning mr-3" id="btn-edit-meet" href="${url}${id}"><i class="fas fa-edit mr-2"></i> Edit</a>`
        $('#card-row-button').prepend(btn)
      }
    }


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


