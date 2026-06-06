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
        $page = (int) ($_GET['page_num'] ?? 1);
        $total = $this->doctorModel->countAll();
        $paginator = new Paginator($total, ITEMS_PER_PAGE, $page);
        $doctors = $this->doctorModel->getAllPaginated($page);

        $pageTitle = 'Doctors';
        require __DIR__ . '/../views/partials/header.php';
        require __DIR__ . '/../views/doctors/index.php';
        require __DIR__ . '/../views/layouts/footer.php';
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
                'user_id' => (int) ($_POST['user_id'] ?? 0),
                'specialization_id' => (int) ($_POST['specialization_id'] ?? 0),
                'bio' => $_POST['bio'] ?? '',
                'consultation_fee' => (float) ($_POST['consultation_fee'] ?? 0),
                'available_days' => implode(',', $days),
            ];

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

        $id = (int) ($_GET['id'] ?? 0);

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
                'specialization_id' => (int) ($_POST['specialization_id'] ?? 0),
                'bio' => $_POST['bio'] ?? '',
                'consultation_fee' => (float) ($_POST['consultation_fee'] ?? 0),
                'available_days' => implode(',', $days),
            ];

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
