<?= $this->extend('layouts/grievance_layout') ?>

<?= $this->section('content') ?>

<div class="page-header my-4">
    <div>
        <h2>User List</h2>
        <small>View and manage all Users.</small>
    </div>
    <button id="btnOpenAdd" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Add User
    </button>
</div>

<div class="card py-2">
    <table id="tableUsers" class="table table-hover" style="width:100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<!-- Modal Add / Edit User -->
<div class="modal-overlay" id="modalUser">
    <div class="modal-box">

        <div class="modal-header">
            <h4><i class="bi bi-person-badge"></i> <span id="modalTitle">Add User</span></h4>
            <button type="button" class="modal-close" data-close="modalUser"><i class="bi bi-x-lg"></i></button>
        </div>

        <form id="userForm">
            <input type="hidden" id="userId" name="id">

            <div class="modal-body">

                <div class="form-grid two mb-3">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" id="name" placeholder="Full name" required>
                    </div>

                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" id="username" placeholder="Login username" required>
                    </div>
                </div>

                <div class="form-grid two mb-3">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" id="email" placeholder="Email address">
                    </div>

                    <div class="form-group">
                        <label>Role</label>
                        <select name="role" id="role" required>
                            <option value="garmen">Garmen</option>
                            <option value="socks">Socks</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label>Password</label>
                    <input type="password" name="password" id="password" placeholder="Min. 6 characters">
                    <small id="passwordHelp" style="display: block; margin-top: 5px; color: #6c757d; font-size: 0.85em;">
                        Leave blank if you don't want to change the password.
                    </small>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-soft" data-close="modalUser">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check2"></i> Save Changes</button>
            </div>

        </form>

    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="<?= base_url('assets/grievance/js/user.js') ?>"></script>
<?= $this->endSection() ?>