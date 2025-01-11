<!-- views/rfid/scan.view.php -->

<div class="container mt-4">
    <h2>RFID Scan Interface</h2>
    <div class="card p-4">
        <form id="rfid-form">
            <div class="mb-3">
                <label for="rfid" class="form-label">Scan RFID</label>
                <input type="text" id="rfid" name="rfid" class="form-control" autofocus autocomplete="off" placeholder="Tap RFID here" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        <div id="feedback" class="mt-3"></div>
        
        <div id="studentInfo" class="mt-3" style="display: none;">
            <div class="text-center">
                <img id="studentImage" class="img-fluid" style="max-height: 250px;" alt="Student Photo">
            </div>
            <div class="mt-3">
                <h4 id="studentName"></h4>
                <p id="studentDepartment" class="mb-1"></p>
                <p id="logType" class="fw-bold"></p>
                <!-- Display date/time -->
                <p id="scanTime" class="text-muted"></p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const rfidForm       = document.getElementById('rfid-form');
    const rfidInput      = document.getElementById('rfid');
    const feedbackDiv    = document.getElementById('feedback');
    const studentInfoDiv = document.getElementById('studentInfo');
    const studentImage   = document.getElementById('studentImage');
    const studentName    = document.getElementById('studentName');
    const studentDept    = document.getElementById('studentDepartment');
    const logType        = document.getElementById('logType');
    const scanTime       = document.getElementById('scanTime');
    let imageTimer       = null;

    rfidForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const rfid = rfidInput.value.trim();
        if (!rfid) {
            displayFeedback('RFID is required.', 'danger');
            return;
        }

        fetch('<?= BASE_PATH ?>/rfid', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `rfid=${encodeURIComponent(rfid)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                displayFeedback(data.message, 'success');
                if (data.student) {
                    studentName.textContent = data.student.firstname + ' ' + data.student.lastname;
                    studentDept.textContent = data.student.department;
                    logType.textContent     = (data.log_type === 'in') 
                        ? 'You have successfully TIMED IN.'
                        : 'You have successfully TIMED OUT.';

                    // Display server-generated date_time
                    if (data.date_time) {
                        scanTime.textContent = 'Date & Time: ' + data.date_time;
                    } else {
                        scanTime.textContent = '';
                    }

                    if (data.student.image) {
                        if (imageTimer) clearTimeout(imageTimer);
                        studentImage.src = data.student.image;
                        studentImage.alt = data.student.firstname + ' ' + data.student.lastname;
                        studentInfoDiv.style.display = 'block';
                        // Change display timer to 10 seconds
                        imageTimer = setTimeout(() => {
                            studentInfoDiv.style.display = 'none';
                            studentImage.src = '';
                        }, 10000);
                    } else {
                        studentImage.src = '';
                        studentInfoDiv.style.display = 'block';
                        // Also hide after 10 seconds if no image
                        if (imageTimer) clearTimeout(imageTimer);
                        imageTimer = setTimeout(() => {
                            studentInfoDiv.style.display = 'none';
                        }, 10000);
                    }
                }
            } else {
                displayFeedback(data.message, 'danger');
            }
            rfidInput.value = '';
            rfidInput.focus();
        })
        .catch(() => {
            displayFeedback('An error occurred. Please try again.', 'danger');
        });
    });

    function displayFeedback(message, type) {
        feedbackDiv.innerHTML = `<div class="alert alert-${type}" role="alert">${escapeHtml(message)}</div>`;
    }

    function escapeHtml(text) {
        return text.replace(/[&<>"']/g, function(m) {
            return ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            })[m];
        });
    }
});
</script>
