<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><i class="fas fa-calendar-plus mr-2"></i>Book an Appointment</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
<div class="container-fluid">

<?php displayFlash(); ?>

<div class="row">

<!-- LEFT SIDE -->
<div class="col-md-8">

<div class="card card-primary">
<div class="card-header">
    <h3 class="card-title">Appointment Details</h3>
</div>

<form method="POST" action="index.php?page=appointments&action=book">

<div class="card-body">

<?php echo CSRF::input(); ?>

<!-- SEARCH -->
<div class="form-group">
    <label>Search Doctor</label>
    <input type="text" id="doctorSearch" class="form-control" placeholder="Search doctor by name...">
</div>

<!-- DOCTOR SELECT -->
<div class="form-group">
    <label>Select Doctor <span class="text-danger">*</span></label>

    <select class="form-control" id="doctor_id" name="doctor_id" required>
        <option value="">-- Choose Doctor --</option>

        <?php foreach ($doctors as $doctor): ?>
            <option
                value="<?php echo $doctor['user_id']; ?>"
                data-days="<?php echo htmlspecialchars($doctor['available_days']); ?>"
            >
                <?php echo htmlspecialchars($doctor['user_name']); ?>
                (<?php echo htmlspecialchars($doctor['specialization']); ?>)
            </option>
        <?php endforeach; ?>
    </select>
</div>

<!-- AVAILABLE DAYS DISPLAY -->
<div class="alert alert-info" id="doctorInfo" style="display:none;">
    <strong>Available Days:</strong>
    <span id="daysList"></span>
</div>

<!-- DATE -->
<div class="form-group">
    <label>Preferred Date</label>
    <input type="date" class="form-control" id="appt_date" name="appt_date"
           min="<?php echo date('Y-m-d'); ?>" required>
</div>

<!-- TIME -->
<div class="form-group">
    <label>Time Slot</label>
    <select class="form-control" name="appt_time" required>
        <option value="">Select Time</option>
        <option value="09:00:00">09:00 AM</option>
        <option value="10:00:00">10:00 AM</option>
        <option value="11:00:00">11:00 AM</option>
        <option value="14:00:00">02:00 PM</option>
        <option value="15:00:00">03:00 PM</option>
    </select>
</div>

<!-- REASON -->
<div class="form-group">
    <label>Reason</label>
    <textarea class="form-control" name="reason" required></textarea>
</div>

</div>

<div class="card-footer">
    <button class="btn btn-primary">
        <i class="fas fa-check"></i> Book Appointment
    </button>
</div>

</form>
</div>

</div>

<!-- RIGHT SIDE -->
<div class="col-md-4">

<div class="card card-info">
<div class="card-header">
    <h3 class="card-title">Info</h3>
</div>
<div class="card-body">
<ul>
    <li>Appointments are subject to availability</li>
    <li>Please arrive 10 minutes early</li>
    <li>Bring your ID</li>
</ul>
</div>
</div>

</div>

</div>
</div>
</section>

<!-- ================= JS ================= -->
<script>
const doctorSelect = document.getElementById('doctor_id');
const dateInput = document.getElementById('appt_date');
const infoBox = document.getElementById('doctorInfo');
const daysList = document.getElementById('daysList');

const weekMap = ["Sun","Mon","Tue","Wed","Thu","Fri","Sat"];

let allowedDays = [];

// doctor change
doctorSelect.addEventListener('change', function () {
    const selected = this.options[this.selectedIndex];
    const days = selected.getAttribute('data-days');

    if (!days) {
        infoBox.style.display = 'none';
        allowedDays = [];
        return;
    }

    allowedDays = days.split(',');

    daysList.innerHTML = allowedDays.join(', ');
    infoBox.style.display = 'block';
});

// prevent invalid day
dateInput.addEventListener('change', function () {
    const date = new Date(this.value);
    const day = weekMap[date.getDay()];

    if (allowedDays.length && !allowedDays.includes(day)) {
        alert("Doctor is not available on " + day);
        this.value = '';
    }
});

// search filter
document.getElementById('doctorSearch').addEventListener('keyup', function () {
    let value = this.value.toLowerCase();
    let options = doctorSelect.options;

    for (let i = 0; i < options.length; i++) {
        let text = options[i].text.toLowerCase();
        options[i].style.display = text.includes(value) ? '' : 'none';
    }
});
</script>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>