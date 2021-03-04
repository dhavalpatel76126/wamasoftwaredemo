<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="csrf-token" content="{{ csrf_token() }}" />
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
      <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
      <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
      <title>Laravel</title>
      <!-- Fonts -->
      <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">
      <style>
          .select2-search__field{
              width:40%!important;
          }
      </style>
   </head>
   <body>
      <div>
         <div class="container" id="tableView" >
            <h2>Employee list</h2>
            <button type="button" class="btn btn-primary" onclick="addnew()">Add new</button>
            <button type="button" class="btn btn-danger" onclick="bulkDeleteUser()">Bulk Delete</button>
            <table class="table">
               <thead>
                  <tr>
                     <th>Sr no.</th>
                     <th>Select</th>
                     <th>Name</th>
                     <th>Contact no.</th>
                     <th>Profile pic</th>
                     <th>Hobby</th>
                     <th>Category</th>
                     <th>Options</th>
                  </tr>
               </thead>
               <tbody id="userRowData">
                  @foreach ($userData as $item)  
                  <tr>
                     <td>{{$item->id}}</td>
                     <td><input type="checkbox" value="{{$item->id}}" id="checkbox{{$item->id}}" onclick="selectCheckBox({{$item->id}})"></td>
                     <td><input type="text" value="{{$item->name}}" class="edit{{$item->id}}" id="name{{$item->id}}"disabled></td>
                     <td><input type="text" value="{{$item->contact}}" class="edit{{$item->id}}" id="contact{{$item->id}}" disabled></td>
                     <td><img src="{{url('/images/profile/'.$item->profilepic)}}" alt="job image" title="Profile image" style="width: 100px;height:100px;"></td>
                     <td>
                        <select class="form-control edit{{$item->id}}" id="hobby{{$item->id}}"name="hobbyupdate[]" multiple="multiple" disabled>
                        @foreach ($hobby as $key=>$hobbyitem)
                        <option value="{{ $hobbyitem->id }}"   @foreach($item->hobby as $hobbylist){{$hobbyitem->id == $hobbylist->id ? 'selected': ''}}   @endforeach> {{ $hobbyitem->name }}</option>
                        @endforeach
                        </select>
                     </td>
                     <td>
                        <select class="form-control edit{{$item->id}}" name="category" id="category{{$item->id}}" disabled>
                        @foreach ($categories as $categoryitem)
                        @php 
                        $selected = '';
                        @endphp
                        @if($categoryitem->id == $item->category)
                        @php 
                        $selected = 'selected="selected"';
                        @endphp
                        @endif
                        {{$selected}}
                        <option value="{{$categoryitem->id}}" {{$selected}}>{{$categoryitem->category}}</option>
                        @endforeach
                        </select>
                     </td>
                     <td>
                        <button type="button" class="btn btn-primary editbutton{{$item->id}}" onclick="editUser({{$item->id}})">Edit</button>
                        <button type="button" class="btn btn-info updatebutton{{$item->id}}" style="display: none;" onclick="updateUser({{$item->id}})">Update</button>
                        <button type="button" class="btn btn-success" onclick="deleteUser({{$item->id}})">Delete</button>
                     </td>
                  </tr>
                  @endforeach
               </tbody>
            </table>
         </div>
      </div>
      <div class="container" id="addnewpart" hidden>
         <h2>Add new</h2>
         <form enctype="multipart/form-data" id="addnewdata">
            @csrf
            <div class="form-group col-md-8">
               <label for="name">Name:</label>
               <input type="text" class="form-control" id="name" placeholder="Enter name" name="name">
            </div>
            <div class="form-group col-md-8">
               <label for="pwd">Contact:</label>
               <input type="text" class="form-control" id="contact" placeholder="Enter contact" name="contact">
            </div>
            <div class="form-group col-md-8">
               <label for="hobby">Select Hobby:</label>
               <select class="form-control hobby" name="hobby[]" id="hobby" multiple="multiple" >
                  @foreach ($hobby as $item)
                  <option value="{{$item->id}}">{{$item->name}}</option>
                  @endforeach
               </select>
            </div>
            <div class="form-group col-md-8">
               <label for="category">Select Category:</label>
               <select class="form-control" name="category" id="category">
                  <option value="" disabled selected hidden>Select category</option>
                  @foreach ($categories as $item)
                  <option value="{{$item->id}}">{{$item->category}}</option>
                  @endforeach
               </select>
            </div>
            <div class="form-group col-md-8">
               <input type="file" class="form-control" id="profilepic" placeholder="Enter contact" name="profilepic">
            </div>
            <div class="form-group col-md-8">
               <button  type="submit" class="btn btn-default" id="savedata" >Submit</button>
            </div>
         </form>
      </div>
      <script>
         var APP_URL = {!! json_encode(url('/')) !!};
         var bulkDeleteArray = [];
         // function add new
         function addnew() {
            $("form").get(0).reset()
             $('table').hide();
             $('#addnewpart').css("display","block");
         }
               // enable input field for editing.
             function editUser(id) {
                 $('.edit'+id).attr('disabled', false);
                 $('.editbutton'+id).hide();
                 $('.updatebutton'+id).css("display", "block");
             }
             
             function selectCheckBox(id) {
                var checkBox = document.getElementById("checkbox"+id);
                if (checkBox.checked == true){
                    bulkDeleteArray.push(id);
                } else {
                    bulkDeleteArray.pop(id);
                }
             }
             // multiselect package
             $(document).ready(function() {
             $('.hobby').select2();
             });
         
             // form data save
                 $("form").submit(function(evt){
                 evt.preventDefault();
                 var formData = new FormData($(this)[0]);
                 $.ajax({
                     url: APP_URL + "/addnewdata",
                     type: 'POST',
                     data:formData,
                     async: false,
                     cache: false,
                     contentType: false,
                     enctype: 'multipart/form-data',
                     processData: false,
                     headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                     dataType: 'json',
                     success: function (response) {
                        var userData = response.userData;
                         var hobbyData = response.hobby;
                        $("#userRowData").html('');
                        for (var data in userData) {
                        $(`<tr>
                     <td>${userData[data].id}</td>
                     <td><input type="checkbox" value="" id="${userData[data].id}"></td>
                     <td><input type="text" value="${userData[data].name}" class="edit${userData[data].id}" id="name${userData[data].id}"disabled></td>
                     <td><input type="text" value="${userData[data].contact}" class="edit${userData[data].id}" id="contact${userData[data].id}" disabled></td>
                     <td><img src="${APP_URL}/images/profile/${userData[data].profilepic}" alt="job image" title="Profile image" style="width: 100px;height:100px;"></td>
                     <td>
                        <select class="form-control edit${userData[data].id}" id="hobby${userData[data].id}"name="hobbyupdate[]" multiple="multiple" disabled>
                             <option value="1"selected>Reading</option>   
                             <option value="2">Programming</option>   
                             <option value="3">Games</option>   
                             <option value="4">Photography</option>   
                            </select>
                     </td>
                     <td>
                        <select class="form-control edit${userData[data].id}" name="category" id="category${userData[data].id}" disabled>
                            <option value="1"selected>Developer</option>   
                            <option value="2">Designer</option>
                        </select>
                     </td>
                     <td>
                        <button type="button" class="btn btn-primary editbutton${userData[data].id}" onclick="editUser(${userData[data].id})">Edit</button>
                        <button type="button" class="btn btn-info updatebutton${userData[data].id}" style="display: none;" onclick="updateUser(${userData[data].id})">Update</button>
                        <button type="button" class="btn btn-success" onclick="deleteUser(${userData[data].id})">Delete</button>
                     </td>
                  </tr>`).appendTo("#userRowData");
                  $('table').css("display", "block");
                  $('#addnewpart').hide();
                        } 
                     },
         
                 });
                 return false;
             });
         
               function updateUser(params) {
                   var name = $("#name"+params).val();
                   var contact = $("#contact"+params).val();
                   var hobby=[];
                    $('#hobby'+params+' :selected').each(function(){
                        hobby[$(this).val()]=$(this).val();
                    });
                    var hobby = hobby.filter(Boolean);
                    var category = $("#category"+params).val();
                   $.ajax({
                     url: APP_URL + "/update",
                     type: 'POST',
                     data:{
                         name:name,
                         contact:contact,
                         hobby:hobby,
                         category:category,
                         id:params
                     },
                     async: false,
                     cache: false,
                    //  contentType: false,
                     enctype: 'multipart/form-data',
                    //  processData: false,
                     headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                     dataType: 'json',
                     success: function (response) {
                        alert("Employee data updated successfully.");
                        $('.edit'+params).attr('disabled', true);
                        $('.updatebutton'+params).hide();
                        $('.editbutton'+params).css("display", "block");
                        
                     },
         
                 });
                 return false;
               }
         
               function deleteUser(params) {
                var result = confirm("Want to delete?");
                if (result) {
                   $.ajax({
                     url: APP_URL + "/delete",
                     type: 'POST',
                     data:{
                         id:params
                     },
                     async: false,
                     cache: false,
                     headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                     dataType: 'json',
                     success: function (response) {
                         var userData = response.userData;
                         var hobbyData = response.hobby;
                        $("#userRowData").html('');
                        for (var data in userData) {
                        $(`<tr>
                     <td>${userData[data].id}</td>
                     <td><input type="checkbox" value="" id="${userData[data].id}"></td>
                     <td><input type="text" value="${userData[data].name}" class="edit${userData[data].id}" id="name${userData[data].id}"disabled></td>
                     <td><input type="text" value="${userData[data].contact}" class="edit${userData[data].id}" id="contact${userData[data].id}" disabled></td>
                     <td><img src="${APP_URL}/images/profile/${userData[data].profilepic}" alt="job image" title="Profile image" style="width: 100px;height:100px;"></td>
                     <td>
                        <select class="form-control edit${userData[data].id}" id="hobby${userData[data].id}"name="hobbyupdate[]" multiple="multiple" disabled>
                             <option value="1" selected>Reading</option>   
                             <option value="2">Programming</option>   
                             <option value="3">Games</option>   
                             <option value="4">Photography</option>   
                            </select>
                     </td>
                     <td>
                        <select class="form-control edit${userData[data].id}" name="category" id="category${userData[data].id}" disabled>
                            <option value="1" selected>Developer</option>   
                            <option value="2">Designer</option>
                        </select>
                     </td>
                     <td>
                        <button type="button" class="btn btn-primary editbutton${userData[data].id}" onclick="editUser(${userData[data].id})">Edit</button>
                        <button type="button" class="btn btn-info updatebutton${userData[data].id}" style="display: none;" onclick="updateUser(${userData[data].id})">Update</button>
                        <button type="button" class="btn btn-success" onclick="deleteUser(${userData[data].id})">Delete</button>
                     </td>
                  </tr>`).appendTo("#userRowData");
                        }  
                     },
                 });
                 return false;
}
               }
               // bulk delete users.
               function bulkDeleteUser() {
                var result = confirm("Want to bulk delete?");
                if (result) {
                    bulkDeleteArray = bulkDeleteArray.join(",");
                   $.ajax({
                     url: APP_URL + "/bulkdelete",
                     type: 'POST',
                     data:'bulkDeleteArray='+bulkDeleteArray,
                     async: false,
                     cache: false,
                     headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                     dataType: 'json',
                     success: function (response) {
                         var userData = response.userData;
                         var hobbyData = response.hobby;
                        $("#userRowData").html('');
                        for (var data in userData) {
                        $(`<tr>
                     <td>${userData[data].id}</td>
                     <td><input type="checkbox" value="" id="${userData[data].id}"></td>
                     <td><input type="text" value="${userData[data].name}" class="edit${userData[data].id}" id="name${userData[data].id}"disabled></td>
                     <td><input type="text" value="${userData[data].contact}" class="edit${userData[data].id}" id="contact${userData[data].id}" disabled></td>
                     <td><img src="${APP_URL}/images/profile/${userData[data].profilepic}" alt="job image" title="Profile image" style="width: 100px;height:100px;"></td>
                     <td>
                        <select class="form-control edit${userData[data].id}" id="hobby${userData[data].id}"name="hobbyupdate[]" multiple="multiple" disabled>
                             <option value="1"selected>Reading</option>   
                             <option value="2">Programming</option>   
                             <option value="3">Games</option>   
                             <option value="4">Photography</option>   
                            </select>
                     </td>
                     <td>
                        <select class="form-control edit${userData[data].id}" name="category" id="category${userData[data].id}" disabled>
                            <option value="1"selected>Developer</option>   
                            <option value="2">Designer</option>
                        </select>
                     </td>
                     <td>
                        <button type="button" class="btn btn-primary editbutton${userData[data].id}" onclick="editUser(${userData[data].id})">Edit</button>
                        <button type="button" class="btn btn-info updatebutton${userData[data].id}" style="display: none;" onclick="updateUser(${userData[data].id})">Update</button>
                        <button type="button" class="btn btn-success" onclick="deleteUser(${userData[data].id})">Delete</button>
                     </td>
                  </tr>`).appendTo("#userRowData");
                        }  
                     },
                 });
                 return false;
}
               }
      </script>
   </body>
</html>