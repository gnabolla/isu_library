<!-- views/students/create.view.php -->
<div class="container mt-4">
    <h2>Add New Student</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= BASE_PATH ?>/students?action=create" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="firstname" class="form-label">First Name</label>
            <input type="text" name="firstname" class="form-control" required 
                   value="<?= htmlspecialchars($_POST['firstname'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label for="middlename" class="form-label">Middle Name</label>
            <input type="text" name="middlename" class="form-control" required
                   value="<?= htmlspecialchars($_POST['middlename'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label for="lastname" class="form-label">Last Name</label>
            <input type="text" name="lastname" class="form-control" required 
                   value="<?= htmlspecialchars($_POST['lastname'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label for="year" class="form-label">Year</label>
            <select name="year" class="form-select" required>
                <option value="">Select Year</option>
                <?php for ($i = 1; $i <= 4; $i++): ?>
                    <option value="<?= $i ?>" 
                        <?= (isset($_POST['year']) && $_POST['year'] == $i) ? 'selected' : '' ?>>
                        <?= $i ?>
                    </option>
                <?php endfor; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="course" class="form-label">Course</label>
            <select name="course" id="courseSelect" class="form-select" required>
                <option value="">Select Course</option>
                <?php 
                    $courses = ['BSIT', 'BSE', 'BSA', 'BSAB', 'BSCrim', 'BSLEA', 'BFAS'];
                    foreach ($courses as $c):
                ?>
                    <option value="<?= $c ?>" 
                        <?= (isset($_POST['course']) && $_POST['course'] == $c) ? 'selected' : '' ?>>
                        <?= $c ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="department" class="form-label">Department</label>
            <input type="text" name="department" id="department" class="form-control" required
                   value="<?= htmlspecialchars($_POST['department'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label for="section" class="form-label">Section</label>
            <select name="section" id="sectionSelect" class="form-select" required>
                <option value="">Select Section</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="rfid" class="form-label">RFID</label>
            <input type="text" name="rfid" class="form-control" required 
                   value="<?= htmlspecialchars($_POST['rfid'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label for="sex" class="form-label">Sex</label>
            <select name="sex" class="form-select" required>
                <option value="">Select Sex</option>
                <option value="Male"   <?= (isset($_POST['sex']) && $_POST['sex'] == 'Male')   ? 'selected' : '' ?>>Male</option>
                <option value="Female" <?= (isset($_POST['sex']) && $_POST['sex'] == 'Female') ? 'selected' : '' ?>>Female</option>
                <option value="Other"  <?= (isset($_POST['sex']) && $_POST['sex'] == 'Other')  ? 'selected' : '' ?>>Other</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Profile Image (Optional)</label>
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>
        <button type="submit" class="btn btn-success">Add Student</button>
        <a href="<?= BASE_PATH ?>/students" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<script>
    const departmentMap = {
      "BSIT":   "IICT",
      "BSE":    "CED",
      "BSA":    "SAA",
      "BSAB":   "SAA",
      "BSCrim": "CCJE",
      "BSLEA":  "CCJE",
      "BFAS":   "PIF"
    };

    const sectionsMap = {
      "BSIT": [
        "BSIT 1A", "BSIT 1B", "BSIT 1C", "BSIT 1D", "BSIT 1E",
        "BSIT 2AWM", "BSIT 2BWM", "BSIT 2ANS",
        "BSIT 3AWM", "BSIT 3BWM", "BSIT 3CWM", "BSIT 3ANS",
        "BSIT 4AWM", "BSIT 4BWM", "BSIT 4ANS", "BSIT 4BNS"
      ],
      "BSE": ["BSE 1A", "BSE 1B"],
      "BSA": ["BSA 1A", "BSA 2A"],
      "BSAB": ["BSAB 1A", "BSAB 2A"],
      "BSCrim": ["BSCrim 1A", "BSCrim 2A"],
      "BSLEA": ["BSLEA 1A", "BSLEA 2A"],
      "BFAS": ["BFAS 1A", "BFAS 2A"]
    };

    const courseSelect = document.getElementById('courseSelect');
    const departmentInput = document.getElementById('department');
    const sectionSelect = document.getElementById('sectionSelect');

    courseSelect.addEventListener('change', function() {
      const selectedCourse = this.value;
      departmentInput.value = departmentMap[selectedCourse] || '';
      sectionSelect.innerHTML = '<option value="">Select Section</option>';

      if (sectionsMap[selectedCourse]) {
        sectionsMap[selectedCourse].forEach(function(sec) {
          const option = document.createElement('option');
          option.value = sec;
          option.textContent = sec;
          sectionSelect.appendChild(option);
        });
      }
    });

    window.addEventListener('load', function() {
      courseSelect.dispatchEvent(new Event('change'));
    });
</script>
