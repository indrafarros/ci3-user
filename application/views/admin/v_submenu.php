<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css">
<script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>


<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Submenu Management</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Submenu Management</li>
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
                    <button type="button" class="btn btn-primary mb-4" data-toggle="modal" data-target="#modalAddSubMenu">
                        Add new menu
                    </button>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped" id="menu_table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Menu Title</th>
                                    <th scope="col">Menu ID</th>
                                    <th scope="col">Link Url</th>
                                    <th scope="col">Icon</th>
                                    <th scope="col">Active</th>
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
<div class="modal fade" id="modalAddSubMenu" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add new submenu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="formAddSubMenu">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="submenu_name">Submenu Name</label>
                        <input type="text" class="form-control" name="submenu_name" id="submenu_name" placeholder="Add submenu name">
                    </div>
                    <div class="form-group">
                        <label for="submenu_id">Position Submenu</label>
                        <select name="submenu_id" id="submenu_id" class="form-control">
                            <option value="">Choose ..</option>
                            <?php foreach ($menu_title as $mn) : ?>
                                <option value="<?= $mn['id'] ?>"><?= $mn['menu'] ?></option>

                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="url_name">URL</label>
                        <input type="text" class="form-control" name="url_name" id="url_name" placeholder="Add url name">
                    </div>
                    <div class="form-group">
                        <label for="icon_sub">Icon</label>
                        <input type="text" class="form-control" name="icon_sub" id="icon_sub" placeholder="Add icon">
                        <span class="text-sm text-danger font-italic font-weight-bold pl-1">Example : fas fa-user</span><br>
                        <span class="text-sm text-danger font-italic font-weight-bold pl-1">Visit https://fontawesome.com/ to see other icons</span>
                    </div>
                    <div class="form-group">
                        <label for="is_active">Active</label>
                        <select name="is_active" id="is_active" class="form-control">
                            <option value="0">Not Active</option>
                            <option value="1">Active</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btnAddSubMenu">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit-->
<div class="modal fade" id="modalEditSubMenu" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add new menu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="formSubmitSubMenu">
                <div class="modal-body">
                    <input type="text" name="id_edit_submenu" id="id_edit_submenu" value="">
                    <div class="form-group">
                        <label for="submenu_edit_name">Submenu Name</label>
                        <input type="text" class="form-control" name="submenu_edit_name" id="submenu_edit_name" placeholder="Add submenu name">
                    </div>
                    <div class="form-group">
                        <label for="submenu_edit_id">Position Submenu</label>
                        <select name="submenu_edit_id" id="submenu_edit_id" class="form-control">
                            <option value="">Choose ..</option>
                            <?php foreach ($menu_title as $mn) : ?>
                                <option value="<?= $mn['id'] ?>"><?= $mn['menu'] ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_url_name">URL</label>
                        <input type="text" class="form-control" name="edit_url_name" id="edit_url_name" placeholder="Add url name">
                    </div>
                    <div class="form-group">
                        <label for="edit_icon_sub">Icon</label>
                        <input type="text" class="form-control" name="edit_icon_sub" id="edit_icon_sub" placeholder="Add icon">
                        <span class="text-sm text-danger font-italic font-weight-bold pl-1">Example : fas fa-user</span><br>
                        <span class="text-sm text-danger font-italic font-weight-bold pl-1">Visit https://fontawesome.com/ to see other icons</span>
                    </div>
                    <div class="form-group">
                        <label for="edit_is_active">Active</label>
                        <select name="edit_is_active" id="edit_is_active" class="form-control">
                            <option value="0">Not Active</option>
                            <option value="1">Active</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btnSubmitSubMenu">Add</button>
                </div>
            </form>
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
                "url": "<?= base_url('admin/dashboard/serverside_get_submenu') ?>",
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
                },
                {
                    "targets": [3],
                    "width": "200px"
                },
                {
                    "targets": [4],
                    "width": "200px"
                },
                {
                    "targets": [5],
                    "width": "200px"
                },
                {
                    "targets": [6],
                    "width": "200px"
                },
            ],

        });
    }
    $(document).ready(function() {

        $("#btnAddSubMenu").click(function(e) {

            e.preventDefault();
            // alert('test');
            var submenu_name = $('#submenu_name').val();
            var submenu_id = $('#submenu_id').val();
            var url_name = $('#url_name').val();
            var icon_sub = $('#icon_sub').val();
            var is_active = $('#is_active').val();
            if (submenu_name == '' || submenu_id == '' || url_name == '' || icon_sub == '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong!',
                });
            } else {
                $.ajax({
                    url: "<?= base_url('admin/dashboard/addSubMenu') ?>",
                    type: 'post',
                    dataType: 'json',
                    data: {
                        submenu_name: submenu_name,
                        submenu_id: submenu_id,
                        url_name: url_name,
                        icon_sub: icon_sub,
                        is_active: is_active,
                        [csrfName]: csrfHash
                    },
                    success: function(data) {
                        csrfName = data.csrfName;
                        csrfHash = data.csrfHash;
                        // alert(data.message);

                        $('#modalAddSubMenu').modal('hide');
                        $('#formAddSubMenu')[0].reset();
                        Swal.fire(
                            'Add new menu successfuly!',
                            'success'
                        )
                        // console.log(data);
                        data_table();
                    }
                })
            }
        });

        $(document).on('click', "#btnDeleteSubMenu", function(e) {
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
                        url: '<?= base_url('admin/dashboard/deleteSubMenu') ?>',
                        type: 'post',
                        dataType: 'json',
                        data: {
                            id_menu: id,
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
        // Get menu, id from dashboard controller
        $(document).on('click', '#btnEditSubMenu', function(e) {
            e.preventDefault();
            var id = $(this).data('id');


            $.ajax({
                url: '<?= base_url('admin/dashboard/getEditSubMenu') ?>',
                type: 'post',
                dataType: 'json',
                data: {
                    id_menu: id,
                    [csrfName]: csrfHash
                },
                success: function(data) {

                    csrfName = data.csrfName;
                    csrfHash = data.csrfHash;
                    $('#modalEditSubMenu').modal('show');

                    // $('#id_edit_name').val(data.menu.id);
                    $('#id_edit_submenu').val(data.menu.id);
                    $('#submenu_edit_name').val(data.menu.menu_title);
                    $('#submenu_edit_id').val(data.menu.menu_id);
                    $('#edit_url_name').val(data.menu.link_url);
                    $('#edit_icon_sub').val(data.menu.icon_sub);
                    $('#edit_is_active').val(data.menu.is_active);
                    console.log(data);
                }
            })
        });

        $(document).on('click', '#btnSubmitSubMenu', function(e) {
            e.preventDefault();

            var id = $('#id_edit_submenu').val();
            var submenu_edit_name = $('#submenu_edit_name').val();
            var submenu_edit_id = $('#submenu_edit_id').val();
            var edit_url_name = $('#edit_url_name').val();
            var edit_icon_sub = $('#edit_icon_sub').val();
            var edit_is_active = $('#edit_is_active').val();

            if (id == '' || submenu_edit_name == '' || edit_url_name == '' || edit_icon_sub == '' || edit_url_name == '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong!',
                });
            } else {
                // let form = $('#formSubmitMenu').serialize();
                $.ajax({
                    url: '<?= base_url('admin/dashboard/submitEditSubMenu') ?>',
                    dataType: 'json',
                    type: 'post',
                    data: {
                        id_edit_submenu: id,
                        submenu_edit_name: submenu_edit_name,
                        submenu_edit_id: submenu_edit_id,
                        edit_url_name: edit_url_name,
                        edit_icon_sub: edit_icon_sub,
                        edit_is_active: edit_is_active,
                        [csrfName]: csrfHash
                    },
                    success: function(data) {
                        csrfName = data.csrfName;
                        csrfHash = data.csrfHash;
                        $('#modalEditSubMenu').modal('hide');

                        // $('#modalEditMenu').modal('hide');
                        $('#formSubmitSubMenu')[0].reset();
                        data_table();
                    }
                })
            }


        })
    });
</script>