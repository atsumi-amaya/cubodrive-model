<!doctype html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>CuboDrive</title>
    <link rel="stylesheet" href="{!! asset('css/app.css') !!}">
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script src="{!! asset('js/app.js') !!}"></script>
    <link rel="shortcut icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,700;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!--<link href="/stylesheets/dashboard.css" rel="stylesheet">-->
    <script src="https://unpkg.com/feather-icons"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.3/css/dataTables.bootstrap5.min.css">    
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.2/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.2/dist/sweetalert2.all.min.js"></script>
    
    <!--dropzone-->
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>


</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark p-0 navmain" style="background-color: #0084b9;">
            <div class="container-fluid p-0">
                <a>
                    <a title="" href="https://cubodrive.com/es-pe/index.html"><img src="{!! asset('assets/TSNlogo.png') !!}" alt="#" width="371"  height="87"></a>
                </a>
                @if (Auth::check())
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" 
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav me-auto mb-lg-0">
                            @if (Auth::user()->rol != 2)
                                <li class="nav-item p-lg-3 pt-lg-3 logo_section-button">
                                    <a class="fw-bold text-light" href="/docum"><i class="fa-solid fa-box-archive"></i> MI UNIDAD</a>
                                </li>
                            @endif
                            @if (Auth::user()->rol == 2)
                            <li class="nav-item p-lg-3 pt-lg-3 logo_section-button">
                                <a class="fw-bold text-light" href="/docum-shared"><i class="fa-solid fa-users"></i> COMPARTiDO</a>
                            </li>
                            @endif
                            @if (Auth::user()->rol != 2)
                            <li class="nav-item p-lg-3 pt-lg-3 logo_section-button">
                                <a class="fw-bold text-light" href="/docum-bin"><i class="fa-solid fa-trash"></i> PAPELERA</a>
                            </li>
                            @endif
                            @if (Auth::user()->rol != 2)
                                <li class="nav-item p-lg-3 pt-lg-3 logo_section-button">
                                    <a class="fw-bold text-light" href="/user-guest"><i class="fa-solid fa-users-gear"></i> INVITADOS</a>
                                </li>
                            @endif
                            @if (Auth::user()->rol == 0)
                                <li class="nav-item p-lg-3 pt-lg-3 logo_section-button">
                                    <a class="fw-bold text-light" href="/docum-graveyard"><i class="fa-solid fa-eraser"></i> ELIMINADOS</a>
                                </li>
                                <li class="nav-item p-lg-3 pt-lg-3 logo_section-button">
                                    <a class="fw-bold text-light" href="/user"><i class="fa-solid fa-user"></i> USUARIOS</a>
                                </li>
                                <li class="nav-item p-lg-3 pt-lg-3 logo_section-button">
                                    <a class="fw-bold text-light" href="/user-codes"><i class="fa-solid fa-ticket"></i> CODIGOS DE REGISTRO</a>
                                </li>
                            @endif
                        </ul>
                        <ul class="navbar-nav me-0 mb-2 mb-lg-0" style="margin-left: auto">
                            <li class="nav-item dropdown p-lg-3 logo_section-button">
                                <a class="nav-link dropdown-toggle fw-bold text-light" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-circle-user"></i> {{Auth::user()->username}}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    @if(Auth::user()->limite != 2)
                                    <li>
                                        <label class="dropdown-item text-dark disabled">
                                            <i class="fa-solid fa-database"></i> 
                                            @if(Auth::user()->limite != 0)
                                                {{ (round(Auth::user()->uso/1073741824,2)) }}Gb/{{ (Auth::user()->limite/1073741824) }}Gb
                                            @else
                                                ILIMITADO
                                            @endif
                                        </label>
                                    </li>
                                    @endif
                                    @if(Auth::user()->plan == 2)
                                    <li>
                                        <label class="dropdown-item text-dark disabled">
                                            <i class="fa-regular fa-hourglass-half"></i>
                                            <?php 
                                                $date1 = new DateTime(Auth::user()->created_by);
                                                $date2 = new DateTime("now");
                                                $diff = $date1->diff($date2);
                                                echo ' Caduca en ' . (30-$diff->days) . ' dias ';
                                            ?>
                                        </label>
                                    </li>
                                    @endif
                                    <li>
                                        <a class="dropdown-item text-dark" href="/user-pass/{{Auth::user()->id}}"><i class="fa-solid fa-key"></i> Cambiar contraseña</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-dark" href="https://cubodrive.com/es-pe/documentacion/" target="_blank"><i class="fa-solid fa-circle-info"></i> Documentacion</a>
                                    </li>
                                    <li>
                                        <form action="/logout" method="post">
                                            @csrf
                                            <a class="dropdown-item text-danger" onclick="this.closest('form').submit()"><i class="fa-solid fa-right-from-bracket"></i> Cerrar sesion</a>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                @else
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav me-1 mb-2 mb-lg-0" style="margin-left: auto">
                            <li class="nav-link fw-bold text-light">
                                <a class="fw-bold text-light" href="https://cubodrive.com/es-pe/documentacion/" target="_blank"><i class="fa-solid fa-circle-info"></i> DOCUMENTACION</a>
                            </li>
                        </ul>
                    </div>
                @endif
            </div>
        </nav>
    </header>
    @yield('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/js/all.min.js" integrity="sha512-2bMhOkE/ACz21dJT8zBOMgMecNxx0d37NND803ExktKiKdSzdwn+L7i9fdccw/3V06gM/DBWKbYmQvKMdAA9Nw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.3/js/dataTables.bootstrap5.min.js"></script>