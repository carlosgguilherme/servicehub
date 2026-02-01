<h1>Nova Empresa</h1>

<form method="POST" action="{{ route('companies.store') }}">
    @csrf

    <input type="text" name="name" placeholder="Nome da empresa">

    <button type="submit">Salvar</button>
</form>
