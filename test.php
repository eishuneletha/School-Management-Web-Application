<?php
include('connect.php');
$ideaId=1;
$select=$conn->prepare("SELECT * from idea where ideaId=$ideaId");
$select->execute();
$ideadata=$select->fetch(PDO::FETCH_ASSOC);
if ($ideadata['qamId']!=NULL) {
    $qamId=$ideadata['qamId'];
    $selectauthor=$conn->prepare("select qm.qamName ,qc.qacName ,a.adminName ,s.staffName
    from qam qm, qac qc, admin a, staff s, idea i, comment c
    where i.ideaId=c.ideaId
    and i.qamId=qm.qamId
    and i.qacId=qc.qacId
    and i.adminId=a.adminId
    and i.staffId=s.staffId
    and i.ideaId= $ideaId
    and i.qamId = $qamId
    ");
}else if($ideadata['qacId']!=NULL){
    $qacId=$ideadata['qacId'];
    $selectauthor=$conn->prepare("select qm.qamName ,qc.qacName ,a.adminName ,s.staffName
    from qam qm, qac qc, admin a, staff s, idea i, comment c
    where i.ideaId=c.ideaId
    and i.qamId=qm.qamId
    and i.qacId=qc.qacId
    and i.adminId=a.adminId
    and i.staffId=s.staffId
    and i.ideaId= $ideaId
    and i.qacId = $qacId
    ");
}else if($ideadata['staffId']!=NULL){
    $staffId=$ideadata['staffId'];
    $selectauthor=$conn->prepare("select qm.qamName ,qc.qacName ,a.adminName ,s.staffName
    from qam qm, qac qc, admin a, staff s, idea i, comment c
    where i.ideaId=c.ideaId
    and i.qamId=qm.qamId
    and i.qacId=qc.qacId
    and i.adminId=a.adminId
    and i.staffId=s.staffId
    and i.ideaId= $ideaId
    and i.staffId = $staffId
    ");
}else if($ideadata['adminId']!=NULL){   
    $adminId=$ideadata['adminId'];
    $selectauthor=$conn->prepare("select qm.qamName ,qc.qacName ,a.adminName ,s.staffName
    from qam qm, qac qc, admin a, staff s, idea i, comment c
    where i.ideaId=c.ideaId
    and i.qamId=qm.qamId
    and i.qacId=qc.qacId
    and i.adminId=a.adminId
    and i.staffId=s.staffId
    and i.ideaId= $ideaId
    and i.adminId = $adminId
    ");
}
