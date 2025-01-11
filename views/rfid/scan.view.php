<!-- views/rfid/scan.view.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ISU Roxas | Automated Library Login System</title>
    <style>
        /* Google Font */
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;600&display=swap');

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Roboto', sans-serif;
        }

        body {
            height: 100vh;
            background: linear-gradient(135deg, #0F2027 0%, #203A43 50%, #2C5364 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        /* Outer Wrapper to control animations */
        .scan-wrapper {
            position: relative;
            width: 800px;
            height: 480px;
        }

        .scan-wrapper * {
            transition: transform 0.6s ease, opacity 0.6s ease;
        }

        /* Panel: Scanning Section */
        .scan-panel {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 380px;
            background-color: rgba(255, 255, 255, 0.10);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            padding: 2rem;
            text-align: center;
            color: #fff;
        }

        .scan-panel .header-logo {
            width: 80px;
            margin-bottom: 1rem;
        }

        .scan-panel h1 {
            font-size: 1.6rem;
            font-weight: 600;
        }

        .scan-panel h2 {
            font-size: 1.2rem;
            font-weight: 400;
            opacity: 0.85;
        }

        .scan-panel .subtitle {
            margin: 1rem 0;
            font-size: 1rem;
            font-weight: 500;
            opacity: 0.9;
        }

        .form-group {
            margin: 1.5rem 0;
        }

        .form-control {
            width: 100%;
            padding: 0.8rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            outline: none;
        }

        .btn-submit {
            margin-top: 1rem;
            width: 100%;
            padding: 0.9rem;
            border: none;
            border-radius: 8px;
            background-color: #F0A500;
            color: #fff;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
        }
        .btn-submit:hover {
            background-color: #d68f00;
        }

        .alert {
            margin-top: 1rem;
            padding: 0.8rem;
            border-radius: 8px;
            font-weight: 500;
        }
        .alert-success {
            background-color: rgba(76, 175, 80, 0.1);
            color: #4CAF50;
            border: 1px solid #4CAF50;
        }
        .alert-danger {
            background-color: rgba(244, 67, 54, 0.1);
            color: #F44336;
            border: 1px solid #F44336;
        }

        /* Panel: Student Info Section */
        .info-panel {
            position: absolute;
            top: 50%;
            right: -100%;
            transform: translate(0, -50%);
            width: 380px;
            background-color: rgba(255, 255, 255, 0.10);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            padding: 2rem;
            text-align: center;
            color: #fff;
            opacity: 0;
        }

        .student-photo {
            max-height: 180px;
            border-radius: 8px;
            margin-bottom: 1rem;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        }
        .student-name {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 0.3rem;
        }
        .student-dept {
            margin-bottom: 0.2rem;
            font-size: 1rem;
        }
        .student-logtype {
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }
        .student-time {
            font-size: 0.9rem;
            opacity: 0.8;
        }

        /* When showing the info panel, shift scanning panel left and slide info in */
        .scan-wrapper.show-info .scan-panel {
            transform: translate(-120%, -50%);
        }
        .scan-wrapper.show-info .info-panel {
            right: 0;
            transform: translate(0, -50%);
            opacity: 1;
        }
    </style>
</head>
<body>

<div class="scan-wrapper" id="scanWrapper">
    <!-- Scanning Panel -->
    <div class="scan-panel" id="scanPanel">
        <img src="assets/img/school-logo.png" alt="School Logo" class="header-logo">
        <h1>Isabela State University</h1>
        <h2>Roxas Campus</h2>
        <div class="subtitle">Automated Library Login System</div>

        <form id="rfid-form">
            <div class="form-group">
                <input type="text" id="rfid" name="rfid" class="form-control"
                       placeholder="Tap or Enter RFID Here" autofocus autocomplete="off" required>
            </div>
            <button type="submit" class="btn-submit">Submit</button>
        </form>

        <div id="feedback"></div>
    </div>

    <!-- Student Info Panel -->
    <div class="info-panel" id="studentInfo">
        <img id="studentImage" class="student-photo" alt="Student Photo">
        <div class="student-name" id="studentName"></div>
        <div class="student-dept" id="studentDepartment"></div>
        <div class="student-logtype" id="logType"></div>
        <div class="student-time" id="scanTime"></div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const rfidForm        = document.getElementById('rfid-form');
    const rfidInput       = document.getElementById('rfid');
    const feedbackDiv     = document.getElementById('feedback');
    const scanWrapper     = document.getElementById('scanWrapper');
    const studentInfo     = document.getElementById('studentInfo');
    const studentImage    = document.getElementById('studentImage');
    const studentName     = document.getElementById('studentName');
    const studentDept     = document.getElementById('studentDepartment');
    const logType         = document.getElementById('logType');
    const scanTime        = document.getElementById('scanTime');
    let hideTimer         = null;

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
                    logType.textContent = (data.log_type === 'in')
                        ? 'You have successfully TIMED IN.'
                        : 'You have successfully TIMED OUT.';

                    scanTime.textContent = data.date_time
                        ? 'Date & Time: ' + data.date_time
                        : '';

                    // Load student image
                    if (data.student.image) {
                        studentImage.src = data.student.image;
                        studentImage.alt = data.student.firstname + ' ' + data.student.lastname;
                    } else {
                        studentImage.src = '';
                    }

                    // Show info panel with animation
                    scanWrapper.classList.add('show-info');

                    // Reset any existing timer
                    if (hideTimer) clearTimeout(hideTimer);
                    // Hide info panel after 6 seconds
                    hideTimer = setTimeout(() => {
                        hideInfoPanel();
                    }, 6000);
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
        const alertClass = (type === 'success') ? 'alert-success' : 'alert-danger';
        feedbackDiv.innerHTML = `<div class="alert ${alertClass}">${escapeHtml(message)}</div>`;
    }

    function hideInfoPanel() {
        // Slide everything back to center
        scanWrapper.classList.remove('show-info');
        // Clear the student image to avoid overlapping old images on next scan
        studentImage.src = '';
        feedbackDiv.innerHTML = '';
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
</body>
</html>
