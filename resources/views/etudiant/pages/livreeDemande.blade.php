@extends('etudiant.master')

@section('title')
    <title>Scolarite</title>
@endsection

@section('content')

    <div class="container-fluid">
        <div class="row shadow" style="overflow: hidden;">
            <div class="card-header py-3">
                <h5 class="m-0 font-weight-bold text-dark text-center">Vos demandes scolaire livrée : </h5>

            </div>
            <div class="row table-responsive" >
                    <table class="table table-stripped table-hover w-auto table-bordered display" style=" min-width:100%;overflow: auto;">
                        <thead>
                            <tr>
                                <th scope="col">Id</th>
                                <th scope="col">Demandes</th>
                                <th scope="col">date de Demande</th>
                                <th scope="col">date de Livraison</th>
                                <th scope="col">Etat</th>
                              </tr>
                        </thead>
                        <tbody id="content-demande" style="overflow: auto;">

                        </tbody>
                      </table>
            </div>
        </div>
    </div>






@endsection

@section('scripts')
    <script>
        let livreeDemande1 = "{{ route('etudiant.livreeDemande') }}";

    </script>
    <script src="{{ asset('js/etudiant/livreeDemande.js') }}"></script>
@endsection
