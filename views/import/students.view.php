<?php
// views/import/students.view.php
?>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h4>Import Students</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($_GET['error'])): ?>
                        <div class="alert alert-danger">
                            Error processing file: <?= htmlspecialchars($_GET['error']) ?>
                            <div class="mt-2">
                                <a href="<?= BASE_PATH ?>/import-students/debug" class="btn btn-sm btn-danger">
                                    Run Diagnostics
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($_GET['success'])): ?>
                        <div class="alert alert-success">
                            Import completed successfully!
                            <?php if (isset($_GET['added']) || isset($_GET['skipped'])): ?>
                                <br>
                                <strong><?= isset($_GET['added']) ? (int)$_GET['added'] : 0 ?></strong> students added.
                                <strong><?= isset($_GET['skipped']) ? (int)$_GET['skipped'] : 0 ?></strong> students skipped (already exist).
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="mb-4">
                        <h5>Select Registrar System</h5>
                        
                        <div class="card mb-3">
                            <div class="card-body">
                                <h6>Import from SACARIAS</h6>
                                <p class="text-muted small">Use this option to import students from the SACARIAS system</p>
                                <form action="<?= BASE_PATH ?>/import-students/sacarias" method="post" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label for="excelFile1" class="form-label">Upload Excel File (SACARIAS)</label>
                                        <input type="file" class="form-control" id="excelFile1" name="excelFile" accept=".xlsx, .xls" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Import SACARIAS Data</button>
                                </form>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-body">
                                <h6>Import from SARIAS</h6>
                                <p class="text-muted small">Use this option to import students from the SARIAS system</p>
                                <form action="<?= BASE_PATH ?>/import-students/sarias" method="post" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label for="excelFile2" class="form-label">Upload Excel File (SARIAS)</label>
                                        <input type="file" class="form-control" id="excelFile2" name="excelFile" accept=".xlsx, .xls" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Import SARIAS Data</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <a href="<?= BASE_PATH ?>/setup" class="btn btn-secondary">Back to Setup</a>
                </div>
            </div>
        </div>
    </div>
</div>