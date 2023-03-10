@extends('frontend.layouts.app')

@section('title', __('Terms & Conditions'))

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <x-frontend.card>
                    <x-slot name="header">
                        Edit board №{{$board->id}}
                    </x-slot>

                    <x-slot name="body">
                        <x-forms.patch :action="route('frontend.board.update', $board)">
                            <div class="form-group row">
                                <label for="external_id" class="col-md-4 col-form-label text-md-right">External id</label>

                                <div class="col-md-6">
                                    <input type="text" id="external_id" name="external_id"
                                           class="form-control" placeholder="External id"
                                           maxlength="100" required
                                           value="{{$board->external_id}}"/>
                                </div>
                            </div><!--form-group-->

                            <div class="form-group row">
                                <label for="chat_id" class="col-md-4 col-form-label text-md-right">Chat id</label>

                                <div class="col-md-6">
                                    <input type="text" id="chat_id" name="chat_id"
                                           class="form-control" placeholder="Chat id"
                                           maxlength="100" required
                                           value="{{$board->chat_id}}"/>
                                </div>
                            </div><!--form-group-->

                            <div class="form-group row">
                                <label for="external_id" class="col-md-4 col-form-label text-md-right">Timeout</label>

                                <div class="col-md-6">
                                    <input type="number" id="timeout" name="timeout"
                                           class="form-control" placeholder="Timeout"
                                           required
                                           value="{{$board->timeout}}"/>
                                </div>
                            </div><!--form-group-->

                            <div class="form-group row">
                                <label for="disabled_notifications" class="col-md-4 col-form-label text-md-right form-check-label">Disabled notifications</label>

                                <div class="col-md-6">
                                    <input type="checkbox" id="disabled_notifications" name="disabled_notifications"
                                           class="form-check-input" placeholder="Disabled notifications"
                                        {{$board->disabled_notifications ? 'checked' : ''}}
                                    />
                                </div>
                            </div><!--form-group-->

                            <div class="form-group row">
                                <label for="enabled_text" class="col-md-4 col-form-label text-md-right">Enabled text</label>

                                <div class="col-md-6">
                                    <textarea
                                        class="form-control"
                                        name="enabled_text"
                                        id="enabled_text"
                                        placeholder="Enabled text"
                                        required
                                        cols="30"
                                        rows="10">{!! $board->enabled_text !!}</textarea>
                                </div>
                            </div><!--form-group-->

                            <div class="form-group row">
                                <label for="external_id" class="col-md-4 col-form-label text-md-right">Disabled text</label>

                                <div class="col-md-6">
                                    <textarea
                                        class="form-control"
                                        name="disabled_text"
                                        id="disabled_text"
                                        placeholder="Disabled text"
                                        required
                                        cols="30"
                                        rows="10">{!! $board->disabled_text !!}</textarea>
                                </div>
                            </div><!--form-group-->

                            <button class="btn btn-success mt-2" type="submit">Save</button>
                        </x-forms.patch>
                    </x-slot>
                </x-frontend.card>
            </div><!--col-md-10-->
        </div><!--row-->
    </div><!--container-->
@endsection
