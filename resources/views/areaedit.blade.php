@extends('layouts.venxia')
@section('title', $PAGE_TITLE)
@section('UserName', $USERNAME)

@section('contents')

    <section class="work-site mt-3">
        <div class="container">
            <div class="row">
                <div class="col-md-12">

                    <form>
                        <div class="row">
                            <div class="col-3">
                                <label for="">Area Name</label>
                                <input type="text" class="form-control" name="area_name" value="{{ $Areas->Area_Name }}">
                            </div>
                            <div class="col-3">
                                <label for="">Orin Device ID</label>
                                <input type="text" class="form-control" name="Orin_Device_ID"
                                    value=" {{ $Areas->Orin_Device_ID }} ">
                            </div>
                            <div class="col-3">
                                <label for="">Orin_Device_KEY</label>
                                <input type="text" class="form-control" name="Orin_Device_Key"
                                    value=" {{ $Areas->Orin_Device_Key }} ">
                            </div>
                            <div class="col-3">
                                <label for="">Edit Area</label></br>
                                <input type="submit" class="btn btn-danger w-100" name="submit" value="Edit">
                            </div>

                        </div>



                    </form>


                    <div class="mt-2 area-boxes overflow-hidden overflow-x-auto">
                        <ul>


                        </ul>
                    </div>
                    <div class="hr-line"></div>
                    <div class="main-natification-table">
                        <h2 style="margin-left:15px;margin-top:15px;margin-bottom:15px">Workers</h2>
                        <table class="table">
                            <thead class="thead-dark">
                                <tr>
                                    <th colspan="6">Name</th>
                                    <th colspan="6">Action</th>
                                </tr>
                            </thead>
                            <tbody>


                                @if ($AreaUsers)
                                    @foreach ($AreaUsers as $AreaUser)
                                        <tr>
                                            <td colspan="6">{{ $AreaUser->UName }}</td>
                                            <td colspan="6"><a class="btn btn-danger">Remove</a></td>
                                        </tr>
                                    @endforeach
                                @endif
                                @for ($i = 0; $i < 6; $i++)
                                @endfor
                            </tbody>
                        </table>
                        <div class="main_loadmore-btn">
                            <button class="load-more">
                                Add More Users
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
 


@endsection
