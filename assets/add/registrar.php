<?php
/**
 * /assets/add/registrar.php
 *
 * This file is part of DomainMOD, an open source domain and internet asset manager.
 * Copyright (c) 2010-2017 Greg Chetcuti <greg@chetcuti.com>
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

require_once DIR_ROOT . '/vendor/autoload.php';

$system = new DomainMOD\System();
$time = new DomainMOD\Time();
$form = new DomainMOD\Form();

require_once DIR_INC . '/head.inc.php';
require_once DIR_INC . '/config.inc.php';
require_once DIR_INC . '/software.inc.php';
require_once DIR_INC . '/debug.inc.php';
require_once DIR_INC . '/settings/assets-add-registrar.inc.php';

$pdo = $system->db();
$system->authCheck();
$system->readOnlyCheck($_SERVER['HTTP_REFERER']);

$new_registrar = $_POST['new_registrar'];
$new_url = $_POST['new_url'];
$new_api_registrar_id = $_POST['new_api_registrar_id'];
$new_notes = $_POST['new_notes'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if ($new_registrar != "") {

        $stmt = $pdo->prepare("
            INSERT INTO registrars
            (`name`, url, api_registrar_id, notes, created_by, insert_time)
            VALUES
            (:new_registrar, :new_url, :new_api_registrar_id, :new_notes, :created_by, :timestamp)");
        $stmt->bindValue('new_registrar', $new_registrar, PDO::PARAM_STR);
        $stmt->bindValue('new_url', $new_url, PDO::PARAM_STR);
        $stmt->bindValue('new_api_registrar_id', $new_api_registrar_id, PDO::PARAM_INT);
        $stmt->bindValue('new_notes', $new_notes, PDO::PARAM_LOB);
        $stmt->bindValue('created_by', $_SESSION['s_user_id'], PDO::PARAM_INT);
        $timestamp = $time->stamp();
        $stmt->bindValue('timestamp', $timestamp, PDO::PARAM_STR);
        $stmt->execute();

        $_SESSION['s_message_success'] .= "Registrar " . $new_registrar . " Added<BR>";

        if ($_SESSION['s_has_registrar'] != '1') {

            $system->checkExistingAssets();

            header("Location: ../../domains/index.php");

        } else {

            header("Location: ../registrars.php");

        }
        exit;

    } else {

        if ($new_registrar == "") $_SESSION['s_message_danger'] .= "Enter the registrar name<BR>";

    }

}
?>
<?php require_once DIR_INC . '/doctype.inc.php'; ?>
<html>
<head>
    <title><?php echo $system->pageTitle($page_title); ?></title>
    <?php require_once DIR_INC . '/layout/head-tags.inc.php'; ?>
</head>
<body class="hold-transition skin-red sidebar-mini">
<?php require_once DIR_INC . '/layout/header.inc.php'; ?>
<?php
echo $form->showFormTop('');
echo $form->showInputText('new_registrar', 'Registrar Name (100)', '', $new_registrar, '100', '', '1', '', '');
echo $form->showInputText('new_url', 'Registrar\'s URL (100)', '', $new_url, '100', '', '', '', '');

$result = $pdo->query("
    SELECT id, `name`
    FROM api_registrars
    ORDER BY `name` ASC")->fetchAll();

echo $form->showDropdownTop('new_api_registrar_id', 'API Support', 'If the registrar has an API please select it from the list below.', '', '');
echo $form->showDropdownOption('0', 'n/a', '0');

foreach ($result as $row) {

    echo $form->showDropdownOption($row->id, $row->name, $new_api_registrar_id);

}
echo $form->showDropdownBottom('');

echo $form->showInputTextarea('new_notes', 'Notes', '', $new_notes, '', '', '');
echo $form->showSubmitButton('Add Domain Registrar', '', '');
echo $form->showFormBottom('');
?>
<?php require_once DIR_INC . '/layout/footer.inc.php'; ?>
</body>
</html>
