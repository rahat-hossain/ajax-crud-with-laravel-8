<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> 

    <title>Laravel 8 Ajax CRUD</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

</head>

<body>
    <div style="padding: 30px;"></div>
    <div class="container">
        <h2 style="color: red;">
            <marquee>Laravel 8 Ajax CRUD Application</marquee>
        </h2>
        <div class="row">
            <div class="col-sm-8">
                <div class="card">
                    <div class="card-header">
                        All Teacher
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Title</th>
                                    <th>Institute</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- <tr>
                                    <td>1</td>
                                    <td>Rahat Hossain</td>
                                    <td>web developer</td>
                                    <td>Grabsoft</td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            <a href="" type="button" class="btn btn-primary"><i class="fa fa-edit"></i></a>
                                            <a href="" type="button" class="btn btn-danger"><i class="fa fa-trash" aria-hidden="true"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr> --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card">
                    <div class="card-header">
                        <span id="addT">Add new teacher</span>
                        <span id="updateT">Update teacher</span>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" value="" id="name" name="name" placeholder="Enter name">
                            <span class="text-danger" id="nameError"></span>
                        </div>
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" class="form-control" value="" id="title" name="title" placeholder="Enter title">
                            <span class="text-danger" id="titleError"></span>
                        </div>
                        <div class="form-group">
                            <label>Institute</label>
                            <input type="text" class="form-control" value="" id="institute" name="institute" placeholder="Enter institute">
                            <span class="text-danger" id="instituteError"></span>
                        </div>
                        <input type="hidden" value="" id="id" name="id">
                        <button type="submit" id="addButton" onclick="addData()" class="btn btn-success">Add</button>
                        <button type="submit" id="updateButton" onclick="updateData()" class="btn btn-warning">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


<script>
    $('#addT').show();                   
    $('#addButton').show();                   
    $('#updateT').hide();                   
    $('#updateButton').hide();


// =============== set csrf token start ===============
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
            }
    }); 
// =============== set csrf token end ===============    


// =============== get all data from db start ===============
    function allData()
    {
        $.ajax({
            type : "GET",
            dataType : "json",
            url : "/teacher/all",
            success : function(response){
                var data = ""
                $.each(response, function(key, value){
                    data = data + "<tr>"
                        data = data + "<td>"+value.id+"</td>"
                        data = data + "<td>"+value.name+"</td>"
                        data = data + "<td>"+value.title+"</td>"
                        data = data + "<td>"+value.institute+"</td>"
                        data = data +  "<td>"
                            data = data + "<button class='btn btn-primary mr-1' onclick='editData("+value.id+")'><i class='fa fa-edit'></i></button>"
                            data = data + "<button class='btn btn-danger' onclick='deleteData("+value.id+")'><i class='fa fa-trash'></i></button>"
                        data = data +  "</td>"
                    data = data + "</tr>"
                })
                $('tbody').html(data);
            }
        })
    }   
    allData();    
// =============== get all data from db end ===============


// =============== clear data from form field start ===============
    function clearData()
    {
        $('#name').val('');
        $('#title').val('');
        $('#institute').val('');
        $('#nameError').text('');
        $('#titleError').text('');
        $('#instituteError').text('');
    }
// =============== clear data from form field end  ===============


// =============== insert data into db start ===============
    function addData()
    {
        var name = $('#name').val();
        var title = $('#title').val();
        var institute = $('#institute').val();
        
        $.ajax({
            type : "POST",
            dataType : "json",
            data : {name:name, title:title, institute:institute},
            url : "/teacher/store/",
            success : function(data){
                clearData();
                allData();
            //-- start alert --
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Data added successfully',
                    showConfirmButton: false,
                    timer: 1500
                })
            //-- end alert --
                console.log("successfully added data");
            },

            error : function(error){
                $('#nameError').text(error.responseJSON.errors.name);
                $('#titleError').text(error.responseJSON.errors.title);
                $('#instituteError').text(error.responseJSON.errors.institute);
            }
        })
    } 
// =============== insert data into db end =============== 


// =============== edit data start ===============
    function editData(id)
    {
        // alert(id);
        $.ajax({
            type : "GET",
            dataType : "json",
            url : "/teacher/edit/"+id,
            success : function(data){

                $('#addT').hide();                   
                $('#addButton').hide();                   
                $('#updateT').show();                   
                $('#updateButton').show();

                $('#id').val(data.id);
                $('#name').val(data.name);
                $('#title').val(data.title);
                $('#institute').val(data.institute);
                console.log(data);
            }
        })
    }
// =============== edit data end ===============


// =============== Update data start ===============
    function updateData()
    {
        var id = $('#id').val();
        var name = $('#name').val();
        var title = $('#title').val();
        var institute = $('#institute').val();
        $.ajax({
            type : "POST",
            dataType : "json",
            data : {name:name, title:title, institute:institute},
            url : "/teacher/update/"+id,
            success : function(data){
                
                $('#addT').show();                   
                $('#addButton').show();                   
                $('#updateT').hide();                   
                $('#updateButton').hide();

                clearData();
                allData();
            //-- start alert --
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Data updated successfully',
                    showConfirmButton: false,
                    timer: 1500
                })
            //-- end alert --
                console.log('data updated');
            },

            error : function(error){
                $('#nameError').text(error.responseJSON.errors.name);
                $('#titleError').text(error.responseJSON.errors.title);
                $('#instituteError').text(error.responseJSON.errors.institute);
            }
        });
    }
// =============== Update data end ===============


// =============== delete data start ===============
    function deleteData(id)
    {
        // swal({
        //     title: 'Are you sure?',
        //     text: "You won't be able to revert this!",
        //     icon: 'warning',
        //     buttons: true,
        //     dangerMode: true,
        // });
        // .then((willDelete) =>{
        //     if(willDelete)
        //     {

        //     }
        //     else
        //     {
        //         swal("Canceled");
        //     }
        // });

        // alert(id);
        $.ajax({
            type : "GET",
            dataType : "json",
            url : "/teacher/destroy/"+id,
            success : function(data){
                $('#addT').show();                   
                $('#addButton').show();                   
                $('#updateT').hide();                   
                $('#updateButton').hide();
                clearData();
                allData();
            //-- start alert --
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'Data deleted successfully',
                    showConfirmButton: false,
                    timer: 1500
                })
            //-- end alert --
                console.log('deleted');
            }
        });
    }
// =============== delete data end ===============
</script>
</body>
</html>