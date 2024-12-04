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

        <!-- Feedback Messages -->
        <div id="feedback" class="mt-3"></div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rfidForm = document.getElementById('rfid-form');
        const rfidInput = document.getElementById('rfid');
        const feedbackDiv = document.getElementById('feedback');

        rfidForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const rfid = rfidInput.value.trim();

            if (rfid === '') {
                displayFeedback('Please scan an RFID.', 'danger');
                return;
            }

            // Send RFID via AJAX
            fetch('<?= BASE_PATH ?>/rfid', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `rfid=${encodeURIComponent(rfid)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    displayFeedback(data.message, 'success');
                    // Optionally, display student info
                    if (data.student) {
                        feedbackDiv.innerHTML += `
                            <div class="mt-2">
                                <strong>Student:</strong> ${escapeHtml(data.student.firstname)} ${escapeHtml(data.student.lastname)}
                            </div>
                        `;
                    }
                } else {
                    displayFeedback(data.message, 'danger');
                }
                // Clear the input
                rfidInput.value = '';
                // Refocus the input
                rfidInput.focus();
            })
            .catch(error => {
                console.error('Error:', error);
                displayFeedback('An error occurred. Please try again.', 'danger');
            });
        });

        // Function to display feedback messages
        function displayFeedback(message, type) {
            feedbackDiv.innerHTML = `<div class="alert alert-${type}" role="alert">${escapeHtml(message)}</div>`;
        }

        // Function to escape HTML to prevent XSS
        function escapeHtml(text) {
            var map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"']/g, function(m) { return map[m]; });
        }

        // Optional: Auto-submit when RFID is scanned and Enter key is pressed
        rfidInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                rfidForm.dispatchEvent(new Event('submit'));
            }
        });
    });
</script>
