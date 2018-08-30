@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                   <div class="card bg-primary">
                       <div class="card-header">
                           <label>Liste des pages facebook</label>
                       </div>
                       <div class="card-body">
                           <table class="table-striped">
                               <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Catégorie</th>
                                </tr>
                               </thead>
                               <tbody>
                               @if(isset($pages))
                                   @foreach($pages as $page)
                                       <tr>
                                           <td>{{ $page->name }}</td>
                                           <td>{{ $page->categoty }}</td>
                                       </tr>
                                   @endforeach
                               @endif
                               </tbody>
                           </table>
                       </div>
                   </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
