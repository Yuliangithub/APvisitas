

<div class="sidebar">
    <a href="http://192.168.1.216/APvisitas/api/dashboard.php">Dashboard</a>
    <a href="http://192.168.1.216/APvisitas/api/visitas.php">Visitas</a>
    <a href="http://192.168.1.216/APvisitas/api/aspectos.php">Aspectos</a>
    <a href="http://192.168.1.216/APvisitas/api/evidencias.php">Evidencias</a>
    <a href="http://192.168.1.216/APvisitas/api/reportes.php">Reportes</a>
</div>


<style>
    .sidebar {
            width: 210px;
            position: fixed;
            top: 0; left: 0; height: 100vh;
            background: #212529;
            color: #fff;
            padding-top: 60px;
        }
        .sidebar a {
            color: #fff; display: block; padding: 12px 24px; text-decoration: none;
        }
        .sidebar a:hover { background: #343a40; }

         @media (max-width: 900px) {
            .sidebar, .main-content { margin-left: 0; width: 100%; }
            .sidebar { position: static; width: 100%; height: auto; }
        }
</style>