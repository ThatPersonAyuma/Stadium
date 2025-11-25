<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Delete AJAX</title>
</head>
<body>

<h3>Delete via AJAX</h3>

{{-- Block --}}
<button class="btn-delete"
        data-type="block"
        data-id="12"
        data-url="{{ route('blocks.destroy', 15) }}">
    Hapus Block 12
</button>

{{-- Card --}}
<button class="btn-delete"
        data-type="card"
        data-id="5"
        data-url="{{ route('cards.destroy', 6) }}">
    Hapus Card 5
</button>

{{-- Lesson --}}
<button class="btn-delete"
        data-type="lesson"
        data-id="3"
        data-url="{{ route('lessons.destroy', 3) }}">
    Hapus Lesson 3
</button>


<script>
document.addEventListener('click', function(e) {
    if (!e.target.classList.contains('btn-delete')) return;

    const btn = e.target;

    const type = btn.dataset.type;
    const id   = btn.dataset.id;
    const url  = btn.dataset.url;

    if (!confirm(`Yakin ingin menghapus ${type} ID ${id}?`)) return;

    fetch(url, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        }
    })
    .then(async res => {
        let text = await res.text();
        alert(text || "Berhasil dihapus");
    })
    .catch(err => {
        alert("ERROR: " + err);
    });

});
</script>

</body>
</html>




{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Test Delete Block</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

<h2>Test DELETE Block</h2>

<form action="{{ route('blocks.destroy', 12) }}" method="POST">
    @csrf
    @method('DELETE')

    <button type="submit" onclick="return confirm('Yakin hapus?')">
        Hapus Block
    </button>
</form>

<input type="number" id="blockId" placeholder="Masukkan ID Block">
<button onclick="deleteBlock()">Hapus Block</button>

<pre id="result"></pre>

<script>
function deleteBlock() {
    let id = document.getElementById('blockId').value;

    fetch( "{{route('blocks.destroy', 12)}}", {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(res => res.text())
    .then(text => {
        document.getElementById('result').textContent = text || 'No Content (204)';
    })
    .catch(err => {
        document.getElementById('result').textContent = 'ERROR: ' + err;
    });
}
</script>

</body>
</html> --}}
