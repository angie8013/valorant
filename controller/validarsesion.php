<?php
if(!isset($_SESSION['id_jugador']) && !isset($_SESSION['id_jugador' ])){
echo '
<script>
    alert("error");
    window.location = "index.php";
</script>
';
}
?>