<?php

namespace App\Http\Controllers\Shopper;

use App\Http\Controllers\Controller;
use App\Services\Store\StoreService;
use App\Services\Store\Location\LocationService;
use App\Services\Shopper\ShopperService;
use App\Models\Shopper\Shopper;
use App\Models\Store\Location\Location;
use App\Models\Store\Store;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShopperQueueController extends Controller
{
    protected $store;
    protected $locationService;
    protected $shopperService;
    protected $shopper;
    protected $location;


    public function __construct(StoreService $store, 
                                LocationService $locationService, 
                                Shopper $shopper, 
                                Location $location,
                                ShopperService $shopperService)
    {
        $this->store = $store;
        $this->locationService = $locationService;
        $this->shopper = $shopper;
        $this->location = $location;
        $this->shopperService = $shopperService;
    }

    public function index () 
    {
        $store = $this->store->all();

        return view('shopper.index')
            ->with('stores', $store ?? null);
    }

    public function storeLocation ($uuid) 
    {
        $store = $this->store->show(
            [
                'uuid' => $uuid
            ],
        );

        return view('shopper.location')
            ->with('store', $store ?? null);
    }

    public function queue ($storeUuid, $locationUuid) 
    {
        $location = $this->locationService->show(
            [
                'uuid' => $locationUuid
            ],
            [
                'Shoppers',
                'Shoppers.Status'
            ]
        );

        $shoppers = null;
        
        if( isset($location['shoppers']) && count($location['shoppers']) >= 1 ){
            $shoppers = $this->locationService->getShoppers($location['shoppers']);
        }
    
        $this->updateShoppers($shoppers['active']);

        return view('shopper.queue')
            ->with('location', $location)
            ->with('shoppers', $shoppers)
            ->with('storeUuid', $storeUuid)
            ->with('locationUuid', $locationUuid);
    }

    public function checkin ($storeUuid, $locationUuid, Request $request)
    {
        $location = $this->locationService->show(
            [
                'uuid' => $locationUuid
            ]
        );
        
        $payload = [
            'uuid' => $this->generateUuid(), 
            'location_id' => $location['id'],
            'status_id' => 1,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email, 
            'check_in' => Carbon::now()->format('Y-m-d H:i:s')
        ];
        
        $this->shopper->create($payload);
        return redirect()->to('shopper/'.$storeUuid.'/'.$locationUuid)
                        ->with('msg', $request->first_name.' '.$request->last_name.' has been checked in');
    }

    public function checkout($storeUuid, $locationUuid, $shopperUuid)
    {
        $shopper = $this->shopper->where('uuid',$shopperUuid) ->first();
        $shopper['check_out'] = Carbon::now()->format('Y-m-d H:i:s');
        $shopper['status_id'] = 2; // Completed
        $shopper->save();

        $location = $this->locationService->show(['uuid' => $locationUuid]);

        $shoppers = $this->shopper->where('location_id',$location['id'])
                                  ->where('status_id', 1)
                                  ->count();
        
        if ($shoppers < $location['shopper_limit']) 
        {
            $updatePendingShopper = $this->shopper->where('status_id', 3)->where('location_id', $location['id'])->orderBy('check_in', 'asc')->first();
            $updatePendingShopper['status_id'] = ($shoppers['active'] < $location['shopper_limit']) ? 1 : 3; // Active | Pending
            $updatePendingShopper->save();
        }

        return redirect()->to('shopper/'.$storeUuid.'/'.$locationUuid)
                        ->with('msg', $shopper['first_name'].' '.$shopper['last_name'].' has been checked out');
    }

    public function shopperLimit ($storeUuid, $locationUuid, Request $request) 
    {
        $location = $this->location->where('uuid', $locationUuid)->first();
        $location['shopper_limit'] = $request['shopper_limit'];
        $location->save();
        return redirect()->to('shopper/'.$storeUuid.'/'.$locationUuid)
                        ->with('msg', 'Maximum allowed shopper limit has been updated');
    }

    private function updateShoppers ($shoppers) 
    {
        // return $shoppers['active'];
        foreach ($shoppers as $key => $value) 
        {
            $shopperCheckIn = Carbon::parse($value['check_in'])->format('Y-m-d H:i:s');
            $checkInValidUntil = Carbon::parse($value['check_in'])->addHours(2)->format('Y-m-d H:i:s');
           
            if (Carbon::Now()->format('Y-m-d H:i:s') >= $checkInValidUntil) {
                $update = $this->shopper->find($value['id']);
                $update['check_out'] = Carbon::now()->format('Y-m-d H:i:s');
                $update['status_id'] = 2;
                $update->save();
            }
        }
    }
    /**
     * @return string
     */
    private function generateUuid(): string
    {
        return (string) Str::uuid();
    }
}
