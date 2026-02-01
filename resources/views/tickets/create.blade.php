<form method="POST" action="{{ route('tickets.store') }}" enctype="multipart/form-data">
    @csrf

    <label>Projeto</label>
    <select name="project_id">
        @foreach($projects as $project)
        <option value="{{ $project->id }}">
            {{ $project->name }}
        </option>
        @endforeach
    </select>

    <label>Título</label>
    <input type="text" name="title">

    <label>Descrição</label>
    <textarea name="description"></textarea>

    <label>Anexo</label>
    <input type="file" name="attachment">
    <br><br>

    <button type="submit">Criar Ticket</button>

</form>