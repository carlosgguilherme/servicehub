<h1>Novo Projeto</h1>

<form method="POST" action="{{ route('projects.store') }}">
    @csrf

    <label>Empresa</label>
    <select name="company_id" required>
        @foreach($companies as $company)
            <option value="{{ $company->id }}">
                {{ $company->name }}
            </option>
        @endforeach
    </select>

    <br><br>

    <label>Nome do Projeto</label>
    <input type="text" name="name" required>

    <br><br>

    <button type="submit">Salvar Projeto</button>
</form>
