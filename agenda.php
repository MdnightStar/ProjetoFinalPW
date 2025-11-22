<?php
require "conexao.php";

//Busca as provas no banco
$stmt = $conn->query("SELECT * FROM provas ORDER BY FIELD(mes,
    'Janeiro','Fevereiro','Março','Abril','Maio','Junho',
    'Julho','Agosto','Setembro','Outubro','Novembro','Dezembro')");
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

//Edita prova
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'edit') {
    $sql = "UPDATE provas SET numero = ?, conteudo = ?, data = ? WHERE idProva = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$_POST['numero'], $_POST['conteudo'], $_POST['data'], $_POST['id']]);
    header("Location: agenda.php");
    exit;
}

//Exclui prova
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    $sql = "DELETE FROM provas WHERE idProva = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$_POST['id']]);
    header("Location: agenda.php");
    exit;
}
// vetor que na página vai gerar os cards com os meses do calendário
$meses = ["Janeiro","Fevereiro","Março","Abril","Maio","Junho",
           "Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"];
?>
<!doctype html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<title>Agenda - Test Organizer</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background:#f5f0ff; }
.navbar-strong { background:#6f42c1; }
.title-large { color:#fff; font-size:2rem; font-weight:bold; }

.months-grid{
  display:grid; grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
  gap:16px; padding:20px;
}
.month-card{
  background:#fff; border:1px solid #ddd; border-radius:10px;
  padding:15px; height:320px; display:flex; flex-direction:column;
}
.events-wrap{
  overflow-y:auto; flex:1; margin-top:10px;
}
.event-item{
  background:#e2d4ff; padding:10px; border-radius:6px;
  margin-bottom:6px; cursor:pointer;
}
.add-circle{
  width:45px;height:45px;font-size:28px;border-radius:50%;
  border:2px dashed #6f42c1;color:#6f42c1;display:flex;
  align-items:center;justify-content:center;cursor:pointer;
}

/* Nome da matéria/professor centralizados */
#subjectDisplay, #teacherDisplay {
  text-align:center;
  font-size:1.8rem;
  font-weight:bold;
  margin-bottom:5px;
  cursor:pointer;
}
.name-input { font-size:1.5rem; text-align:center; }
</style>
</head>
<body>

<nav class="navbar navbar-strong">
  <div class="container">
    <span class="title-large">Test Organizer</span>
  </div>
</nav>
<!-- Aqui define o nome da matéria e do professor -->
<div class="container mt-3 text-center">
  <input id="subjectInput" class="form-control name-input" placeholder="Nome da Matéria (Enter)">
  <div id="subjectDisplay" style="display:none;"></div>

  <input id="teacherInput" class="form-control name-input mt-2" placeholder="Nome do Professor (Enter)">
  <div id="teacherDisplay" style="display:none;"></div>
</div>
<!-- Aqui gera os cards do calendário -->
<div class="months-grid">
  <?php foreach ($meses as $m): ?>
    <div class="month-card">
      <div class="d-flex justify-content-between mb-2">
        <h5><?= $m ?></h5>
        <div class="add-circle" onclick="location.href='form.php?mes=<?= $m ?>'">+</div>
      </div>
      <div class="events-wrap">
        <?php 
        $has = false;
        foreach($events as $ev){
          if($ev['mes'] === $m){
            $has = true;
            ?>
            <div class="event-item" onclick="openEditModal(<?= $ev['idProva'] ?>)">
              <strong>Avaliação <?= $ev['numero'] ?></strong> — <?= $ev['data'] ?><br>
              <?= nl2br(htmlspecialchars($ev['conteudo'])) ?>
            </div>
          <?php }
        } 
        if(!$has) echo "<div class='text-muted'>Nenhuma prova</div>";
        // Se o events não puxar nada do bd define o texto acima para todos os cards
        ?>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<!-- Modal de edição -->
<div class="modal fade" id="editModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content p-3">
      <form method="post" action="agenda.php">
        <input type="hidden" name="action" value="edit">
        <input type="hidden" name="id" id="edit-id">
        <div class="modal-header">
          <h5>Editar Prova</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <label>Número</label>
          <input type="number" name="numero" id="edit-number" class="form-control mb-2" required>
          <label>Conteúdo</label>
          <textarea name="conteudo" id="edit-content" class="form-control mb-2" rows="3" required></textarea>
          <label>Data</label>
          <input type="date" name="data" id="edit-date" class="form-control mb-2" required>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Salvar</button>
          <button type="button" class="btn btn-danger" onclick="deleteEvent()">Excluir</button>
        </div>
      </form>
    </div>
  </div>
</div>

<form id="deleteForm" method="post" action="agenda.php" style="display:none;">
  <input type="hidden" name="action" value="delete">
  <input type="hidden" name="id" id="delete-id">
</form>

<script>
function openEditModal(id){
  fetch('get_event.php?id='+id)
    .then(r=>r.json())
    .then(d=>{
      if(d.success){
        document.getElementById('edit-id').value = id;
        document.getElementById('edit-number').value = d.event.numero;
        document.getElementById('edit-content').value = d.event.conteudo;
        document.getElementById('edit-date').value = d.event.data;
        new bootstrap.Modal(document.getElementById('editModal')).show();
      }
    });
}

function deleteEvent(){
  document.getElementById('delete-id').value = document.getElementById('edit-id').value;
  document.getElementById('deleteForm').submit();
}

// Matéria e Professor centralizados
function setupEditableDisplay(inputId, displayId){
  const input = document.getElementById(inputId);
  const display = document.getElementById(displayId);

  input.addEventListener('keypress', function(e){
    if(e.key === 'Enter'){
      display.textContent = input.value;
      display.style.display = 'block';
      input.style.display = 'none';
    }
  });

  display.addEventListener('click', function(){
    input.style.display = 'block';
    input.focus();
    display.style.display = 'none';
  });
}
setupEditableDisplay('subjectInput','subjectDisplay');
setupEditableDisplay('teacherInput','teacherDisplay');
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>