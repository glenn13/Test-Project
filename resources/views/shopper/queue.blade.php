<x-guest-layout>
    <style>
        .modalDialog {
            position: fixed;
            font-family: Arial, Helvetica, sans-serif;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background: rgba(0, 0, 0, 0.8);
            z-index: 99999;
            opacity:0;
            -webkit-transition: opacity 400ms ease-in;
            -moz-transition: opacity 400ms ease-in;
            transition: opacity 400ms ease-in;
            pointer-events: none;
        }
        .modalDialog:target {
            opacity:1;
            pointer-events: auto;
        }
        .modalDialog > div {
            width: 400px;
            position: relative;
            margin: 10% auto;
            padding: 5px 20px 13px 20px;
            border-radius: 10px;
            background: #fff;
            background: -moz-linear-gradient(#fff, #999);
            background: -webkit-linear-gradient(#fff, #999);
            background: -o-linear-gradient(#fff, #999);
        }
        .close {
            background: #606061;
            color: #FFFFFF;
            line-height: 25px;
            position: absolute;
            right: -12px;
            text-align: center;
            top: -10px;
            width: 24px;
            text-decoration: none;
            font-weight: bold;
            -webkit-border-radius: 12px;
            -moz-border-radius: 12px;
            border-radius: 12px;
            -moz-box-shadow: 1px 1px 3px #000;
            -webkit-box-shadow: 1px 1px 3px #000;
            box-shadow: 1px 1px 3px #000;
        }
        .close:hover {
            background: #00d9ff;
        }

    </style>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <div class="pt-4 bg-gray-100">
        <div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0">
            <div>
                <x-jet-authentication-card-logo />
            </div>

            <div class="w-full sm:max-w-2xl mt-6 p-6 bg-white shadow-md overflow-hidden sm:rounded-lg prose">
                @if (\Session::has('msg'))
                    <div class="alert alert-success">
                        <ul>
                            <li>{!! \Session::get('msg') !!}</li>
                        </ul>
                    </div>
                @endif
                <a href="#checkinModal"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition"
                    style="color: #fff">
                    Check In
                </a>
                <a href="#shopperLimitModal"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition"
                    style="color: #fff; float:right;">
                    Update Shopper Limit
                </a>
                <h2>Shopper Queue</h2>

                
                <div class="container">
                    <div id="content">
                        <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
                            <li class="active"><a href="#active" data-toggle="tab">Active ({{ count($shoppers['active']) }})</a></li>
                            <li><a href="#pending" data-toggle="tab">Pending ({{ count($shoppers['pending']) }})</a></li>
                            <li><a href="#completed" data-toggle="tab">Completed ({{ count($shoppers['completed']) }})</a></li>
                        </ul>
                        <div id="my-tab-content" class="tab-content">
                            <div class="tab-pane active" id="active">
                                <table class="w-full whitespace-no-wrapw-full whitespace-no-wrap mt-6">
                                    <thead>
                                        <tr>
                                            <th>Status</th>
                                            <th>Shopper Name</th>
                                            <th> Email</th>
                                            <th>Check-In</th>
                                            <th>Check-Out</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if( isset( $shoppers['active'] ) && is_iterable( $shoppers['active'] ) )
                                            @if( count( $shoppers['active'] )  >= 1 )
                                                @foreach( $shoppers['active'] as $shopper )

                                                    <tr class="text-center">
                                                        <td class="border px-6 py-4">
                                                            <div class="inline-flex items-center px-4 py-2 bg-green-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase transition">
                                                                    Active
                                                            </div>
                                                        </td>

                                                        <td class="border px-6 py-4">
                                                            {{ $shopper['first_name'].' '.$shopper['last_name'] }}
                                                        </td>

                                                        <td class="border px-6 py-4">
                                                            {{ $shopper['email'] }}
                                                        </td>

                                                        <td class="border px-6 py-4">
                                                            {{ $shopper['check_in'] }}
                                                        </td>

                                                        <td class="border px-6 py-4">
                                                            <a href="{{ url('/shopper/'.$storeUuid.'/'.$locationUuid.'/'.$shopper['uuid']) }}" class="inline-flex items-center px-4 py-2 bg-blue-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase transition">
                                                                Checkout
                                                            </a>
                                                        </td>
                                                    </tr>



                                                                                                        </tr>
                                                @endforeach
                                            @endif
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane" id="pending">
                                <table class="w-full whitespace-no-wrapw-full whitespace-no-wrap mt-6">
                                    <thead>
                                        <tr>
                                            <th>Status</th>
                                            <th>Shopper Name</th>
                                            <th> Email</th>
                                            <th>Check-In</th>
                                            <th>Check-Out</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if( isset( $shoppers['pending'] ) && is_iterable( $shoppers['pending'] ) )
                                            @if( count( $shoppers['pending'] )  >= 1 )
                                                @foreach( $shoppers['pending'] as $shopper )
                                                    <tr class="text-center">
                                                        <x-shopper.listing :shopper="$shopper"/>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane" id="completed">
                                <table class="w-full whitespace-no-wrapw-full whitespace-no-wrap mt-6">
                                    <thead>
                                        <tr>
                                            <th>Status</th>
                                            <th>Shopper Name</th>
                                            <th> Email</th>
                                            <th>Check-In</th>
                                            <th>Check-Out</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if( isset( $shoppers['completed'] ) && is_iterable( $shoppers['completed'] ) )
                                            @if( count( $shoppers['completed'] )  >= 1 )
                                                @foreach( $shoppers['completed'] as $shopper )
                                                    <tr class="text-center">
                                                        <x-shopper.listing :shopper="$shopper"/>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>

            </div>


            <!-- CHECKIN MODAL -->
            <div id="checkinModal" class="modalDialog">
                <div>	
                    <a href="#close" title="Close" class="close">X</a>

                    <div class="mb-4">
                        <div style="font-size: 24px; padding-top: 20px;">Shopper Portal</div>
                    </div>

                    <div class="mb-4">
                        <hr />
                    </div>
                    <form action="{{ url('shopper/'.$storeUuid.'/'.$locationUuid) }}" method="POST">
                    @csrf
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="storeName">
                                First Name:
                            </label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                    id="first_name" 
                                    name="first_name" 
                                    type="text" 
                                    placeholder="First Name">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="storeName">
                                Last Name:
                            </label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                    id="last_name" 
                                    name="last_name" 
                                    type="text" 
                                    placeholder="Last Name">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="storeName">
                                Email Address:
                            </label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                    id="email" 
                                    name="email" 
                                    type="text" 
                                    placeholder="Email Address">
                        </div>
                        <div class="mb-4">

                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition"
                                style="color: #fff">
                                Check In
                            </button>

                        </div>
                    </form>
                </div>
            </div>

            <!-- SHOPPER LIMIT MODAL -->
            <div id="shopperLimitModal" class="modalDialog">
                <div>	
                    <a href="#close" title="Close" class="close">X</a>

                    <div class="mb-4">
                        <div style="font-size: 24px; padding-top: 20px;">Shopper Limit</div>
                    </div>

                    <div class="mb-4">
                        <hr />
                    </div>
                    <form action="{{ url('shopper/limit/'.$storeUuid.'/'.$locationUuid) }}" method="POST">
                     @csrf
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="storeName">
                                Specify maximum number of shopper allowed:
                            </label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                    id="shopper_limit" 
                                    name="shopper_limit" 
                                    type="number" 
                                    value="{{ $location['shopper_limit'] }}">
                        </div>
                        <div class="mb-4">

                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition"
                                style="color: #fff">
                                Update
                            </button>

                        </div>
                    </form>
                </div>
            </div>
            

        </div>
    </div>
</x-guest-layout>
