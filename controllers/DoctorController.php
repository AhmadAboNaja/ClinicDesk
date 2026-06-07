<?php

require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/CSRF.php';
require_once __DIR__ . '/../core/helpers.php';
require_once __DIR__ . '/../core/Paginator.php';
require_once __DIR__ . '/../models/Doctor.php';
require_once __DIR__ . '/../models/Specialization.php';

class DoctorController
{
    private Doctor $doctorModel;
    private Specialization $specializationModel;

    public function __construct()
    {
        $this->doctorModel = new Doctor();
        $this->specializationModel = new Specialization();
    }

    public function index(): void
    {
        Auth::requireRole('admin', 'doctor');

        $page = (int)($_GET['page_num'] ?? 1);
        $search = $_GET['search'] ?? '';
        $specialization = $_GET['specialization'] ?? '';

        $total = $this->doctorModel->countDoctors($search, $specialization);
        $paginator = new Paginator($total, ITEMS_PER_PAGE, $page);

        $doctors = $this->doctorModel->getDoctorsPaginated($page, $search, $specialization);
        $specializations = $this->specializationModel->getAll();

        $pageTitle = 'Doctors';

        require __DIR__ . '/../views/partials/header.php';
        require __DIR__ . '/../views/doctors/index.php';
        require __DIR__ . '/../views/layouts/footer.php';
    }

    private function handleDoctorProfilePhoto(array &$data): void
    {
        if (empty($_FILES['profile_photo']) || !isset($_FILES['profile_photo']['tmp_name'])) {
            return;
        }

        // If no file selected
        if (($_FILES['profile_photo']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return;
        }

        // If the input exists but no file was selected
        if (empty($_FILES['profile_photo']['tmp_name']) || !is_uploaded_file($_FILES['profile_photo']['tmp_name'])) {
            return;
        }

        $file = $_FILES['profile_photo'];

        if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
            flash('error', 'Failed to upload profile photo.');
            redirect('index.php?page=doctors');
        }

        $size = (int)($file['size'] ?? 0);
        if ($size <= 0) {
            return;
        }

        if ($size > (UPLOAD_MAX_AVATAR ?? 1048576)) {
            flash('error', 'Profile photo is too large. Max 1MB allowed.');
            redirect('index.php?page=doctors');
        }

        $tmpPath = $file['tmp_name'];

        // JPEG/PNG only - use getimagesize() validation
        $imgInfo = @getimagesize($tmpPath);
        if ($imgInfo === false) {
            flash('error', 'Invalid image file.');
            redirect('index.php?page=doctors');
        }

        $mime = $imgInfo['mime'] ?? '';
        $allowed = ['image/jpeg', 'image/png'];
        if (!in_array($mime, $allowed, true)) {
            flash('error', 'Only JPEG/PNG images are allowed for doctor profile photos.');
            redirect('index.php?page=doctors');
        }

        $ext = $mime === 'image/png' ? 'png' : 'jpg';

        if (!is_dir(DOCTOR_PHOTO_UPLOAD_DIR)) {
            @mkdir(DOCTOR_PHOTO_UPLOAD_DIR, 0775, true);
        }

        $filename = 'doctor_' . time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
        $destPath = rtrim(DOCTOR_PHOTO_UPLOAD_DIR, '/\\') . DIRECTORY_SEPARATOR . $filename;

        if (!move_uploaded_file($tmpPath, $destPath)) {
            flash('error', 'Could not save the uploaded photo.');
            redirect('index.php?page=doctors');
        }

        // Store only filename/path relative to uploads dir
        $data['profile_photo'] = $filename;
    }

    public function create(): void
    {
        Auth::requireRole('admin');

        $specializations = $this->specializationModel->getAll();
        $doctorUsers = $this->doctorModel->getDoctorsAvailable();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf_token'] ?? '';
            if (!CSRF::validateToken($token)) {
                flash('error', 'Invalid CSRF token.');
                redirect('index.php?page=doctors');
            }

            $days = array_filter($_POST['available_days'] ?? []);
            $data = [
                'user_id' => (int)($_POST['user_id'] ?? 0),
                'specialization_id' => (int)($_POST['specialization_id'] ?? 0),
                'bio' => $_POST['bio'] ?? '',
                'consultation_fee' => (float)($_POST['consultation_fee'] ?? 0),
                'available_days' => implode(',', $days),
                // 'profile_photo' handled below (optional)
            ];

            $this->handleDoctorProfilePhoto($data);

            $this->doctorModel->create($data);
            flash('success', 'Doctor record created.');
            redirect('index.php?page=doctors');
        }

        $pageTitle = 'Create Doctor Record';
        require __DIR__ . '/../views/partials/header.php';
        require __DIR__ . '/../views/doctors/create.php';
        require __DIR__ . '/../views/layouts/footer.php';
    }

    public function edit(): void
    {
        Auth::requireRole('admin');

        $id = (int)($_GET['id'] ?? 0);

        $editedDoctor = $this->doctorModel->findById($id);
        if (!$editedDoctor) {
            flash('error', 'Doctor not found.');
            redirect('index.php?page=doctors');
        }

        $specializations = $this->specializationModel->getAll();
        $availableDays = $this->doctorModel->getAvailableDays($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf_token'] ?? '';
            if (!CSRF::validateToken($token)) {
                flash('error', 'Invalid CSRF token.');
                redirect('index.php?page=doctors&action=edit&id=' . $id);
            }

            $days = array_filter($_POST['available_days'] ?? []);
            $data = [
                'specialization_id' => (int)($_POST['specialization_id'] ?? 0),
                'bio' => $_POST['bio'] ?? '',
                'consultation_fee' => (float)($_POST['consultation_fee'] ?? 0),
                'available_days' => implode(',', $days),
                // 'profile_photo' handled below (optional)
            ];

            // If new file uploaded, replace old file
            $oldPhoto = $editedDoctor['profile_photo'] ?? '';
            $this->handleDoctorProfilePhoto($data);

            if (isset($data['profile_photo']) && $oldPhoto) {
                $oldPath = rtrim(DOCTOR_PHOTO_UPLOAD_DIR, '/\\') . DIRECTORY_SEPARATOR . $oldPhoto;
                if (is_file($oldPath)) {
                    @unlink($oldPath);
                }
            }

            $this->doctorModel->update($id, $data);
            flash('success', 'Doctor record updated.');
            redirect('index.php?page=doctors');
        }

        $pageTitle = 'Edit Doctor';
        require __DIR__ . '/../views/partials/header.php';
        require __DIR__ . '/../views/doctors/edit.php';
        require __DIR__ . '/../views/layouts/footer.php';
    }
}

