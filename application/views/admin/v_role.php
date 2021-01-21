<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css">
<script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>


<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Menu Management</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Menu Management</li>
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
                    <button type="button" class="btn btn-primary mb-4" data-toggle="modal" data-target="#modalAddMenu">
                        Add new menu
                    </button>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped" id="menu_table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Menu</th>
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
<div class="modal fade" id="modalAddMenu" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add new menu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="formAddMenu">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="menu_name">Menu Name</label>
                        <input type="text" class="form-control" name="menu_name" id="menu_name" placeholder="Add menu name">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btnAddMenu">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalEditMenu" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add new menu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" id="formAddMenu">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="menu_name">Menu Name</label>
                        <input type="text" class="form-control" name="menu_name" id="menu_name" placeholder="Add menu name">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btnAddMenu">Add</button>
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
        table = $('#menu_table').DataTable({
            responsive: true,
            "destroy": true,
            "processing": true,
            "serverSide": true,
            "order": [],

            "ajax": {
                "url": "<?= base_url('admin/dashboard/serverside_get_menu') ?>",
                "dataType": "json",
                "data": {
                    [csrfName]: csrfHash
                },
                "type": "POST"
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


        $("#btnAddMenu").click(function(e) {
            e.preventDefault();
            var menu_name = $('#menu_name').val();
            if (menu_name == '' && menu_item < 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong!',
                });
            } else {
                $.ajax({
                    url: "<?= base_url('admin/dashboard/addMenu') ?>",
                    type: 'post',
                    dataType: 'json',
                    data: {
                        menu_name: menu_name,
                        [csrfName]: csrfHash
                    },
                    success: function(data) {
                        csrfName = data.csrfName;
                        csrfHash = data.csrfHash;
                        // alert(data.message);

                        $('#modalAddMenu').modal('hide');
                        $('#formAddMenu')[0].reset();
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

        $("#btnEditMenu").click(function() {
            $("modalEditMenu").modal();
        });


        $(document).on('click', "#btnDeleteMenu", function(e) {
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
                        url: '<?= base_url('admin/dashboard/deleteMenu') ?>',
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

        $(document).on('click', '#btnEditMenu', function(e) {
            e.preventDefault();
            var id = $(this).attr('value');


            $.ajax({
                url: '<?= base_url('admin/dashboard/editMenu') ?>',
                type: 'post',
                dataType: 'json',
                data: {
                    id_menu: id,
                    [csrfName]: csrfHash
                },
                success: function(data) {

                }
            })
        })
    });
</script>