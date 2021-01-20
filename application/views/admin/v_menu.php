<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Menu Management</h1>
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
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalAddMenu">
                        Add new menu
                    </button>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Menu</th>
                                <th scope="col">Action</th>
                                <!-- <th class="col"></th> -->
                                <th scope="col">#</th>
                            </tr>
                        </thead>
                        <tbody>


                            <?php $i = 1;
                            foreach ($menu as $mn) :  ?>
                                <tr>
                                    <th scope="col"><?= $i ?></th>
                                    <td><?= $mn['menu'] ?></td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm" id="btnEditMenu">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" value="<?= $mn['id'] ?>" id="btnDeleteMenu">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>

                            <?php $i++;
                            endforeach ?>
                        </tbody>
                    </table>
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
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
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
    $(document).ready(function() {
        $("#btnAddMenu").click(function(e) {
            e.preventDefault();

            var menu_name = $('#menu_name').val();
            if (menu_name == '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong!',
                })
            } else {

                $.ajax({
                    url: "<?= base_url('admin/dashbord/addMenu') ?>",
                    type: 'post',
                    dataType: 'json',
                    data: $('formAddMenu').serialize(),
                    success: function(data) {
                        Swal.fire(
                            'Add new menu successfuly!',
                            'success'
                        )
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
                            id_menu: id
                        },
                        success: function(data) {

                            Swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                            )
                        }
                    });
                }
            })
        })
    });
</script>