@extends('admin.master')

@section('title')
    <title>Gestion des Etudiants</title>
@endsection

@section('content')

<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class=" mb-2 text-gray-800 text-center">Gestion Etudiants</h1>

    <div class="row shadow formcontainer">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Ajouter un Etudiants :
            </h6>
        </div>
        
        <div class="row error-ajout">
            <div class="col col-sm-11 m-3 errorcontainer" style="position: relative;">

            </div>
            <div class="col-sm-11 col-lg-5">
                <div class="m-3 nomcontainer">
                    <label for="nom">Nom</label>
                    <input type="text" class="form-control" placeholder="Nom..." id="nom">
                </div>
            </div>
            <div class="col-sm-11 col-lg-6">
                <div class="custom-file m-3">
                    <div class="prenomcontainer">
                        <label for="prenom">Prenom</label>
                        <input type="text" class="form-control" placeholder="Prenom..." id="prenom">
                    </div>
                </div>           
            </div>
        </div>
       
        <div class="row">
            <div class="col-sm-11 col-lg-5">
                <div class="custom-file m-3 emailcontainer">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" placeholder="Email" id="email">
                </div>
                            
            </div> 
            <div class="col-sm-11 col-lg-6">
                <div class="custom-file m-3 cincontainer">
                    <label for="cin">CIN</label>
                    <input type="text" class="form-control" placeholder="CIN" id="cin">
                </div>
                            
            </div> 
        </div>
        <div class="form-row">
            <div class="col col-sm-11 m-3 filierecontainer">
                <label for="filiere">Filiere</label>
                    <select name="filiere" id="filiereselect" class="form-select">
                        <option value="">Choisissez une filiere</option>
                        @foreach ($filieres as $f)
                            <option value="{{ $f->id }}">{{ $f->code }}</option>
                        @endforeach

                    </select>
                
            </div>
        </div>
        <div class="form-row m-3">
            <button type="button" id="submitetudiant" class="btn btn-success">Ajouter</button>
        </div>

    </div>
    <br><br>
    <div class="row m-3" style="padding:0px;">
{{-- 
        <div class="col col-sm-11 col-lg-5">
            <div class="card shadow">
                <div class="py-3 card-header">
                    <h3 class="m-0 font-weight-bold text-primary">Exporter les etudiants</h3>
                </div>
                <div class="form-row">
                    <form action="{{ route('admin.export') }}" method="get">
                        <div class="col col-sm-11 m-3 filierecontainerexport">
                            <label for="filiere">Filiere</label>
                                <select name="filiere" id="filiereselectexport" class="form-select">
                                    <option value="0">Tous les filieres</option>
                                    @foreach ($filieres as $f)
                                        <option value="{{ $f->id }}">{{ $f->code }}</option>
                                    @endforeach
            
                                </select>
                            
                        </div>
                    
                        <div class="col col-sm-11 m-3">
                            <button type="submit" id="export" class="btn btn-primary">Exporter</a>
                        </div>
                    </form>
                </div>

            </div>
            
            
        </div> --}}

        <div class="col col-sm-12">
            <div class="card shadow">
                <div class="py-3 card-header">
                    <h3 class="m-0 font-weight-bold text-primary">Ajouter des etudiants par filiere</h3>
                </div>
                {{-- <div class="form-row"> --}}
                    
                    <form id="importform">
                        @csrf
                        <div class="form-row">
                            <div class="col col-sm-11 m-3 mw-90 filierecontainerimport">
                                <label for="filiere">Filiere</label>
                                <select name="filiere" id="filiereselectimport" class="form-select">
                                    <option value="">Tous les filieres</option>
                                    @foreach ($filieres as $f)
                                        <option value="{{ $f->id }}">{{ $f->code }}</option>
                                    @endforeach
            
                                </select>
                                @if(session('errorimport'))
                                    <p>{{ session('errorimport')['filiere'][0]}}</p>
                                @endif

                            </div>
                            <div class="col col-sm-11 m-3 filecontainer">
                                <input type="file" @if(session('error'))class="custom-file-input is-invalid py-2"@else class="custom-file-input py-2"@endif  name="file" id="customFile">
                                <label class="custom-file-label" for="customFile">Choisissez un fichier excel</label>
                            </div>
                        </div>
                    
                    
                        <div class="col col-sm-11">
                            <button type="submit" id="import" class="btn btn-primary m-1">Importer</button>
                            <button type="reset" id="reset" class="btn btn-info m-1">Réinitialiser</button>
                            <a href="{{ route('admin.exportsample') }}" class="btn btn-primary m-1">Exemplaire Excel</a>
                        </div>
                    </form>
                {{-- </div> --}}

            </div>
            
            
        </div>
        
        <br>
    </div>





@endsection




@section('scripts')
<script>
    let gestionurl =  "{{ route('admin.gestionetudiant') }}";
    let exporturl = "{{ route('admin.export') }}";
    let importurl = "{{ route('admin.import') }}";
</script>

<script src="{{ asset('js/admin/etudiant.js') }}"></script>

@endsection