<?php

class UploadResponse {
	public $uploaded;
	public $fileName;
	public $url;
	//public $error;

	public function __construct($uploaded, $name = null, $url = null,
		$error = null) {
		$this->uploaded = $uploaded;
		$this->fileName = $name;
		$this->url = $url;
		//$this->error = ['message' => $error];
	}
}

function attemptFileUpload() {
	if (isset($_FILES['upload']) && file_exists($_FILES['upload']['tmp_name'])) {
		$tmpPath = $_FILES['upload']['tmp_name'];
		$filename = $_FILES['upload']['name'];
		$urlPath = '/assets/img/uploads/' . $filename;
		$targetPath = $_SERVER['DOCUMENT_ROOT'] . $urlPath;

		if (!getimagesize($tmpPath)) {
			return new UploadResponse(0, $filename, null, 'file is not an image');
		}

		if (move_uploaded_file($tmpPath, $targetPath)) {
			$funcNum = $_GET['CKEditorFuncNum'];
			echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$urlPath');</script>";
			//return new UploadResponse(1, $filename, $urlPath);
		}
	}
	return new UploadResponse(0, $filename, null, 'problem uploading image');
}

// header('Content-type: application/json');
// echo json_encode(attemptFileUpload(), JSON_UNESCAPED_SLASHES);
echo attemptFileUpload();
