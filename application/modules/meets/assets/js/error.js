$(document).ready(async () => {
  showError()

  function showError() {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: "You don't have Permission or The Meet is already ended"
    })
    .then(async (result) => {
      if (result.isCanceled) {
        window.location.href = "../../meets";
      } else {
        window.location.href = "../../meets";
      }
    })
  }

})


