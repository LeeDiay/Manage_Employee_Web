<?php include('../includes/header.php')?>
<?php

if (!isset($_SESSION['slogin']) || !isset($_SESSION['srole'])) {
    header('Location: ../index.php');
    exit();
}


$userRole = $_SESSION['srole'];
if ($userRole !== 'Manager' && $userRole !== 'Admin') {
    header('Location: ../index.php');
    exit();
}
?>

<body>
<style>
    .notification {
        display: none;
        position: fixed;
        top: 0;
        right: 0;
        padding: 10px;
        margin: 10px;
        border-radius: 4px;
        z-index: 9999;
        background-color: #AFF29A;
        border: 2px solid #35DB00;
        color: #104300;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    }

    .notification-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 5px;
        padding-bottom: 5px;
        border-bottom: 1px solid #35DB00;
    }

    .notification-icon {
        margin-right: 10px;
        font-size: 18px;
        line-height: 20px;
        text-align: center;
        width: 20px;
    }

    .notification-title {
        margin: 0;
        font-size: 16px;
    }

    .notification-message {
        margin-top: 5px;
        padding: 10px 0;
    }

    .swal2-container {
      z-index: 99999;
    }

    .notification-close {
        border: none;
        background-color: transparent;
        font-size: 18px;
        line-height: 20px;
        color: #0a0a0a;
        cursor: pointer;
    }

    .notification-close:hover {
        color: #0a0a0a;
    }

    .md-content h3 {
        color: #fff;
        margin: 0;
        padding: 0.3em;
        text-align: center;
        font-weight: 300;
        opacity: 0.7;
        background: #01a9ac;
        border-radius: 3px 3px 0 0;
}
</style>

<div style="display: none; width: 270px; right: 36px; top: 36px;" id="notification" class="notification success">
    <div class="notification-header">
        <div class="notification-icon"><i class="icofont icofont-info-circle"></i></div>
        <h4 class="notification-title">Success</h4>
        <button class="notification-close"><i class="icofont icofont-close-circled"></i></button>
    </div>
    <div class="notification-message">Success message goes here.</div>
</div>


<!-- Pre-loader start -->
<?php include('../includes/loader.php')?>
<!-- Pre-loader end -->
<div id="pcoded" class="pcoded">
    <div class="pcoded-overlay-box"></div>
    <div class="pcoded-container navbar-wrapper">

        <?php include('../includes/topbar.php')?>

        <div class="pcoded-main-container">
            <div class="pcoded-wrapper">
                
               <?php $page_name = "leave_type"; ?>
                <?php include('../includes/sidebar.php')?>

                <div class="pcoded-content">
                    <div class="pcoded-inner-content">
              
                        <!-- Main-body start -->
                        <div class="main-body">
                            <div class="page-wrapper">
                                <!-- Page-header start -->
                                <div class="page-header">
                                    <div class="row align-items-end">
                                        <div class="col-lg-8">
                                            <div class="page-header-title">
                                                <div class="d-inline"  id="pnotify-desktop-success">
                                                    <h4>Các loại nghỉ phép</h4>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                                <!-- Page-header end -->
                                    <!-- Page body start -->
                                    <div class="page-body">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <!-- Product list card start -->
                                                <div class="card">
                                                    <div class="card-header">
                                                        <button type="button" class="btn btn-primary waves-effect waves-light f-right d-inline-block md-trigger" data-modal="modal-13"> <i class="icofont icofont-plus m-r-5"></i> Thêm mới
                                                        </button>
                                                    </div>
                                                    <div class="tab-content">
                                                    <!-- tab pane contact start -->
                                                        <div class="tab-pane active" id="contacts" role="tabpanel">
                                                            <div class="row">
                                                                <div class="col-xl-12">
                                                                    <div class="row">
                                                                        <div class="col-sm-12">
                                                                            <!-- contact data table card start -->
                                                                            <div class="card">
                                                                                <div class="card-block contact-details">
                                                                                    <div class="data_table_main table-responsive dt-responsive">
                                                                                        <table id="simpletable" class="table  table-striped table-bordered nowrap">
                                                                                            <thead>
                                                                                            <tr>
                                                                                                <th>ID</th>
                                                                                                <th>Loại nghỉ phép</th>
                                                                                                <th>Mô tả</th>
                                                                                                <th>Thời gian</th>
                                                                                                <th>Trạng thái</th>
                                                                                                <th>Ngày tạo</th>
                                                                                                <th>Hành động</th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                            <?php
                                                                                            $sql = "SELECT * FROM tblleavetype";
                                                                                            $result = mysqli_query($conn, $sql);
                                                                                            if (mysqli_num_rows($result) > 0) {
                                                                                                $count = 1;
                                                                                                while ($row = mysqli_fetch_assoc($result)) {
                                                                                                    ?>
                                                                                                    <tr>
                                                                                                        <td><?php echo $count; ?></td>
                                                                                                        <td>
                                                                                                          <span style="font-weight: 600;"><?php echo $row['leave_type']; ?></span>    
                                                                                                        </td>
                                                                                                        <td><?php echo $row['description']; ?></td>
                                                                                                        <td><?php echo $row['assign_days']; ?></td>
                                                                                                        <td>
                                                                                                            <?php
                                                                                                            $status = $row['status'];
                                                                                                            if ($status == 1) {
                                                                                                                echo '<span style="color: green; font-weight: 600;">Active</span>';
                                                                                                            } else {
                                                                                                                echo '<span style="color: red; font-weight: 600;">Inactive</span>';
                                                                                                            }
                                                                                                            ?>
                                                                                                        </td>
                                                                                                        <td>
                                                                                                            <?php
                                                                                                            $createDate = date("jS F, Y - H:i", strtotime($row['creation_date']));
                                                                                                            echo $createDate;
                                                                                                            ?>
                                                                                                        </td>
                                                                                                        <td class="action-icon">
                                                                                                            <a href="#" data-modal="modal-13" class="m-r-15 text-muted edit-btn md-trigger" data-toggle="tooltip" data-placement="top" title="" data-original-title="Sửa" data-id="<?php echo $row['id']; ?>" data-name="<?php echo $row['leave_type']; ?>" data-description="<?php echo $row['description']; ?>" data-assigned="<?php echo $row['assign_days']; ?>" data-status="<?php echo $row['status']; ?>">
                                                                                                            <i class="icofont icofont-ui-edit"></i></a>
                                                                                                            <a href="#" class="delete-btn text-muted" data-toggle="tooltip" data-placement="top" title="" data-original-title="Xóa" data-id="<?php echo $row['id']; ?>"><i class="icofont icofont-ui-delete"></i></a>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    <?php
                                                                                                    $count++;
                                                                                                }
                                                                                            } else {
                                                                                                ?>
                                                                                                <tr>
                                                                                                    <td colspan="4" class="text-center">
                                                                                                        <img src="..\files\assets\images\no_data.png" class="img-radius" alt="No Data Found" style="width: 200px; height: auto;">
                                                                                                    </td>
                                                                                                </tr>
                                                                                                <?php
                                                                                            }
                                                                                            ?>
                                                                                        </tbody>

                                                                                        </table>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <!-- contact data table card end -->
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Product list card end -->
                                            </div>
                                        </div>
                                        <!-- Add Contact Start Model start-->
                                           <div class="md-modal md-effect-13 addcontact" id="modal-13">
                                                <div class="md-content" style="max-width: 400px;">
                                                    <h3 class="f-26">Thêm loại nghỉ phép mới</h3>
                                                    <div >
                                                         <input hidden type="text" class="form-control type-id" name="type-id">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="icofont icofont-bank-alt"></i></span>
                                                            <input type="text" class="form-control dname" name="dname" placeholder="Tên">
                                                        </div>
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="icofont icofont-social-ebuddy"></i></span>
                                                            <input type="text" class="form-control assigned" name="assigned" placeholder="Số ngày">
                                                        </div>
                                                        
                                                        <div class="input-group">
                                                            <textarea class="form-control description" name="description" placeholder="Mô tả" spellcheck="false" rows="5"></textarea>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-sm-12">
                                                                <div class="form-radio">
                                                                    <div class="radio radiofill radio-inline">
                                                                        <label>
                                                                            <input type="radio" class="status" name="status" value="1">
                                                                            <i class="helper"></i>Hoạt động
                                                                        </label>
                                                                    </div>
                                                                    <div class="radio radiofill radio-inline">
                                                                        <label>
                                                                            <input type="radio" class="status" name="status" value="2">
                                                                            <i class="helper"></i>Không hoạt động
                                                                        </label>
                                                                    </div>
                                                                    
                                                                </div>
                                                            </div>
                                                        </div>
                                                                        
                                                        <div class="text-center">
                                                            <button type="submit"  id="save-btn" class="btn btn-primary waves-effect m-r-20 f-w-600 d-inline-block save_btn">Lưu</button>
                                                            <button type="submit" id="update-btn" class="btn btn-primary waves-effect m-r-20 f-w-600 update_btn" style="display:none;">Cập nhật</button>
                                                            <button type="button" class="btn btn-primary waves-effect m-r-20 f-w-600 md-close d-inline-block close_btn">Đóng</button>
                                                        </div>
                                                    </div>
                                               </div>
                                          </div>
                                        <div class="md-overlay"></div>
                                        <!-- Add Contact Ends Model end-->
                                    </div>
                                    <!-- Page body end -->
                                </div>
                            </div>
                            <!-- Main-body end -->
                            <div id="styleSelector">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Required Jquery -->
    <?php include('../includes/scripts.php')?>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-23581568-13');
    </script>
    <script type="text/javascript">
        $('#save-btn').click(function(event){
            event.preventDefault(); 
            (async () => {
                var data = {
                    dname: $('.dname').val(),
                    description: $('.description').val(), 
                    assigned: $('.assigned').val(),
                    status: $('input[name="status"]:checked').val(),
                    action: "save",
                };
                if (data.dname.trim() === '' || data.description.trim() === '' || data.assigned.trim() === '' || data.status.trim() === '') {
                    Swal.fire({
                        icon: 'warning',
                        text: 'Vui lòng điền đẩy đủ thông tin',
                        confirmButtonColor: '#ffc107',
                        confirmButtonText: 'OK'
                    });
                    return;
                }
                $.ajax({
                    url: 'leave_type_functions.php',
                    type: 'post',
                    data: data,
                    success:function(response){
                        response = JSON.parse(response);
                        if (response.status == 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Thêm thành công',
                                html:
                                'Name : ' + data['dname'],
                                confirmButtonColor: '#01a9ac',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $('.md-close').trigger('click'); 
                                    location.reload();
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                text: response.message,
                                confirmButtonColor: '#eb3422',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                    }
                });
            })()
        })
    </script>
    <script type="text/javascript">
       
        $('.edit-btn').click(function() {
            
            var id = $(this).data('id');
            var name = $(this).data('name');
            var description = $(this).data('description');
            var assigned = $(this).data('assigned');
            var status = $(this).data('status');
            
            
            $('#modal-13 .type-id').val(id);
            $('#modal-13 .dname').val(name);
            $('#modal-13 .assigned').val(assigned);
            $('#modal-13 .description').val(description);
            if (status === 1) {
                $('#modal-13 .status[value="1"]').prop('checked', true);
            } else if (status === 2) {
                $('#modal-13 .status[value="2"]').prop('checked', true);
            }
            
            
            $('.md-modal[data-modal="modal-13"]').addClass('md-show');

            $('#save-btn').removeClass('d-inline-block').hide();
            $('#update-btn').addClass('d-inline-block').show();

        });

        $('#update-btn').click(function(event){
            event.preventDefault(); 
            (async () => {
                var data = {
                    id: $('.type-id').val(),
                    dname: $('.dname').val(),
                    description: $('.description').val(),
                    assigned: $('.assigned').val(),
                    status: $('input[name="status"]:checked').val(),
                    action: "update",
                };
                if (data.dname.trim() === '' || data.description.trim() === '' || data.assigned.trim() === '' || data.status.trim() === '') {
                    Swal.fire({
                        icon: 'warning',
                        text: 'Vui lòng điền đầy đủ thông tin',
                        confirmButtonColor: '#ffc107',
                        confirmButtonText: 'OK'
                    });
                    return;
                }
                $.ajax({
                    url: 'leave_type_functions.php',
                    type: 'post',
                    data: data,
                    success:function(response){
                        response = JSON.parse(response);
                        if (response.status == 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Cập nhật thành công',
                                html:
                                'Tên : ' + data['dname'],
                                confirmButtonColor: '#01a9ac',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $('.md-close').trigger('click'); 
                                    location.reload();
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                text: response.message,
                                confirmButtonColor: '#eb3422',
                                confirmButtonText: 'OK'
                            });
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
                    }
                });
            })()
        })
    </script>
    <script type="text/javascript">
        $('.delete-btn').click(function(){
        (async () => {
            const { value: formValues } = await Swal.fire({
                title: 'Bạn có chắc không?',
                text: "Bạn sẽ không thể khôi phục lại nó!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Chắc chắn!'
            })

            if (formValues) {
            var data = {
                id: $(this).data("id"),
                action: "delete"
            };

            $.ajax({
                url: 'leave_type_functions.php',
                type: 'post',
                data: data,
                success: function(response) {
                    const responseObject = JSON.parse(response);
                    console.log(`RESPONSE HERE: ${responseObject.status}`);
                    if (response && responseObject.status === 'success') {
                        
                        Swal.fire(
                            'Đã xóa!',
                            'Xóa nghỉ phép thành công',
                            'success'
                        ).then((result) => {
                                if (result.isConfirmed) {
                                   
                                    location.reload();
                                }
                        });
                        
                    } else {
                        
                        Swal.fire(
                            'Error!',
                            'Xóa thất bại',
                            'error'
                        );
                    }
                },
                error: function(xhr, status, error) {
                    console.log("AJAX error: " + error);
                    Swal.fire('Error!', 'Xóa thất bại.', 'error');
                }

            });
          }
        })()
    })
    </script>

</body>

</html>
