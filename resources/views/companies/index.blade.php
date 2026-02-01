<h1>Empresas</h1>

<a href="{{ route('companies.create') }}">Nova Empresa</a>

<ul>
@foreach($companies as $company)
    <li>{{ $company->name }}</li>
@endforeach
</ul>