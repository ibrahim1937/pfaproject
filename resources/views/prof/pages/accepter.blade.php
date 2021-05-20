@extends('prof.master')

@section('title')
    <title>Demande</title>
@endsection

@section('content')
{{-- --}}
<div class="container-fluid">
    <div class="row shadow" style="overflow: hidden;">
        <div class="card-header py-3">
            <h5 class="m-0 font-weight-bold text-dark text-center">Demandes rectifications acceptées :  </h5>

        </div>
        <div class="row table-responsive" >
                <table class="table table-stripped table-hover w-auto table-bordered display" style=" min-width:100%;overflow: auto;">
                    <thead>
                        <tr>
                            <tr>
                                <th scope="col">Id</th>
                                <th scope="col">Nom d'etdiant</th>
                                <th scope="col">Prenom d'etdiant</th>
                                <th scope="col">Module</th>
                                <th scope="col">Element de module</th>
                                <th scope="col">Commentaire</th>
                                <th scope="col">date acceptation</th>

                                <th scope="col">Etats</th>
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
        let accepter1 = "{{ route('prof.accepter') }}";

    </script>
    <script src="{{ asset('js/prof/accepter.js') }}"></script>
@endsection

