<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
?>
<script>
<?php if (!empty($_SESSION['success'])): ?>
Swal.fire({icon:'success', title:'Berhasil', text: <?= json_encode($_SESSION['success']); ?>, confirmButtonColor:'#9d2b22'});
<?php unset($_SESSION['success']); endif; ?>
<?php if (!empty($_SESSION['error'])): ?>
Swal.fire({icon:'error', title:'Oops', text: <?= json_encode($_SESSION['error']); ?>, confirmButtonColor:'#9d2b22'});
<?php unset($_SESSION['error']); endif; ?>
</script>
