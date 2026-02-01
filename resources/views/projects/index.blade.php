<h1>Projetos</h1>

<a href="{{ route('projects.create') }}">Novo Projeto</a>

<ul>
@foreach($projects as $project)
    <li>
        {{ $project->name }} - Empresa: {{ $project->company->name }}
    </li>
@endforeach
</ul>
