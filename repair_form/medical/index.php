<?php

$data = require_once '../../mock_data/mock_data.php';

include '../../components/header.php';
include '../../components/navbar.php';
?>
<div class="container-fluid mt-3">

    <?php include 'filter.php'; ?>

    <?php include 'card_summary.php'; ?>

    <?php include 'dashboard.php'; ?>

    <?php include 'chart.php'; ?>

</div>

<?php
include '../../components/footer.php';
include '../../components/scripts.php';
?>