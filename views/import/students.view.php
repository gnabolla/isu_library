<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h4>Import Students</h4>
                </div>
                <div class="card-body">
                    <form action="<?= BASE_PATH ?>/import-students" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="excelFile" class="form-label">Excel File</label>
                            <input type="file" class="form-control" id="excelFile" name="excelFile" accept=".xlsx, .xls" required>
                            <div class="form-text">
                                Upload Excel file containing student records. 
                                <a href="<?= BASE_PATH ?>/templates/student_import_template.xlsx">Download Template</a>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Upload and Import</button>
                        <a href="<?= BASE_PATH ?>/setup" class="btn btn-secondary">Back to Setup</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
