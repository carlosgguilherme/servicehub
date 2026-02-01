<!DOCTYPE html>
<html>
<head>
    <title>Tickets</title>
    <meta charset="UTF-8">
</head>
<body>

<h1>Lista de Tickets</h1>

<a href="{{ route('tickets.create') }}">Criar Ticket</a>

<br><br>

<table border="1" cellpadding="8">
    <thead>
        <tr>
            <th>ID</th>
            <th>Título</th>
            <th>Status</th>
            <th>Projeto</th>
            <th>Anexo</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($tickets as $ticket)
            <tr>
                <td>{{ $ticket->id }}</td>
                <td>{{ $ticket->title }}</td>
                <td>{{ $ticket->status }}</td>
                <td>{{ $ticket->project->name ?? '-' }}</td>

                <td>
                    @if($ticket->attachment_path)
                        <button onclick="openModal('{{ asset('storage/'.$ticket->attachment_path) }}')">
                            Ver anexo
                        </button>
                    @else
                        Sem anexo
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<div id="imgModal" style="
    display:none;
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.8);
    text-align:center;
">

    <span onclick="closeModal()" style="
        color:white;
        font-size:30px;
        cursor:pointer;
        position:absolute;
        top:20px;
        right:30px;
    ">X</span>

    <img id="modalImg" style="
        margin-top:5%;
        max-width:90%;
        max-height:90%;
    ">
</div>

<script>
function openModal(src) {
    document.getElementById("modalImg").src = src;
    document.getElementById("imgModal").style.display = "block";
}

function closeModal() {
    document.getElementById("imgModal").style.display = "none";
}
</script>

</body>
</html>
