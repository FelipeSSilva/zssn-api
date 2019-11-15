<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSurvivorsRequest;
use App\Http\Requests\UpdateSurvivorsRequest;
use App\InfectionReport;
use App\Item;
use App\Resource;
use App\Survivor;
use Illuminate\Http\Request;

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

    public function reportInfection($survivor_reporter_id, $survivor_infected_id){
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


        $infectedReport = new InfectionReport();
        $infectedReport->survivor_reporter_id = $survivor_reporter_id;
        $infectedReport->survivor_infected_id = $survivor_infected_id;
        $infectedReport->save();

        if($survivorInfected->infectedReportsCount >= 3){
            if($survivorInfected->infected != 'Y'){
                $survivorInfected->infected = 'Y';
                $survivorInfected->save();
            }

            return response()->json([
                'message' => 'Survivor infected!',
            ], 200);
        }else{
            return response()->json([
                'message' => 'Unconfirmed infection!',
            ], 200);
        }
    }
}
