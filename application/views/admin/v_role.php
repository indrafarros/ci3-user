<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css">
<script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><?= $title ?></h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Role Management</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>

<div class="container-fluid">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <button type="button" class="btn btn-primary mb-4" data-toggle="modal" data-target="#modalAddRole">
                        Add new access
                    </button>
                    <p><?= $role_name['role_access'] ?></p>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped" id="menu_table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Role Name</th>
                                    <!-- <th class="col"></th> -->
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.row -->
    <!-- Main row -->
    <!-- /.row (main row) -->
</div><!-- /.container-fluid -->

<!-- Modal -->
<div class="modal fade" id="modalAddRole" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add new access</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="formAddRole">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="role_name">Role Name</label>
                        <input type="text" class="form-control" name="role_name" id="role_name" placeholder="Add role name">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btnAddRole">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit-->
<div class="modal fade" id="modalEditRole" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit role access</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="formSubmitMenu">
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                        <input type="hidden" name="id_role_access" id="id_role_access" value="">
                        <label for="role_edit_name">Role Name</label>
                        <input type="text" class="form-control" name="role_edit_name" id="role_edit_name" placeholder="Add menu name">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btnSubmitRole">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal View Access-->
<div class="modal fade" id="modalViewAccessRole" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit role access</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-hover" id="tableView">
                    <thead>
                        <tr>
                            <th scope="row">No</th>
                            <th>Menu Name</th>
                            <th>#</th>
                            <!-- <th>Action</th> -->
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btnSubmitRole">Add</button> -->
            </div>

        </div>
    </div>
</div>

<script>
    var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>',
        csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';

    data_table();

    function data_table() {
        var table = $('#menu_table').DataTable({
            "responsive": true,
            "destroy": true,
            "processing": true,
            "serverSide": true,
            "order": [],

            "ajax": {
                "url": "<?= base_url('admin/dashboard/serverside_role_access') ?>",
                "dataType": "json",
                "type": "POST",


            },
            "columnDefs": [{
                    "targets": [0],
                    "orderable": false,
                    "width": "5"
                },
                {
                    "targets": [1],
                    "width": "auto"
                },
                {
                    "targets": [2],
                    "width": "200px"
                }
            ],

        });
    }
    $(document).ready(function() {

        $(document).on("click", "#btnViewRole", function(e) {
            let table = $('#tableView tbody');
            var id = $(this).attr('value');
            $.ajax({
                url: "<?= base_url('admin/dashboard/viewRole') ?>",
                type: "post",
                dataType: "json",
                data: {
                    id_role: id,

                    [csrfName]: csrfHash
                },
                beforeSend: function() {
                    table.empty();
                },
                success: function(data) {
                    csrfName = data.csrfName;
                    csrfHash = data.csrfHash;
                    console.log(data);
                    $('#modalViewAccessRole').modal('show');
                    let id = 0;
                    $.each(data.menu_title, function(id, item) {
                        id++
                        table.append("<tr><td>" + id + "</td><td>" + item.menu + "</td><td><input class='form-check-input' type='checkbox'> </td></tr>");
                    });
                }
            });
        });

        $("#btnAddRole").click(function(e) {
            e.preventDefault();
            var role_name = $('#role_name').val();
            if (role_name == '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong!',
                });
            } else {
                $.ajax({
                    url: "<?= base_url('admin/dashboard/addRole') ?>",
                    type: 'post',
                    dataType: 'json',
                    data: {
                        role_name: role_name,
                        [csrfName]: csrfHash
                    },
                    success: function(data) {
                        csrfName = data.csrfName;
                        csrfHash = data.csrfHash;

                        $('#modalAddRole').modal('hide');
                        $('#formAddRole')[0].reset();
                        Swal.fire(
                            'Add new role successfuly!',
                            'success'
                        )
                        data_table();
                    }
                })
            }
        });

        $(document).on('click', "#btnDeleteRole", function(e) {
            e.preventDefault();
            var id = $(this).attr('value');

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= base_url('admin/dashboard/deleteRole') ?>',
                        type: 'post',
                        dataType: 'json',
                        data: {
                            id_role: id,
                            [csrfName]: csrfHash
                        },
                        success: function(data) {
                            csrfName = data.csrfName;
                            csrfHash = data.csrfHash;
                            Swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                            );
                            data_table();
                        }
                    });
                }
            })
        });
        // Get menu
        $(document).on('click', '#btnEditRole', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            $.ajax({
                url: '<?= base_url('admin/dashboard/getEditRole') ?>',
                type: 'post',
                dataType: 'json',
                data: {
                    id_role: id,
                    [csrfName]: csrfHash
                },
                success: function(data) {
                    csrfName = data.csrfName;
                    csrfHash = data.csrfHash;
                    $('#modalEditRole').modal('show');
                    $('#id_role_access').val(data.role.id);
                    $('#role_edit_name').val(data.role.role_access);
                }
            })
        });

        $(document).on('click', '#btnSubmitRole', function(e) {
            e.preventDefault();

            var id = $('#id_edit_name').val();
            var menu_name = $('#menu_edit_name').val();

            if (id == '' || menu_name == '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong!',
                });
            } else {
                $.ajax({
                    url: '<?= base_url('admin/dashboard/submitRoleAccess') ?>',
                    dataType: 'json',
                    type: 'post',
                    data: {
                        id_edit_name: id,
                        menu_edit_name: menu_name,
                        [csrfName]: csrfHash
                    },
                    success: function(data) {
                        csrfName = data.csrfName;
                        csrfHash = data.csrfHash;
                        $('#modalEditMenu').modal('hide');
                        $('#formSubmitMenu')[0].reset();
                        data_table();
                    }
                })
            }
        })
    });
</script>