<?php
// views/home/dashboard.php

// Incluimos la nueva clase frontera
require_once 'FormPanelControl.php';

// Instanciamos y mostramos
$objDashboard = new FormPanelControl();
$objDashboard->formPanelControlShow();
?>