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
        
        <div id="studentImageContainer" class="mt-3 text-center" style="display: none;">
            <img id="studentImage" class="img-fluid" style="max-height: 300px;" alt="Student Photo">
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const rfidForm = document.getElementById('rfid-form');
    const rfidInput = document.getElementById('rfid');
    const feedbackDiv = document.getElementById('feedback');
    const imageContainer = document.getElementById('studentImageContainer');
    const studentImage = document.getElementById('studentImage');
    let imageTimer = null;

    rfidForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const rfid = rfidInput.value.trim();
        if (rfid === '') {
            displayFeedback('Please scan an RFID.', 'danger');
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
                    feedbackDiv.innerHTML += `
                        <div class="mt-2">
                            <strong>Student:</strong> ${escapeHtml(data.student.firstname)} ${escapeHtml(data.student.lastname)}
                        </div>
                    `;
                    if (data.student.image) {
                        if (imageTimer) clearTimeout(imageTimer);
                        studentImage.src = data.student.image;
                        imageContainer.style.display = 'block';
                        imageTimer = setTimeout(() => {
                            imageContainer.style.display = 'none';
                            studentImage.src = '';
                        }, 5000);
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

    rfidInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            rfidForm.dispatchEvent(new Event('submit'));
        }
    });
});
</script>
