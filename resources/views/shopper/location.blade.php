<x-guest-layout>



    <div class="pt-4 bg-gray-100">
        <div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0">
            <div>
                <x-jet-authentication-card-logo />
            </div>

            <div class="w-full sm:max-w-2xl mt-6 p-6 bg-white shadow-md overflow-hidden sm:rounded-lg prose">
                Select Location
                <table class="w-full whitespace-no-wrapw-full whitespace-no-wrap mt-6">
                    <tbody>
                    @if( isset($store['locations']) )
                        @foreach($store['locations'] as $location)
                            <tr class="text-center">

                                <x-table-column>
                                    {{ $location['location_name'] ?? null }}
                                </x-table-column>

                                <x-table-column>
                                    @if( isset($location['uuid']) )
                                    <a href="{{ url('shopper/'.$store['uuid'].'/'.$location['uuid']) }}" class="inline-flex items-center px-4 py-2  border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                                        Select
                                    </a>
                                    @endif
                                </x-table-column>

                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-guest-layout>
