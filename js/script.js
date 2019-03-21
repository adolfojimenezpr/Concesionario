$(document).ready(function() {
  $("#boton_mensaje").on("click", function(event) {
    $("#mensaje").toggle();
  });
  $("button.alta_usuario").on("click", function(event) {
    $("form.alta_usuario").toggle();
  });
  $("form.alta_usuario").on("submit", function(event) {
    altaUsuario();
    return false;
  });
  $("#boton_enviar").on("click", function(event) {
    enviarMensaje();
    return false;
  });
  $(".logueado_usuario").on("click", function(event) {
    desloguear(event);
  });
  $(".bloqueado_usuario").on("click", function(event) {
    bloquear(event);
  });
  $(".baja_usuario").on("click", function(event) {
    darDeBaja(event);
  });
  $(".leido").on("click", function(event) {
    marcarLeido(event);
  });
  $("#leidos").on("change", function(event) {
    filtrarMensajes(true);
  });
  $("#no_leidos").on("change", function(event) {
    filtrarMensajes(false);
  });
  $(".botonConfirmar").on("click", function(event) {
    confirmarProducto(event);
  });

  $("#confirmados").on("change", function(event) {
    filtrarProductos(true);
  });
  $("#no_confirmados").on("change", function(event) {
    filtrarProductos(false);
  });

  $("#uploadform").on("submit", function(event) {
    if (document.getElementById("xml").files.length == 0)
      alert("Debe subir un archivo XML para actualizar sus productos");
    else {
      altaProductos(event);
      return false;
    }
  });

  $("form.filtrarConcesionario").on("submit", function(event) {
    filtrarPedidos();
    return false;
  });

  $("#botonCrearPedido").on("click", function(event) {
    crearPedidos();
    return false;
  });

  $("form.modificarPedidoConcesionario").on("submit", function(event) {
    modificarPedidos(event);
    return false;
  });

});



// admin
function filtrarMensajes(leidos) {
  var post = new XMLHttpRequest();
  post.onreadystatechange = function() {
    if (this.readyState == 4) {
      if (this.status == 200) {
        $("#admin_mensajes tbody").html(this.responseText);
        if (leidos)
          $("#admin_mensajes caption").text("MENSAJES");
        else
          $("#admin_mensajes caption").text("MENSAJES NO LEÍDOS");
        $(".leido").prop("onclick", null).off("click");
        $(".leido").on("click", function() {
          marcarLeido(event);
        });
      } else
        alert("Error al filtrar mensajes");
    }
  };
  post.open("POST", "../scripts/adminFiltrarMensajes.php", true);
  post.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  post.send("leidos=" + leidos);
}

// admin
function marcarLeido(event) {
  if ($(event.target).get(0).innerText == "No") {
    if (confirm("¿Marcar mensaje como leído?")) {
      var id = $(event.target).siblings(".id_mensaje").get(0).innerText;
      var post = new XMLHttpRequest();
      post.onreadystatechange = function() {
        if (this.readyState == 4) {
          if (this.status == 200) {
            $(event.target).get(0).innerText = "Sí";
          } else
            alert("Error al marcar mensaje como leído");
        }
      };
      post.open("POST", "../scripts/adminMensajeLeido.php", true);
      post.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      post.send("id=" + id);
    }
  }
}
//Proveedor
function confirmarProducto(event) {

  var idProducto = $(event.target).parent().siblings(".idProductoConfirmado").text();
  var idPedido = $(event.target).parent().siblings(".idPedidoConfirmado").text();

  $(event.target).attr("disabled", true);
  var post = new XMLHttpRequest();
  post.onreadystatechange = function() {
    if (this.readyState == 4) {
      if (this.status == 200) {
        alert("Producto confirmado correctamente.");

      } else
        alert("Error al confirmar producto.");
    }
  };
  post.open("POST", "../scripts/proveedorConfirmarProducto.php", true);
  post.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  post.send("idProducto=" + idProducto + "&idPedido=" + idPedido);

}

// admin
function altaUsuario() {
  var post = new XMLHttpRequest();
  post.onreadystatechange = function() {
    if (this.readyState == 4) {
      if (this.status == 200) {
        alert("Usuario dado de alta");
        $("form.alta_usuario").children().not("[type='submit']").val("");
        $("form.alta_usuario").toggle();
        $("#admin_usuarios tbody").append(this.responseText);
        $("#admin_usuarios .tabla_vacia").parent().remove();
        $(".logueado_usuario").prop("onclick", null).off("click");
        $(".logueado_usuario").on("click", function() {
          desloguear(event);
        });
        $(".bloqueado_usuario").prop("onclick", null).off("click");
        $(".bloqueado_usuario").on("click", function() {
          bloquear(event);
        });
        $(".baja_usuario").prop("onclick", null).off("click");
        $(".baja_usuario").on("click", function() {
          darDeBaja(event);
        });
      } else
        alert("Error al dar de alta usuario");
    }
  };
  post.open("POST", "../scripts/adminAltaUsuario.php", true);
  post.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  post.send($("form.alta_usuario").serialize());
}

// admin
function bloquear(event) {
  if ($(event.target).get(0).innerText == "No") {
    var msgConfirm = "¿Bloquear al usuario?";
    var msgSuccess = "Usuario bloqueado";
    var msgError = "Error al bloquear usuario";
    var msgAct = "Sí";
    var bloqueado = 1;
  } else if ($(event.target).get(0).innerText == "Sí") {
    var msgConfirm = "¿Desbloquear al usuario?";
    var msgSuccess = "Usuario desbloqueado";
    var msgError = "Error al desbloquear usuario";
    var msgAct = "No";
    var bloqueado = 0;
  }
  if (confirm(msgConfirm)) {
    var id = $(event.target).siblings(".id_usuario").get(0).innerText;
    var post = new XMLHttpRequest();
    post.onreadystatechange = function() {
      if (this.readyState == 4) {
        if (this.status == 200) {
          alert(msgSuccess);
          $(event.target).get(0).innerText = msgAct;
        } else
          alert(msgError);
      }
    };
    post.open("POST", "../scripts/adminBloquearUsuario.php", true);
    post.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    post.send("id=" + id + "&bloqueado=" + bloqueado);
  }
}

// admin
function darDeBaja(event) {
  if ($(event.target).get(0).innerText == "No") {
    var msgConfirm = "¿Dar de baja al usuario?";
    var msgSuccess = "Usuario dado de baja";
    var msgError = "Error al dar de baja al usuario";
    var msgAct = "Sí";
    var dadoDeBaja = 1;
  } else if ($(event.target).get(0).innerText == "Sí") {
    var msgConfirm = "¿Volver a dar de alta al usuario?";
    var msgSuccess = "Usuario dado de alta";
    var msgError = "Error al dar de alta al usuario";
    var msgAct = "No";
    var dadoDeBaja = 0;
  }
  if (confirm(msgConfirm)) {
    var id = $(event.target).siblings(".id_usuario").get(0).innerText;
    var post = new XMLHttpRequest();
    post.onreadystatechange = function() {
      if (this.readyState == 4) {
        if (this.status == 200) {
          alert(msgSuccess);
          $(event.target).get(0).innerText = msgAct;
        } else
          alert(msgError);
      }
    };
    post.open("POST", "../scripts/adminBajaUsuario.php", true);
    post.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    post.send("id=" + id + "&dadoDeBaja=" + dadoDeBaja);
  }
}

// admin
function desloguear(event) {
  if ($(event.target).get(0).innerText == "Sí") {
    if (confirm("¿Desloguear al usuario?")) {
      var id = $(event.target).siblings(".id_usuario").get(0).innerText;
      var post = new XMLHttpRequest();
      post.onreadystatechange = function() {
        if (this.readyState == 4) {
          if (this.status == 200) {
            alert("Usuario deslogueado");
            $(event.target).get(0).innerText = "No";
          } else
            alert("Error al desloguear usuario");
        }
      };
      post.open("POST", "../scripts/adminDesloguearUsuario.php", true);
      post.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      post.send("id=" + id);
    }
  }
}

function desplegar(event, id) {
  $("#" + id).toggle();
}

function enviarMensaje(asunto, descripcion) {
  var asunto = document.getElementById("asunto").value;
  var descripcion = document.getElementById("descripcion").value;

  if (!descripcion) {
    alert("Es necesario rellenar la descripción del mensaje");
  } else {
    $.ajax({
      type: 'POST',
      url: 'scripts/guardarMensaje.php',
      data: 'asunto=' + asunto + '&descripcion=' + descripcion,
      cache: false,
      success: function() {
        alert("Mensaje enviado");
        document.getElementById("asunto").value = "";
        document.getElementById("descripcion").value = "";
      },
      error: function(postRequest, textStatus, errorThrown) {
        alert("Error al enviar el mensaje");
      }
    });
  }
}

// proveedor
function altaProductos(event) {
  event.preventDefault();
  var f = $(this);
  var formData = new FormData($("#uploadform").get(0));
  $.ajax({
    url: "../scripts/proveeAltaProductos.php",
    type: "post",
    dataType: "html",
    data: formData,
    cache: false,
    contentType: false,
    processData: false,
    success: function(text) {
      alert("Productos actualizados");
      $("#p1").append(text);
    },
    error: function() {
      alert("Error al actualizar los productos");
    }
  });
}

//proveedor

function filtrarProductos(confirmados) {

  var post = new XMLHttpRequest();
  post.onreadystatechange = function() {
    if (this.readyState == 4) {
      if (this.status == 200) {
        $("#prov_ver_confirmarProds tbody").html(this.responseText);
        if (confirmados)
          $("#prov_ver_confirmarProds caption").text("TODOS LOS PRODUCTOS PEDIDOS");
        else
          $("#prov_ver_confirmarProds caption").text("PRODUCTOS PEDIDOS SIN CONFIRMAR");
        $(".botonConfirmar").prop("onclick", null).off("click");
        $(".botonConfirmar").on("click", function(event) {
          confirmarProducto(event);
        });
      } else
        alert("Error al filtrar productos");
    }
  };
  post.open("POST", "../scripts/proveedorFiltrarProductos.php", true);
  post.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  post.send("confirmados=" + confirmados);

}

//Concesionario

function filtrarPedidos() {
  var post = new XMLHttpRequest();
  post.onreadystatechange = function() {
    if (this.readyState == 4) {
      if (this.status == 200) {
        $("#p3 tbody").html(this.responseText);
      } else
        alert("Error al filtrar pedidos");
    }
  };
  post.open("POST", "../scripts/concesionarioFiltrarPedidos.php", true);
  post.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  post.send($("form.filtrarConcesionario").serialize());
}

//Concesionario

function crearPedidos(formData) {
  var post = new XMLHttpRequest();
  post.onreadystatechange = function() {
    if (this.readyState == 4) {
      if (this.status == 200) {
        alert(this.responseText);
      } else
        alert("Error al crear el pedido");
    }
  };
  post.open("POST", "../scripts/concesionarioCrearPedido.php", true);
  post.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  post.send($("form.crearPedidoConcesionario").serialize());
}

//Concesionario

function modificarPedidos(event) {
  var post = new XMLHttpRequest();
  post.onreadystatechange = function() {
    if (this.readyState == 4) {
      if (this.status == 200) {
        alert("Pedido modificado");
      } else
        alert("Error al modificar pedido");
    }
  };
  post.open("POST", "../scripts/concesionarioModificarPedido.php", true);
  post.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  var id = $(event.target).attr('id');
  id = id.substring(1, id.length);
  post.send($("form.modificarPedidoConcesionario#f" + id).serialize() + "&id=" + id);
}
