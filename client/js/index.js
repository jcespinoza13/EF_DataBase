$(function(){
  var l = new Login();
})


class Login {
  constructor() {
    this.submitEvent()
  }

  submitEvent(){
    $('form').submit((event)=>{
      event.preventDefault()
      this.sendForm()
    })
  }

  sendForm(){
    let form_data = new FormData();
    form_data.append('username', $('#user').val())
    form_data.append('password', $('#password').val())
    $.ajax({
      url: '../server/check_login.php',
      dataType: "json",
      cache: false,
      processData: false,
      contentType: false,
      data: form_data,
      type: 'post',
      success: function(php_response){
          if (php_response.acceso == "concedido") {;
            alert('Bienvenido '+ php_response.id + ' ' + php_response.user );
            window.location.href = '../client/main.html';
            // var nombre =   'Agenda: ' + php_response.user
            // var ventana = document.getElementsByClassName('identificador');
            // ventana.innerHtml = nombre;
            // alert(nombre);
            // $('identificador').text(nombre);
          }else {
            alert(php_response.motivo);
          }
      },
      error: function (xhr, ajaxOptions, thrownError) {
       alert(xhr.status + ' ' + thrownError );
      }
    })
  }
}
