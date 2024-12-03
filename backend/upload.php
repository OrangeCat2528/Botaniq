<?php
$uploadDirectory = __DIR__ . '/../uploads/';

// Pastikan folder upload ada
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
    error_log('Uploaded file info: ' . print_r($file, true)); // Log file info untuk debugging

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

            $maxFileSize = 10 * 1024 * 1024; // Maksimal ukuran file 10MB
            if ($file['size'] > $maxFileSize) {
                $response['message'] = 'File terlalu besar. Maksimal 10MB.';
            } else {
                // Log size of the uploaded file
                error_log('File size: ' . $file['size']);

                if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
                    // Log success upload
                    error_log('File berhasil diunggah ke: ' . $targetFilePath);

                    $fileUrl = 'https://botaniq.cogarden.app/uploads/' . $newFileName;
                    $response['status'] = 'success';
                    $response['message'] = 'File berhasil diunggah.';
                    $response['data'] = ['file_url' => $fileUrl];
                } else {
                    // Log failure
                    error_log('Gagal mengunggah file ke: ' . $targetFilePath);
                    $response['message'] = 'Gagal mengunggah file.';
                }
            }
        } else {
            $response['message'] = 'Terjadi kesalahan saat mengunggah file: ' . uploadErrorToMessage($file['error']);
        }
    } else {
        $response['message'] = 'Hanya file gambar yang diperbolehkan diunggah.';
    }
} else {
    $response['message'] = 'Harap unggah file melalui metode POST.';
}

header('Content-Type: application/json');
echo json_encode($response);

// Fungsi untuk mengonversi kode error upload ke pesan yang lebih mudah dipahami
function uploadErrorToMessage($errorCode) {
    switch ($errorCode) {
        case UPLOAD_ERR_OK:
            return 'File berhasil diunggah.';
        case UPLOAD_ERR_INI_SIZE:
            return 'Ukuran file melebihi batas yang diizinkan oleh server (upload_max_filesize).';
        case UPLOAD_ERR_FORM_SIZE:
            return 'Ukuran file melebihi batas yang diizinkan dalam formulir HTML (MAX_FILE_SIZE).';
        case UPLOAD_ERR_PARTIAL:
            return 'File hanya terupload sebagian.';
        case UPLOAD_ERR_NO_FILE:
            return 'Tidak ada file yang diunggah.';
        case UPLOAD_ERR_NO_TMP_DIR:
            return 'Direktori sementara tidak ditemukan.';
        case UPLOAD_ERR_CANT_WRITE:
            return 'Gagal menulis file ke disk.';
        case UPLOAD_ERR_EXTENSION:
            return 'File upload dibatalkan karena ekstensi PHP (seperti mod_security).';
        default:
            return 'Terjadi kesalahan tidak terduga saat mengunggah file.';
    }
}

// Fungsi untuk menghasilkan string acak
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
