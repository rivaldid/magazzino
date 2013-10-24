<?php
include('upload.class.php');

$upload = new Upload;

if (!empty($_POST['action']) && $_POST['action'] == 'upload') {
    $upload->uploadFile('/magazzino/files/', 'md5', 10);
	?>
	<script>
		setTimeout('document.location="test.php"',1000);
	</script>
	<?php

	print_r($upload->_files);
}

//$upload->imgResize('filename', '/files/', 800, true);
//$upload->deleteFile('filename', '/files/');

?>
<form action="<?=$_SERVER['PHP_SELF']?>" method="POST" name="upload" ENCTYPE="multipart/form-data">
<input type="file" name="file1"><br>
<input type="file" name="file2"><br>
<input type="file" name="file3"><br>
<input type="file" name="file4"><br>
<input type="file" name="file5"><br>
<input type="submit" value="Забросить">
<input type="hidden" name="action" value="upload">
</form>
