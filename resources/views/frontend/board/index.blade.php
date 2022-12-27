@extends('frontend.layouts.app')

@section('title', __('Terms & Conditions'))

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <x-frontend.card>
                    <x-slot name="header">
                        Boards
                    </x-slot>

                    <x-slot name="body">

                        <x-forms.get :action="route('frontend.board.create')">
                            <button class="btn btn-sm btn-success mb-3" type="submit">Create new</button>
                        </x-forms.get>

                        <table class="table">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>External id</th>
                                <th>Chat id</th>
                                <th>Last activity</th>
                                <th>Timeout</th>
                                <th>Active</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach(\App\Models\Board::orderBy('id')->get() as $key => $board)
                                <tr>
                                    <td>{{$board->id}}</td>
                                    <td>{{$board->external_id ?? 'none' }}</td>
                                    <td>{{$board->chat_id ?? 'none' }}</td>
                                    <td>{{$board->last_activity}}</td>
                                    <td>{{$board->timeout}}</td>
                                    <td>
                                        <div class="btn {{$board->active ? 'btn-success' : 'btn-danger'}}">
                                            {{ $board->active ?  'Online' : 'Offline' }}
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{route('frontend.board.edit', $board)}}" class="btn btn-success">Edit</a>
                                        <x-forms.delete :action="route('frontend.board.destroy', $board)">
                                            <button class="btn btn-danger mt-2" type="submit">Delete</button>
                                        </x-forms.delete>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </x-slot>
                </x-frontend.card>
            </div><!--col-md-10-->
        </div><!--row-->
    </div><!--container-->
@endsection
