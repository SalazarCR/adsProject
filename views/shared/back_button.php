<?php
$destino = $back_to ?? "/views/home/dashboard.php";
?>

<a href="<?= $destino ?>" class="btn-back">
    ⬅ Regresar
</a>

<style>
.btn-back {
    display: inline-block;
    padding: 8px 14px;
    background: #444;
    color: #fff;
    border-radius: 5px;
    text-decoration: none;
    margin-bottom: 15px;
}
.btn-back:hover {
    background: #222;
}
</style>
