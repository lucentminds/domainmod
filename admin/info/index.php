<?php
/**
 * /admin/info/index.php
 *
 * This file is part of DomainMOD, an open source domain and internet asset manager.
 * Copyright (c) 2010-2018 Greg Chetcuti <greg@chetcuti.com>
 *
 * Project: http://domainmod.org   Author: http://chetcuti.com
 *
 * DomainMOD is free software: you can redistribute it and/or modify it under the terms of the GNU General Public
 * License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later
 * version.
 *
 * DomainMOD is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
 * warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with DomainMOD. If not, see
 * http://www.gnu.org/licenses/.
 *
 */
?>
<?php
require_once __DIR__ . '/../../_includes/start-session.inc.php';
require_once __DIR__ . '/../../_includes/init.inc.php';
require_once DIR_INC . '/config.inc.php';
require_once DIR_INC . '/software.inc.php';
require_once DIR_ROOT . '/vendor/autoload.php';

$deeb = DomainMOD\Database::getInstance();
$system = new DomainMOD\System();

require_once DIR_INC . '/head.inc.php';
require_once DIR_INC . '/debug.inc.php';
require_once DIR_INC . '/settings/admin-info.inc.php';

$system->authCheck();
$system->checkAdminUser($_SESSION['s_is_admin']);
$pdo = $deeb->cnxx;
?>
<?php require_once DIR_INC . '/doctype.inc.php'; ?>
<html>
<head>
    <title><?php echo $system->pageTitle($page_title); ?></title>
    <?php require_once DIR_INC . '/layout/head-tags.inc.php'; ?>
</head>
<body class="hold-transition skin-red sidebar-mini">
<?php require_once DIR_INC . '/layout/header.inc.php'; ?>

<strong><?php echo SOFTWARE_TITLE; ?> Version:</strong> <?php echo SOFTWARE_VERSION; ?> (<em><?php echo $_SESSION['s_system_db_version']; ?></em>)<BR>
<strong>Web Server IP Address:</strong> <?php echo $_SERVER['SERVER_ADDR']; ?><BR>
<strong>Web Server OS:</strong> <?php echo php_uname(); ?><BR>
<strong>Web Server Software:</strong> <?php echo $_SERVER['SERVER_SOFTWARE']; ?><BR>
<strong>PHP Version:</strong> <?php echo phpversion(); ?><BR>
<strong>PHP Error Log Location:</strong> <?php echo ini_get('error_log'); ?><BR>
<strong>MySQL Version:</strong> <?php echo $pdo->query('select version()')->fetchColumn(); ?><BR>

<?php require_once DIR_INC . '/layout/footer.inc.php'; ?>
</body>
</html>
