<?php
$uploadDirectory = __DIR__ . '/../uploads/';

if (!is_dir($uploadDirectory)) {
    mkdir($uploadDirectory, 0755, true);
}

$response = [
    'status' => 'error',
    'message' => '',
    'data' => []
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $fileName = basename($file['name']);
    $targetFilePath = $uploadDirectory . $fileName;

    $fileType = mime_content_type($file['tmp_name']);
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/bmp', 'image/tiff'];

    if (in_array($fileType, $allowedTypes)) {
        if ($file['error'] === UPLOAD_ERR_OK) {
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $date = date('dmy');
            $randomWords = generateRandomString(4);
            $newFileName = 'botaniq-' . $date . '-' . $randomWords . '.' . $fileExtension;

            $targetFilePath = $uploadDirectory . $newFileName;

            $maxFileSize = 10 * 1024 * 1024;
            if ($file['size'] > $maxFileSize) {
                $response['message'] = 'File terlalu besar. Maksimal 10MB.';
            } elseif (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
                $fileUrl = 'https://botaniq.cogarden.app/uploads/' . $newFileName;
                $response['status'] = 'success';
                $response['message'] = 'File berhasil diunggah.';
                $response['data'] = ['file_url' => $fileUrl];
            } else {
                $response['message'] = 'Gagal mengunggah file.';
            }
        } else {
            $response['message'] = 'Terjadi kesalahan saat mengunggah file: ' . $file['error'];
        }
    } else {
        $response['message'] = 'Hanya file gambar yang diperbolehkan diunggah.';
    }
} else {
    $response['message'] = 'Harap unggah file melalui metode POST.';
}

header('Content-Type: application/json');
echo json_encode($response);

function generateRandomString($wordCount) {
    $words = ['apple', 'banana', 'cherry', 'date', 'elderberry', 'fig', 'grape', 'honeydew', 'kiwi', 'lemon', 'mango', 'nectarine', 'orange', 'papaya', 'quince', 'raspberry', 'strawberry', 'tangerine', 'ugli', 'violet', 'watermelon', 'xigua', 'yellow', 'zucchini'];
    $randomWords = [];

    for ($i = 0; $i < $wordCount; $i++) {
        $randomWord = $words[array_rand($words)];
        $randomNumber = rand(100, 999);
        $randomWords[] = $randomWord . $randomNumber;
    }

    return implode('-', $randomWords);
}
?>
