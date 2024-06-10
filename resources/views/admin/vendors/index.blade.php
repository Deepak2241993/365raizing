@extends('layouts.masteradmin')
@section('body')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive" style="margin-top:80px;">
                   All vendor
                    @if(session('message')) <p style="color:rgb(6, 82, 6); font-weight: 600;">{{session('message')}}</p>@endif
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>vendor Name</th>
                                <th>Brand Name</th>
                                <th>Contact</th>
                                <th>Email</th>
                                <th>Service</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $value)
                            <tr>
                                <th scope="row">{{$loop->iteration}}</th>
                                <td>{{$value->fname . $value->mname.$value->lname }}</td>
                                <td>{{$value->bname}}</td>
                                <td>{{$value->vcont}}</td>
                                <td>{{$value->vemail}}</td>
                                <td>{{$value->vservice}}</td>
                                
                                <td>
                                    <a href="{{route('vendor.edit',$value->id)}}"><i class="bx bx-pencil"></i> Edit </a> | <a href="javascript:void(0);"  onClick="deleteblogs('{{$value->id}}')" class="text-danger"><i class="bx bx-trash-alt"></i> Delete</a>
                                </td>
                            </tr>
                            
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{$data->links('vendor.pagination.simple-bootstrap-4')}}
                
            </div>
        </div>
    </div>
</div>
@endsection

@push('footer-section-code')

<script>
    function deleteblogs(tid){
        if(confirm('Are You sure'))
        {
        $.ajax({
            method:'DELETE',
            url: '{{ url('master-admin/vendor') }}/'+tid,
            data:{
                id: tid,
                _token: '{{ csrf_token() }}'
            },
            success:function(response){
                
                if(response.success==true)
                {
                    location.reload();
                    swal("Deleted!", response.message, "error");
                }
                if(response.success==false)
                {
                    location.reload();
                    swal("Deleted!", response.message, "error");
                    

                }
                
            }
        });
    }
}
    </script>


@endpush