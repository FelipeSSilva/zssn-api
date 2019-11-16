<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSurvivorsRequest;
use App\Http\Requests\TradeSurvivorsRequest;
use App\Http\Requests\UpdateSurvivorsRequest;
use App\InfectionReport;
use App\Item;
use App\Resource;
use App\Survivor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SurvivorsController extends Controller
{
    public function index()
    {
        $survivors = Survivor::get();
        return response()->json($survivors);
    }

    public function store(StoreSurvivorsRequest $request)
    {
        $survivor = new Survivor();
        $survivor->fill($request->all());
        $survivor->save();

        if ($request->resources) {
            foreach ($request->resources as $itemName => $quantity) {
                $item = Item::where('name', 'like', '%' . $itemName . '%')->first();
                if (!empty($item->id)) {
                    $resource = new Resource();
                    $resource->item_id = $item->id;
                    $resource->survivor_id = $survivor->id;
                    $resource->quantity = $quantity;

                    $resource->save();
                }
            }
        }

        return response()->json($survivor, 201);

    }

    public function update(UpdateSurvivorsRequest $request, $id)
    {
        $survivor = Survivor::find($id);

        if (!$survivor) {
            return response()->json([
                'message' => 'Survivor not found',
            ], 404);
        }

        $survivor->fill($request->all());
        $survivor->save();

        return response()->json($survivor);
    }

    public function reportInfection($survivor_reporter_id, $survivor_infected_id)
    {
        $survivorReporter = Survivor::find($survivor_reporter_id);
        if (!$survivorReporter) {
            return response()->json([
                'message' => 'Survivor not found',
            ], 404);
        }

        $survivorInfected = Survivor::find($survivor_infected_id);
        if (!$survivorInfected) {
            return response()->json([
                'message' => 'Survivor not found',
            ], 404);
        }

        $reportCreated = InfectionReport::where('survivor_reporter_id', $survivor_reporter_id)->where('survivor_infected_id',$survivor_infected_id)->first();
        if($reportCreated){
            return response()->json([
                'message' => 'Report already created',
            ], 400);
        }


        $infectedReport = new InfectionReport();
        $infectedReport->survivor_reporter_id = $survivor_reporter_id;
        $infectedReport->survivor_infected_id = $survivor_infected_id;
        $infectedReport->save();

        if ($survivorInfected->infectionReportsCount >= 3) {
            if ($survivorInfected->infected != 'Y') {
                $survivorInfected->infected = 'Y';
                $survivorInfected->save();
            }

            return response()->json([
                'message' => 'Survivor infected!',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Unconfirmed infection!',
            ], 200);
        }
    }

    public function tradeItems(TradeSurvivorsRequest $request, $survivor_offer_id, $survivor_accept_id)
    {
        $survivorOffer = Survivor::find($survivor_offer_id);
        if (!$survivorOffer) {
            return response()->json([
                'message' => 'Survivor not found',
            ], 404);
        } elseif ($survivorOffer->infected == 'Y') {
            return response()->json([
                'message' => 'Survivor infected!',
            ], 400);
        }

        $survivorAccept = Survivor::find($survivor_accept_id);
        if (!$survivorAccept) {
            return response()->json([
                'message' => 'Survivor not found',
            ], 404);
        } elseif ($survivorAccept->infected == 'Y') {
            return response()->json([
                'message' => 'Survivor infected!',
            ], 400);
        }

        $checkItemEquality = self::checkItemsEquality($request->resourceSurvivorOffer, $request->resourceSurvivorAccept);
        if ($checkItemEquality == false) {
            return response()->json([
                'message' => 'Different points!',
            ], 400);
        }

        $checkQuantityMinSurvivorOffer = self::checkQuantityMin($request->resourceSurvivorOffer, $survivorOffer);
        if ($checkQuantityMinSurvivorOffer == false) {
            return response()->json([
                'message' => 'No resources available!',
            ], 400);
        }

        $checkQuantityMinSurvivorAccept = self::checkQuantityMin($request->resourceSurvivorAccept, $survivorAccept);
        if ($checkQuantityMinSurvivorAccept == false) {
            return response()->json([
                'message' => 'No resources available!',
            ], 400);
        }

        foreach ($request->resourceSurvivorOffer as $itemName => $quantityTrade) {
            $item = Item::where('name', 'like', '%' . $itemName . '%')->first();
            $resourceSurvivorOffer = Resource::where('item_id','=',$item->id)->where('survivor_id','=',$survivor_offer_id)->first();
            $resourceSurvivorOffer->quantity -= $quantityTrade;
            $resourceSurvivorOffer->save();

            $resourceSurvivorAccept = Resource::where('item_id','=',$item->id)->where('survivor_id','=',$survivor_accept_id)->first();

            if(!empty($resourceSurvivorAccept)){
                $resourceSurvivorAccept->quantity += $quantityTrade;

                $resourceSurvivorAccept->save();
            }else{
                $resourceSurvivorAccept = new Resource();
                $resourceSurvivorAccept->survivor_id = $survivor_accept_id;
                $resourceSurvivorAccept->item_id = $item->id;
                $resourceSurvivorAccept->quantity = $quantityTrade;
                $resourceSurvivorAccept->save();
            }
        }

        foreach ($request->resourceSurvivorAccept as $itemName => $quantityTrade) {
            $item = Item::where('name', 'like', '%' . $itemName . '%')->first();
            $resourceSurvivorAccept = Resource::where('item_id','=',$item->id)->where('survivor_id','=',$survivor_accept_id)->first();
            $resourceSurvivorAccept->quantity -= $quantityTrade;
            $resourceSurvivorAccept->save();

            $resourceSurvivorOffer = Resource::where('item_id','=',$item->id)->where('survivor_id','=',$survivor_offer_id)->first();
            if(!empty($resourceSurvivorOffer)){
                $resourceSurvivorOffer->quantity += $quantityTrade;
                $resourceSurvivorOffer->save();
            }else{
                $resourceSurvivorOffer = new Resource();
                $resourceSurvivorOffer->survivor_id = $survivor_offer_id;
                $resourceSurvivorOffer->item_id = $item->id;
                $resourceSurvivorOffer->quantity = $quantityTrade;
                $resourceSurvivorOffer->save();
            }
        }

        return response()->json([
            'message' => 'Trade done!',
        ], 200);
    }

    public function percentageInfected(){
        $countAllSurvivors = Survivor::count();
        $countInfetedSurvivors = Survivor::where('infected','Y')->count();

        $percentageInfected = (100 * $countInfetedSurvivors) / $countAllSurvivors;

        return response()->json([
            'percentageInfected' => $percentageInfected,
        ], 200);
    }

    public function percentageNonInfected(){
        $countAllSurvivors = Survivor::count();
        $countNonInfetedSurvivors = Survivor::where('infected','N')->count();

        $percentageNonInfected = (100 * $countNonInfetedSurvivors) / $countAllSurvivors;

        return response()->json([
            'percentageNonInfected' => $percentageNonInfected,
        ], 200);
    }

    public function averageAmount(){
        $countAllSurvivors = Survivor::count();
        $allItems = Item::get();
        $return = array();
        foreach ($allItems as $item){
            $countAllItem = Resource::where('item_id',$item->id)->sum('quantity');

            $return[$item->name] = $countAllItem / $countAllSurvivors;
        }

        return response()->json([
            $return
        ], 200);
    }

    public function pointsLost($survivor_infected_id){
        $survivorInfected = Survivor::find($survivor_infected_id);
        if (!$survivorInfected) {
            return response()->json([
                'message' => 'Survivor not found',
            ], 404);
        }

        if($survivorInfected->infected == 'N'){
            return response()->json([
                'message' => 'Survivor not infected, yet...',
            ], 400);
        }

        $countAllItems = Resource::where('survivor_id',$survivor_infected_id)->sum('quantity');
        return response()->json([
            'pointsLost' => $countAllItems,
        ], 200);
    }

    private static function checkItemsEquality($resourceSurvivorOffer, $resourceSurvivorAccept)
    {
        $countPointsSurvivorOffer = 0;
        $countPointsSurvivorAccept = 0;
        $equal = false;

        foreach ($resourceSurvivorOffer as $itemName => $quantity) {
            $item = Item::where('name', 'like', '%' . $itemName . '%')->first();
            if ($item) {
                $countPointsSurvivorOffer += ($quantity * $item->points);
            } else {
                return response()->json([
                    'message' => 'Item not found!',
                ], 404);
            }
        }

        foreach ($resourceSurvivorAccept as $itemName => $quantity) {
            $item = Item::where('name', 'like', '%' . $itemName . '%')->first();
            if ($item) {
                $countPointsSurvivorAccept += ($quantity * $item->points);
            } else {
                return response()->json([
                    'message' => 'Item not found!',
                ], 404);
            }
        }

        if ($countPointsSurvivorOffer == $countPointsSurvivorAccept) {
            $equal = true;
        }

        return $equal;
    }

    private static function checkQuantityMin($resourceSurvivor, Survivor $survivor)
    {
        foreach ($resourceSurvivor as $itemName => $quantityTrade) {
            $itemQuantitySurvivor = $survivor->checkItemQuantity($itemName);

            if ($itemQuantitySurvivor < $quantityTrade) {
                return false;
            }
        }

        return true;
    }
}
