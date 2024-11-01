<?php
$file = $_GET["download"];
$ideaIdToDetail = $_GET['ideaIdToDetail'];

if ($file == "Uploads/No File Found") {
    echo "<script>alert('File not found!')</script>";
    echo "<script>location='ideaSingle.php?ideaIdToDetail=$ideaIdToDetail'</script>";
} else {
    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

    if ($extension == "pdf") {
        header("Content-Type: application/octet-stream");

        header("Content-Disposition: attachment; filename=" . urlencode($file));
        header("Content-Type: application/download");
        header("Content-Description: File Transfer");
        header("Content-Length: " . filesize($file));

        flush(); // This doesn't really matter.

        $fp = fopen($file, "r");
        while (!feof($fp)) {
            echo fread($fp, 65536);
            flush(); // This is essential for large downloads
        }
        fclose($fp);
    }
}
