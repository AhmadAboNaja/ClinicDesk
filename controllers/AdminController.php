<?php

require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/CSRF.php';
require_once __DIR__ . '/../models/Specialization.php';

class SpecializationController {
    private Specialization $specializationModel;

    public function __construct() {
        $this->specializationModel = new Specialization();
    }

    public function index(): void {
        Auth::requireRole('admin');
        $specializations = $this->specializationModel->getAll();

        $pageTitle = 'Specializations';
        require __DIR__ . '/../views/partials/header.php';
        require __DIR__ . '/../views/doctors/specializations.php';
        require __DIR__ . '/../views/layouts/footer.php';
    }

    public function create(): void {
        Auth::requireRole('admin');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf_token'] ?? '';
            if (!CSRF::validateToken($token)) {
                flash('error', 'Invalid CSRF token.');
                redirect('index.php?page=specializations');
            }

            $name = trim($_POST['name'] ?? '');
            if (!$name) {
                flash('error', 'Name is required.');
                redirect('index.php?page=specializations');
            }

            $this->specializationModel->create($name);
            flash('success', 'Specialization created.');
            redirect('index.php?page=specializations');
        }

        $pageTitle = 'Add Specialization';
        require __DIR__ . '/../views/partials/header.php';
        require __DIR__ . '/../views/doctors/specialization_form.php';
        require __DIR__ . '/../views/layouts/footer.php';
    }

    public function delete(): void {
        Auth::requireRole('admin');
        $id = (int) ($_GET['id'] ?? 0);
        $token = $_POST['csrf_token'] ?? '';

        if (!CSRF::validateToken($token)) {
            flash('error', 'Invalid CSRF token.');
            redirect('index.php?page=specializations');
        }

        if (!$this->specializationModel->isSafeToDelete($id)) {
            flash('error', 'Cannot delete specialization with associated doctors.');
            redirect('index.php?page=specializations');
        }

        $this->specializationModel->delete($id);
        flash('success', 'Specialization deleted.');
        redirect('index.php?page=specializations');
    }
}
