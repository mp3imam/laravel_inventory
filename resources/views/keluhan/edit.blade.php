@extends('layouts.master')

<!-- @section('title')
    {{ $title }}
@endsection -->

@section('content')
    @include('components.breadcrumb')

    <div class="container-fluid">
        <div class="chat-wrapper d-lg-flex gap-1 mx-n4 mt-n4 p-1">
            <div class="user-chat w-100 overflow-hidden user-chat-show">
                <div class="chat-content d-lg-flex">
                    <!-- start chat conversation section -->
                    <meta name="csrf-token" content="{{ csrf_token() }}" />
                    <div class="w-100 overflow-hidden position-relative">
                        @php
                            $user = Auth::user()->id;
                            $position = $user == $keluhan->user_id ? "right" : "left";
                            $status   = $keluhan->status  == 1 ? "bx-check-double" : "bx-check";
                        @endphp
                        <input type="hidden" id="user_id" value="{{ $user }}" />
                        <input type="hidden" id="keluhan_id" value="{{ $keluhan->id }}" />
                        <!-- conversation user -->
                        <div class="position-relative">
                            <div class="position-relative" id="users-chat" style="display: block;">
                                <div class="p-3 user-chat-topbar">
                                    <div class="row align-items-center">
                                        <div class="col-sm-4 col-8">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 d-block d-lg-none me-3">
                                                    <a href="javascript: void(0);" class="user-chat-remove fs-18 p-1"><i
                                                            class="ri-arrow-left-s-line align-bottom"></i></a>
                                                </div>
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <h5 class="fs-16"><a class="text-reset username"
                                                                    data-bs-toggle="offcanvas"
                                                                    href="#userProfileCanvasExample"
                                                                    aria-controls="userProfileCanvasExample">{{ $user == 2 ?  "SuperAdmin" : $keluhan->user->name }}</a>
                                                            </h5>
                                                            <p class="text-truncate text-muted fs-14 mb-0 userStatus">
                                                                <small>{{$user == 2 ? "" : $keluhan->satker->name }}</small></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-8 col-4">
                                            <ul class="list-inline user-chat-nav text-end mb-0">
                                                @if ($user != 3)
                                                    <li class="list-inline-item d-none d-lg-inline-block m-0">
                                                        <button type="button" class="btn btn-warning close_komentar" data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false" value="Close Question">Close Question</button>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>

                                </div>
                                <!-- end chat user head -->
                                <div class="chat-conversation p-3 p-lg-4" id="chat-conversation" data-simplebar="init">
                                    <div class="simplebar-wrapper" style="margin: -16px;">
                                        <div class="simplebar-height-auto-observer-wrapper">
                                            <div class="simplebar-height-auto-observer"></div>
                                        </div>
                                        <div class="simplebar-mask">
                                            <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                                                <div class="simplebar-content-wrapper" tabindex="0" role="region"
                                                    aria-label="scrollable content"
                                                    style="height: 100%; overflow: hidden scroll;">
                                                    <div class="simplebar-content" style="padding: 16px;">
                                                        <ul class="list-unstyled chat-conversation-list" id="users-conversation">

                                                            <li class="chat-list {{ $position }}" id="1">
                                                                <div class="conversation-list">
                                                                    <div class="user-chat-content">
                                                                        <div class="ctext-wrap">
                                                                            <div class="ctext-wrap-content" id="2">
                                                                                <p class="mb-0 ctext-content">Inti Pertanyaan dari Nomor Keluhan {{ $keluhan->nomor_keluhan }}</p>
                                                                                @if ($keluhan->image)
                                                                                    <p class="mb-0 ctext-content"><img src="{{ $keluhan->image }}" /></p>
                                                                                @endif
                                                                                <p class="mb-0 ctext-content">{{ $keluhan->pertanyaan }}</p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="conversation-name"><span class="d-none name"></span><small
                                                                                class="text-muted time">{{ \Carbon\Carbon::parse($keluhan->created_at)->format('d-m-Y h:i A') }}</small>
                                                                            <span
                                                                                class="text-success check-message-icon"><i
                                                                                    class="bx `+status+`"></i></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="simplebar-placeholder" style="width: auto; height: 626px;"></div>
                                    </div>
                                    <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
                                        <div class="simplebar-scrollbar" style="width: 0px; display: none;"></div>
                                    </div>
                                    <div class="simplebar-track simplebar-vertical" style="visibility: visible;">
                                        <div class="simplebar-scrollbar"
                                            style="height: 260px; transform: translate3d(0px, 144px, 0px); display: block;">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="position-relative" id="channel-chat" style="display: none;">
                                <div class="p-3 user-chat-topbar">
                                    <div class="row align-items-center">
                                        <div class="col-sm-4 col-8">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 d-block d-lg-none me-3">
                                                    <a href="javascript: void(0);" class="user-chat-remove fs-18 p-1"><i
                                                            class="ri-arrow-left-s-line align-bottom"></i></a>
                                                </div>
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <div class="d-flex align-items-center">
                                                        <div
                                                            class="flex-shrink-0 chat-user-img online user-own-img align-self-center me-3 ms-0">
                                                            <img src="assets/images/users/avatar-2.jpg"
                                                                class="rounded-circle avatar-xs" alt="">
                                                        </div>
                                                        <div class="flex-grow-1 overflow-hidden">
                                                            <h5 class="text-truncate mb-0 fs-16"><a
                                                                    class="text-reset username" data-bs-toggle="offcanvas"
                                                                    href="#userProfileCanvasExample"
                                                                    aria-controls="userProfileCanvasExample">Lisa
                                                                    Parker</a></h5>
                                                            <p class="text-truncate text-muted fs-14 mb-0 userStatus">
                                                                <small>24 Members</small></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-8 col-4">
                                            <ul class="list-inline user-chat-nav text-end mb-0">
                                                <li class="list-inline-item m-0">
                                                    <div class="dropdown">
                                                        <button class="btn btn-ghost-secondary btn-icon" type="button"
                                                            data-bs-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="feather feather-search icon-sm">
                                                                <circle cx="11" cy="11" r="8">
                                                                </circle>
                                                                <line x1="21" y1="21" x2="16.65"
                                                                    y2="16.65"></line>
                                                            </svg>
                                                        </button>
                                                        <div class="dropdown-menu p-0 dropdown-menu-end dropdown-menu-lg">
                                                            <div class="p-2">
                                                                <div class="search-box">
                                                                    <input type="text"
                                                                        class="form-control bg-light border-light"
                                                                        placeholder="Search here..."
                                                                        onkeyup="searchMessages()" id="searchMessage">
                                                                    <i class="ri-search-2-line search-icon"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>

                                                <li class="list-inline-item d-none d-lg-inline-block m-0">
                                                    <button type="button" class="btn btn-ghost-secondary btn-icon"
                                                        data-bs-toggle="offcanvas"
                                                        data-bs-target="#userProfileCanvasExample"
                                                        aria-controls="userProfileCanvasExample">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" class="feather feather-info icon-sm">
                                                            <circle cx="12" cy="12" r="10">
                                                            </circle>
                                                            <line x1="12" y1="16" x2="12"
                                                                y2="12"></line>
                                                            <line x1="12" y1="8" x2="12.01"
                                                                y2="8"></line>
                                                        </svg>
                                                    </button>
                                                </li>

                                                <li class="list-inline-item m-0">
                                                    <div class="dropdown">
                                                        <button class="btn btn-ghost-secondary btn-icon" type="button"
                                                            data-bs-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="feather feather-more-vertical icon-sm">
                                                                <circle cx="12" cy="12" r="1">
                                                                </circle>
                                                                <circle cx="12" cy="5" r="1">
                                                                </circle>
                                                                <circle cx="12" cy="19" r="1">
                                                                </circle>
                                                            </svg>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <a class="dropdown-item d-block d-lg-none user-profile-show"
                                                                href="#"><i
                                                                    class="ri-user-2-fill align-bottom text-muted me-2"></i>
                                                                View Profile</a>
                                                            <a class="dropdown-item" href="#"><i
                                                                    class="ri-inbox-archive-line align-bottom text-muted me-2"></i>
                                                                Archive</a>
                                                            <a class="dropdown-item" href="#"><i
                                                                    class="ri-mic-off-line align-bottom text-muted me-2"></i>
                                                                Muted</a>
                                                            <a class="dropdown-item" href="#"><i
                                                                    class="ri-delete-bin-5-line align-bottom text-muted me-2"></i>
                                                                Delete</a>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                </div>
                                <!-- end chat user head -->
                                <div class="chat-conversation p-3 p-lg-4" id="chat-conversation" data-simplebar="init">
                                    <div class="simplebar-wrapper" style="margin: -24px;">
                                        <div class="simplebar-height-auto-observer-wrapper">
                                            <div class="simplebar-height-auto-observer"></div>
                                        </div>
                                        <div class="simplebar-mask">
                                            <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                                                <div class="simplebar-content-wrapper" tabindex="0" role="region"
                                                    aria-label="scrollable content"
                                                    style="height: auto; overflow: hidden;">
                                                    <div class="simplebar-content" style="padding: 24px;">
                                                        <ul class="list-unstyled chat-conversation-list"
                                                            id="channel-conversation">
                                                        </ul>
                                                        <!-- end chat-conversation-list -->

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="simplebar-placeholder" style="width: 0px; height: 0px;"></div>
                                    </div>
                                    <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
                                        <div class="simplebar-scrollbar" style="width: 0px; display: none;"></div>
                                    </div>
                                    <div class="simplebar-track simplebar-vertical" style="visibility: hidden;">
                                        <div class="simplebar-scrollbar" style="height: 0px; display: none;"></div>
                                    </div>
                                </div>
                                <div class="alert alert-warning alert-dismissible copyclipboard-alert px-4 fade show"
                                    id="copyClipBoardChannel" role="alert">
                                    Message copied
                                </div>
                            </div>

                            <!-- end chat-conversation -->

                            <div class="chat-input-section p-3 p-lg-4">

                                <form id="chatinput-form" enctype="multipart/form-data">
                                    <div class="row g-0 align-items-center">
                                        {{-- <div class="col-md-1 mt-2">
                                            <input type="file" class="form-label chat-file bg-light border-light" id="chat-file">
                                            <label for="image" class="btn btn-info">Pilih File</label>
                                            <input type="file" id="image" style="display:none">
                                        </div> --}}
                                        <div class="col">
                                            <div class="chat-input-feedback">
                                                Please Enter a Message
                                            </div>
                                            <input type="text" class="form-control chat-input bg-light border-light"
                                                id="chat-input" placeholder="Type your message..." autocomplete="off">
                                        </div>
                                        <div class="col-auto">
                                            <div class="chat-input-links ms-2">
                                                <div class="links-list-item">
                                                    <button type="button"
                                                        class="btn btn-success chat-send waves-effect waves-light">
                                                        <i class="ri-send-plane-2-fill align-bottom"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </form>
                            </div>

                            <div class="replyCard">
                                <div class="card mb-0">
                                    <div class="card-body py-3">
                                        <div class="replymessage-block mb-0 d-flex align-items-start">
                                            <div class="flex-grow-1">
                                                <h5 class="conversation-name"></h5>
                                                <p class="mb-0"></p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <button type="button" id="close_toggle"
                                                    class="btn btn-sm btn-link mt-n2 me-n3 fs-18">
                                                    <i class="bx bx-x align-middle"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end chat-wrapper -->

    </div>
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment-with-locales.min.js"></script>
@section('script')
<script type="text/javascript">
    var keluhan_id = $('#keluhan_id').val()
    var user_id = $('#user_id').val()
    moment.locale('id');
    $(function () {
        $.ajax({
            url: "{{ url('/list_komentars') }}/"+keluhan_id,
            dataType: 'json',
            delay: 250,
            success: function (data) {
            var komentar = data['data']
            komentar.forEach(element => {
                var position, status = null;
                var time = moment(element['created_at']).fromNow();
                position = element['user_id'] == user_id ? "right" : "left"
                status   = element['status']  == 1 ? "bx-check-double" : "bx-check"
                $('#users-conversation').append(`
                    <li class="chat-list `+position+`" id="1">
                        <div class="conversation-list">
                            <div class="user-chat-content">
                                <div class="ctext-wrap">
                                    <div class="ctext-wrap-content" id="2">
                                        <p class="mb-0 ctext-content" id="focus_`+element['id']+`">`+element['komentar']+`</p>
                                    </div>
                                </div>
                                <div class="conversation-name"><span class="d-none name"></span><small
                                        class="text-muted time">`+time+`</small>
                                    <span
                                        class="text-success check-message-icon"><i
                                            class="bx `+status+`"></i></span>
                                </div>
                            </div>
                        </div>
                    </li>
                `)
            });
            },
            cache: true
        });
    });

    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on('keypress',function(e) {
        if(e.which == 13) {
            sendData()
        }
    });

    $('.chat-send').click(function(){
        sendData()
    })

    function sendData(){
        $('.chat-input').attr('disabled',true)
        $('.chat-send').attr('disabled',true)
        var komentar = $('.chat-input').val()
        if (komentar == "") return;
        fd = new FormData()
        fd.append('keluhan_id', keluhan_id)
        fd.append('user_id', user_id)
        fd.append('komentar', komentar)

        $.ajax({
            type: "post",
            url: "{{ route('update_komentar') }}",
            data:fd,
            processData: false,
            contentType: false,
            success: function (result) {
                $('.chat-input').val('')
                $('#users-conversation').append(`
                    <li class="chat-list right id="1">
                        <div class="conversation-list">
                            <div class="user-chat-content">
                                <div class="ctext-wrap">
                                    <div class="ctext-wrap-content" id="2">
                                        <p class="mb-0 ctext-content">`+komentar+`</p>
                                    </div>
                                </div>
                                <div class="conversation-name"><span class="d-none name">
                                    </span><small class="text-muted time"> {{ date('d-m-Y h:i A') }} </small>
                                    <span class="text-success check-message-icon"> <i class="bx bx-check"> </i></span>
                                </div>
                            </div>
                        </div>
                    </li>
                `)
                $('.chat-input').attr('disabled',false)
                $('.chat-send').attr('disabled',false)
            }
        });
        $('.chat-input').attr('disabled',false)
        $('.chat-send').attr('disabled',false)
    }

    $('.close_komentar').click(function(){
        Swal.fire({
            title: 'Apakah anda puas dengan jawaban dari superadmin?',
            input: 'select',
            inputOptions: {
                '1': 'Tidak Puas',
                '2': 'Kurang Puas',
                '3': 'Cukup Puas',
                '4': 'Puas',
                '5': 'Sangat Puas',
            },
            inputPlaceholder: 'Select a Rating',
            showCancelButton: true,
            inputValidator: (value) => {
                console.log(value);
                fd = new FormData()
                fd.append('keluhan_id', keluhan_id)
                fd.append('rating', value)

                $.ajax({
                    type: "POST",
                    url: "{{ route('update_keluhan') }}",
                    data:fd,
                    processData: false,
                    contentType: false,
                    success: function (result) {
                        console.log(result)
                        // if (result['status'] == 200)
                        window.location.href = "{{ url('rate_support_sistem') }}"
                    }
                });
            }
        })
    })
</script>
@endsection
