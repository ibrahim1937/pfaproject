
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        @yield('title')

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

            <!-- Custom fonts for this template-->
        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('css/loader.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('fontawesome-free/css/all.css') }}" rel="stylesheet" type="text/css">
        
        <link
            href="{{ asset('css/css.css') }}"
            rel="stylesheet">

        <!-- Custom styles for this template-->
        <link href="{{ asset('css/sb-admin-2.css') }}" rel="stylesheet">
        <link href="{{ asset('css/main.css') }}" rel="stylesheet">
        <link href="{{ asset('css/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
        <script src="{{ asset('js/chart.js') }}"></script>
        <link href="{{ asset('css/admin.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('css/dropzone.css') }}" rel="stylesheet" type="text/css">

    </head>
    <body id="page-top">

         <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <div class="sidebar-brand d-flex align-items-center">
                <div class="sidebar-brand-icon">
                    <img src="{{ file_exists(public_path().'/storage/images/'.Auth::user()->photo) ? asset('storage/images/'. Auth::user()->photo) : asset('storage/images/user.png') }}" alt="userimage" width="50" height="50" style="float: left; border-radius:50%">
                </div>
                <div class="sidebar-brand-text mx-3">
                    <span style="font-size: 12px;margin: 5px;">{{ Auth::user()->nom }} {{ Auth::user()->prenom }}</span>
                </div>
            </div>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-home fa-fw "></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Gestion
            </div>


            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseOne"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-user-graduate"></i>
                    <span>Étudiant</span>
                </a>
                <div id="collapseOne" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h5 class="collapse-header text-center">Gestion Étudiant:</h5>
                        <a class="collapse-item" href="{{ route('admin.addetudiant') }}">Ajouter des étudiants</a>
                        <a class="collapse-item" href="{{ route('admin.listeetudiant') }}">Liste des étudiants</a>
                    </div>
                </div>
                
            </li>


            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseThree"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-user-tie"></i>
                    <span>Fonctionnaire</span>
                </a>
                <div id="collapseThree" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h5 class="collapse-header text-center">Gestion Fonctionnaires:</h5>
                        <a class="collapse-item" href="{{ route('admin.professeur') }}">Professeur</a>
                        <a class="collapse-item" href="{{ route('admin.agentscolarite') }}">Agent de scolarité</a>
                        <a class="collapse-item" href="{{ route('admin.agentexamen') }}">Agent d'examen</a>
                    </div>
                </div>
                
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.filiere') }}">
                    <i class="fas fa-graduation-cap"></i>
                    <span>Filière</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.module') }}">
                    <i class="fas fa-chalkboard"></i>
                    <span>Module</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.element') }}">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <span>Élément</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Historique
            </div>

            <!-- Nav Item - Logs -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.logspages') }}">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Registre</span></a>
            </li>


            

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
           
        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->nom }} {{ Auth::user()->prenom }}</span>
                                <img class="img-profile rounded-circle"
                                    src="{{ file_exists(public_path().'/storage/images/'.Auth::user()->photo) ? asset('storage/images/'. Auth::user()->photo) : asset('storage/images/user.png') }}">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="{{ route('admin.profilepage') }}">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <div class="dropdown-divider"></div>
                                <form method="post", action="{{ route('logout') }}">
                                    @csrf
                                    <button class="dropdown-item" type="submit" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                    </button>
                            </form>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    @yield('content')

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2020</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->
    
        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

        <div class="loader-wrapper">
            <span class="loader"><span class="loader-inner"></span></span>
        </div>
    
        
        <!-- Bootstrap core JavaScript-->
        <script src="{{ asset('js/jquery-3.6.0.js') }}"></script>
        <script src="{{ asset('js/dataTables.bootstrap4.js') }}"></script>
        <script src="{{ asset('js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap.bundle.js') }}"></script>
        <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('js/dataTables.bootstrap5.min.js') }}"></script>
    
    
        <!-- Core plugin JavaScript-->
        <script src="{{ asset('js/jquery.easing.js') }}"></script>
        <script src="{{ asset('js/dropzone.js') }}"></script>
        
        <!-- Custom scripts for all pages-->
        

        <script src="{{ asset('js/sb-admin-2.js') }}"></script>

        @yield('scripts')
        <script src="{{ asset('js/all.js') }}"></script>

        
        
    
    
    </body>
</html>