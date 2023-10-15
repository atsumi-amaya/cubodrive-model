<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro en Cubodrive</title>
    <link href="css/style.css?v=9" rel="stylesheet" />
    <!-- Agregar los enlaces a Bootstrap CSS y JavaScript -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Agregar enlaces a FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
/* Estilos generales */
body {
  background-color: #f5f5f5;
  font-family: -apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Ubuntu,sans-serif;
}

.cont {
  background-color: #f5f5f5;
  padding: 1rem;
  flex: 1;
}
.card {
  border: 2px solid #ffffff !important; 
  border-radius: 10px;
  box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.1); 
  padding: 20px; 
}
label{
  font-family: -apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Ubuntu,sans-serif;
}
input{
  font-family: -apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Ubuntu,sans-serif;
}
footer {
  background-color: #313436;
  color: white;
  text-align: center;
  padding: 1rem;
}


/* Estilos de navegación */
nav {
  background-color: #0084b9;
  color: white;
  text-align: center;
  box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.1); 
}


nav a {
  text-decoration: none;
  color: #f0f0f0;
  font-family: -apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Ubuntu,sans-serif;
  font-size: 24px;
  font-weight: bold;
}

nav a:hover {
  text-decoration: none;
  color: #c0c0c0;
}

/* Estilos de imagen personalizada */
.custom-image {
  border: none;
  padding: 0;
  float: left;
}

/* Estilos de enlaces en el pie de página */
footer a {
  text-decoration: none;
  color: #f0f0f0;
}

footer a:hover {
  text-decoration: none;
  color: #c0c0c0;
}

/* Estilos del título de la tarjeta */
.card-title {
  background-color: #0084b9;
  padding: 0.7rem;
  border-radius: 4px;
}

/* Estilos del botón de la tarjeta */
.card-button {
  width: 100%;
  margin-bottom: 10px;
}

/* Estilos del enlace de la tarjeta */
.card-link {
  display: block;
  text-align: center;
  color: #007bff;
}

/* Estilos del título H2 */
h2 {
  color: #f0f0f0;
  font-family: -apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Ubuntu,sans-serif;
  font-weight: bold;
}

/* Estilos para enlaces en la barra de navegación y botón de documentación */
.navbar a.navbar-brand,
.navbar a.doc-link,
.doc-button a {
  display: flex;
  align-items: center;
  text-decoration: none;
  color: rgb(255, 255, 255);
  font-weight: bold;
}

/* Estilos para el texto y el icono */
.brand-text,
.doc-text,
.doc-button a .doc-text {
  font-size: 16px;
  margin-right: 10px;
}

/* Estilos para el icono de información */
.fas.fa-info-circle {
  font-size: 20px;
  margin-right: 10px;
}

/* Estilos del botón de documentación */
.doc-button:hover {
  background-color: #0084b7;
}

.doc-button {
  border: none;
  background-color: #0084b9;
  color: #fff;
  padding: 10px;
  border-radius: 10px;
  cursor: pointer;
  transition: background-color 0.3s ease;
  float: right;
  position: relative;
}

/* Media queries para tamaños de pantalla específicos */

/* Pantallas menores de 600px */
@media (max-width: 600px) {
  .doc-button::after {
    font-weight: 900;
    font-size: 20px;
    margin-left: 10px;
  }

  .doc-button span {
    display: none;
  }
}

@media (min-width: 400px) {
  footer {
    position: absolute;
    bottom: 0;
    width: 100%;
  }
  
}

@media (min-height: 826px) {
  footer {
    position: absolute;
    bottom: 0;
    width: 100%;
  }
}

@media (min-height: 599px) and (max-height: 826px) {
  footer {
    position: relative;
  }
}
@media (min-height: 826px) and (max-height: 870px) {
  footer {
    position: relative;
  }
}
/* Pantallas menores de 420px */
@media (max-width: 420px) {
  nav {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
  }

  .custom-image {
    max-width: 80%;
    height: auto;
  }

  .doc-button {
    max-width: calc(20% - 10px);
    margin-left: 10px;
  }

  .doc-button a {
    display: flex;
    align-items: center;
    justify-content: center;
  }
  
}

/* Pantallas mayores de 800px */
@media (min-width: 801px) {
  .doc-button {
    position: absolute;
    right: 20px;
  }
  
}

/* Estilos para los mensajes de error y éxito */
#message-container {
  text-align: center;
  margin-bottom: 10px;
}

.alert {
  margin-top: 10px;
}
/* Estilo para el mensaje de éxito */
#success-message {
  display: none;
  background-color: #dff0d8; 
  color: #3c763d; 
  border: 1px solid #d6e9c6; 
  padding: 10px 15px; 
  margin-top: 10px; 
  border-radius: 5px; 
  text-align: center; 
  font-weight: bold; 
}
.listing.padding-top--24.padding-bottom--24.flex-flex.center-center {
  display: flex;
  justify-content: center;
  align-items: center;
  padding-top: 24px;
  padding-bottom: 24px;
  background-color: #f2f2f2;
}

.listing.padding-top--24.padding-bottom--24.flex-flex.center-center a {
  text-decoration: none;
  color: #333;
  margin: 10px;
  font-weight: bold;
}

.listing.padding-top--24.padding-bottom--24.flex-flex.center-center a:hover {
  color: #f97e2c;
}
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <img class="custom-image" src="{!! asset('assets/TSNlogo.png') !!}" style="border-radius: 4px;">
        <!--<a href="https://cubodrive.com/index.php"><h1>Cubodrive</h1></a>-->
        <button class="doc-button" title="Ir a la guia de usuario">
            <a href="https://cubodrive.com/es-pe/documentacion/" class="doc-link" target="_blank">
              <i class="fas fa-info-circle"></i>
              <span class="doc-text">Documentación</span>
            </a>
        </button>
        
    </nav>

    <div class="cont">
        <div class="card mx-auto" style="max-width: 400px;">
            <div class="card-body">
                <h2 class="card-title text-center" style="font-family: 'Roboto', sans-serif;">CUBODRIVE</h2>
                <form id="registroForm" method="post" action="/user-registe">
                    @csrf
                    <div class="form-group">
                        <label for="username">Nombre de Usuario</label>
                        <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}" placeholder="Ingrese nombre de usuario" required>
                        @error('username')
                            <small style="color: darkred">*{{$message}}</small>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}"  placeholder="cubodrive@cubodrive.com" required>
                        @error('email')
                            <small style="color: darkred">*{{$message}}</small>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" value="{{ old('password') }}" placeholder="********"  required>
                            <div class="input-group-append">
                                <span class="input-group-text toggle-password">
                                    <i class="fa fa-eye-slash" aria-hidden="true"></i>
                                </span>
                            </div>
                        </div>
                        @error('password')
                            <small style="color: darkred">*{{$message}}</small>
                        @enderror
                    </div>
        
                    <div class="form-group">
                        <label for="password1">Repetir contraseña</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password1" name="password1" value="{{ old('password1') }}"  placeholder="********" >
                            <div class="input-group-append">
                                <span class="input-group-text toggle-password">
                                    <i class="fa fa-eye-slash" aria-hidden="true"></i>
                                </span>
                            </div>
                        </div>
                        @error('password1')
                            <small style="color: darkred">*{{$message}}</small>
                        @enderror
                    </div>
                    
                    <div class="form-group p-2" style="border: 1px solid darkgray; border-radius: 10px;">
                        <label for="code">Código de Cliente</label>
                        <input type="text" class="form-control" id="code" name="code">
                        @error('code')
                            <small style="color: darkred">*{{$message}}</small>
                        @enderror
                        <div id="codeHelpBlock" class="form-text">
                            <small><b>Esta seccion no es obligatoria. </b> Si ha contratado uno de nuestros servicios ingrese el codigo de cliente para mejorar su cuenta.</small>
                        </div>
                    </div>

                    <div id="message-container">
                        <div id="error-message" class="alert alert-danger" style="display: none;"></div>
                        <div id="success-message" class="alert alert-success" style="display: none;"></div>
                      </div>
                      
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="aceptar_terminos" required>
                            <label class="custom-control-label" for="aceptar_terminos">Acepto los <a href="https://cubodrive.com/test/politica_privacidad.html" target="_blank" rel="noopener noreferrer">términos</a> y <a href="https://cubodrive.com/test/condiciones_uso.html" target="_blank" rel="noopener noreferrer">condiciones</a></label>
                        </div>
                    </div>
                    
                    
                    <button type="submit" class="btn btn-primary card-button">Registrar Usuario</button>
            </form>
            <a href="https://cubodrive.com/login" class="card-link" title="Ir a inicio de sesion">Ya tengo una cuenta</a>
            </div>
        </div>
    </div>
    <div class="footer-link padding-top--24">
        <div class="listing padding-top--24 padding-bottom--24 flex-flex center-center">
            <a href="https://cubodrive.com/user-registe">©Cubo Drive</a>
            <a href="#">Contacto</a>
            <a href="https://cubodrive.com/test/politica_privacidad.html">Términos & Condiciones</a>
        </div>
    </div>

    <!-- Footer -->
    <!-- <footer>
        &copy; 2023 Cubodrive | Todos los derechos reservados |
        <a href="https://tsn-cloud.com/">TSN Soluciones en la nube</a>
    </footer> -->

    <!-- Agregar enlaces a Bootstrap JS y jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="js/script.js"></script>
    <script>
        $('.toggle-password').click(function () {
            var passwordField = $(this).parent().prev();
            var passwordFieldType = passwordField.attr('type');
            if (passwordFieldType === 'password') {
                passwordField.attr('type', 'text');
                $(this).find('i').removeClass('fa-eye-slash').addClass('fa-eye');
            } else {
                passwordField.attr('type', 'password');
                $(this).find('i').removeClass('fa-eye').addClass('fa-eye-slash');
            }
        });
        
        $(document).ready(function () {
            function showError(message) {
                $("#error-message").text(message).show();
            }
        
            function showSuccess(message) {
                $("#success-message").text(message).show();
            }
        
            function hideMessages() {
                $("#error-message").hide();
                $("#success-message").hide();
            }
        
            function isValidEmail(email) {
                var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
                return emailPattern.test(email);
            }
        
            function isValidUser(user) {
                return user.length > 8;
            }
        
            function isValidPassword(password) {
                var passwordPattern = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
                return passwordPattern.test(password);
            }
        /*
            $("#registroForm").submit(function (e) {
                e.preventDefault();
        
                const password = $("#password").val();
                const password1 = $("#password1").val();
        
                const email = $("#email").val();
                const usuario = $("#username").val();
        
                if (!isValidEmail(email)) {
                    showError("El email ingresado no es válido.");
                    return;
                }
        
                if (!isValidUser(usuario)) {
                    showError("El nombre de usuario debe tener al menos 9 caracteres.");
                    return;
                }
        
                if (!isValidPassword(password)) {
                    showError("La contraseña debe tener al menos 8 caracteres, incluyendo letras, números y símbolos.");
                    return;
                }
                if (password !== password1) {
                    showError("Las contraseñas no coinciden.");
                    return; 
                }
        
                hideMessages();
                showSuccess("Registro exitoso. El formulario se enviará.");
        
                // Envía el formulario
                $("#registroForm")[0].submit();
            });*/
            
            $("#email, #username, #password, #password1").on("input", hideMessages);
        });

    </script>
</body>
</html>

