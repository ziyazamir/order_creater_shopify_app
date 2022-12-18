@extends('shopify-app::layouts.default')
@section('styles')
    <style>
        :root {
            --overlay-color: rgba(0, 0, 0, .7);
            --dialog-color: white;
            --dialog-border-radius: 20px;
            --icon-color: rgba(73, 80, 87, .6);
            --dialog-padding: 20px;
            --drag-over-background: #e3e5e8;
        }

        body {
            background: #353353;
            padding: 0;
            height: 100vh;
            min-height: 100vh;
            font-family: "Rubik", sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-flow: column;
            color: white;
        }

        .file-container {
            font-size: 13pt;
            color: #4d4d4d;

        }

        .file-container .file-overlay {
            position: fixed;
            width: 100vw;
            height: 100vh;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            z-index: 10;
        }

        .file-container .file-wrapper {
            position: fixed;
            display: block;
            width: 30vw;
            height: 80vh;
            max-height: 500px;
            min-height: 400px;
            min-width: 400px;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            margin: 30px auto;
            background-color: var(--dialog-color);
            z-index: 20;
            border-radius: var(--dialog-border-radius);
            padding: var(--dialog-padding);
            box-shadow: 3px 3px 15px 0px black;
        }

        .file-container .file-wrapper .file-input {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background-color: #000;
            z-index: 10;
            cursor: pointer;
            opacity: 0;
        }

        .file-container .file-wrapper .file-input--active+.file-content {
            background: var(--drag-over-background);
        }

        .file-container .file-wrapper .file-input--active+.file-content .file-icon {
            opacity: 0.5;
        }

        .file-container .file-wrapper .file-input--active+.file-content .file-icon i {
            animation-name: bounce;
            animation-duration: 0.6s;
            animation-iteration-count: infinite;
            animation-timing-function: ease;
        }

        .file-container .file-wrapper .file-input--active+.file-content .file-icon .icon-shadow {
            animation-name: shrink;
            animation-duration: 0.6s;
            animation-iteration-count: infinite;
        }

        .file-container .file-wrapper .file-content {
            position: relative;
            display: block;
            width: 100%;
            height: 100%;
            border-radius: var(--dialog-border-radius);
            transition: 0.2s;
        }

        .file-container .file-wrapper .file-content .file-infos {
            position: absolute;
            display: flex;
            width: 80%;
            height: 50%;
            min-height: 202px;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            flex-direction: column;
            justify-content: center;
            margin: auto;
        }

        .file-container .file-wrapper .file-content .file-infos .file-icon {
            position: relative;
            width: 100%;
            height: 100%;
            margin: 0;
            color: var(--icon-color);
            background-color: #dcf9ff21;
            border-radius: var(--dialog-border-radius);
            padding: var(--dialog-padding);
            transition: 0.2s;
            border: dashed 2px #91c7d263;
        }

        .file-container .file-wrapper .file-content .file-infos .file-icon i {
            margin: 20px 0;
            width: 100%;
            color: #9a94ef;
        }

        .file-container .file-wrapper .file-content .file-infos .file-icon .icon-shadow {
            position: relative;
            display: block;
            width: 95px;
            height: 7px;
            border-radius: 100%;
            background-color: var(--drag-over-background);
            top: -17px;
            margin-left: auto;
            margin-right: auto;
        }

        .file-container .file-wrapper .file-content .file-infos .file-icon>span {
            position: absolute;
            bottom: var(--dialog-padding);
            width: calc(100% - var(--dialog-padding) * 2);
        }

        .file-container .file-wrapper .file-content .file-infos .file-icon>span span {
            display: none;
        }

        .file-container .file-wrapper .file-content .file-infos .file-icon>span .has-drag {
            display: inline;
        }

        .file-container .file-wrapper .file-content .file-infos .file-icon i,
        .file-container .file-wrapper .file-content .file-infos .file-icon span {
            display: block;
            text-align: center;
            text-transform: uppercase;
            font-weight: bold;
        }

        .file-container .file-wrapper .file-content .file-name {
            position: absolute;
            width: 100%;
            text-align: middle;
            left: 0;
            bottom: var(--dialog-padding);
            right: 0;
            font-weight: bold;
            font-size: 15pt;
            margin: auto;
            text-align: center;
        }

        @keyframes bounce {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-15px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        @keyframes shrink {
            0% {
                width: 95px;
            }

            50% {
                width: 75px;
            }

            100% {
                width: 95px;
            }
        }

        .upload-text {
            position: absolute;
            margin-top: 100px;
            display: flex;
            flex-flow: column;
            align-items: center;
            left: calc(50% - 59px);
            font-size: 10px;
            color: #b7b7b7;
        }

        .bold {
            font-weight: 500;
            margin: 5px 0;
            font-size: 12px;
            color: grey;
        }

        .upload-text button,
        input[type="button"] {
            padding: 8px 12px;
            border: 0;
            color: white;
            border-radius: 7px;
            background: #9a94ef;
            font-size: 12px;
        }

        input[type="submit"] {
            background: #9a94ef;
            width: 100%;
            padding: 12px;
            min-width: 400px;
            width: 30vw;
        }

        h1 {
            font-size: 20px;
            text-align: center;
            font-weight: 500;
            padding: 15px 0 5px 0;
            ;
        }

        h2 {
            opacity: .5;
            font-weight: 300;
            text-align: center;
            font-size: 12px;
        }

        textarea {
            width: 100%;
            min-width: 400px;
            width: 30vw;
            margin: 0;
            border-radius: 20px;
            padding: 10px 10px 0 10px;
            font-family: "Rubik", sans-serif;
            color: grey;
        }
    </style>
@endsection
@section('content')
    <!-- You are: (shop domain name) -->
    <p>You are: {{ $shopDomain ?? Auth::user()->name }}</p>
    @php
        $shop = Auth::user();
        $request = $shop->api()->rest('GET', '/admin/shop.json');
        // $request = $shop->api()->graph('{ shop { name } }');
        print_r($request['body']);
    @endphp
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-8">
                <form action="{{ route('readcsv') }}" enctype="multipart/form-data" method="post">
                    <div class="file-container">
                        <div class="file-overlay"></div>
                        <div class="file-wrapper">
                            {{-- <input type="text" name="store" value="{{ $shop }}"> --}}
                            <input type="hidden" name="token" value="{{ request('token') }}">
                            <input class="file-input" required name="file" id="js-file-input" type="file" />
                            <div class="file-content">
                                <h1>Upload File To Create Orders</h1>
                                <h2 class="subheader">Made with ❤️ by ziya zamir </h2>
                                <div class="file-infos">
                                    <p class="file-icon"><i class="fas fa-folder-open fa-3x"></i><span
                                            class="icon-shadow"></span>
                                    <div class="upload-text"><span class="has-drag">Drag & Drop your file here</span><span
                                            class="bold">OR</span><span> <button>Click to upload</button></span></div>

                                    </p>
                                </div>
                                <p class="file-name" id="js-file-name">No file selected</p>
                            </div>

                            <div style="width:100%;margin-left:-20px;">
                                <br><br>

                                <div>&nbsp;</div>
                                <input type="submit" class="fs-3" value="Submit">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent

    <script>
        actions.TitleBar.create(app, {
            title: 'Welcome'
        });

        window.supportDrag = function() {
            let div = document.createElement('div');
            return (('draggable' in div) || ('ondragstart' in div && 'ondrop' in div)) && 'FormData' in
                window && 'FileReader' in window;
        }();

        let input = document.getElementById('js-file-input');

        if (!supportDrag) {
            document.querySelectorAll('.has-drag')[0].classList.remove('has-drag');
        }

        input.addEventListener("change", function(e) {
            document.getElementById('js-file-name').innerHTML = this.files[0].name;
            document.querySelectorAll('.file-input')[0].classList.remove('file-input--active');
        }, false);

        if (supportDrag) {
            input.addEventListener("dragenter", function(e) {
                document.querySelectorAll('.file-input')[0].classList.add('file-input--active');
            });

            input.addEventListener("dragleave", function(e) {
                document.querySelectorAll('.file-input')[0].classList.remove('file-input--active');
            });
        }
    </script>
@endsection
